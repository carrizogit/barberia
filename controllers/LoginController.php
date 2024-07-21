<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
        $auth = new Usuario();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //comprobar si el usuario existe
                $usuario = Usuario::where('email', $auth->email);
                if($usuario) {
                    if($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        //autenticar el usuario
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else {
                            header('Location: /cita');
                        }
                    }

                }else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmailRecuperacion();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado === '1') {
                    $usuario->crearToken();
                    $usuario->guardar();

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu casilla de correo');
                }else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    
                }
            }
        }
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null; //reseteamos el campo password para colocarl el nuevo
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                if($resultado) {
                    header('Location: /');
                }

            }

        }
    
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario; //lo iniciamos aqui para mantener los datos ya cargados

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST); //sincroniza el objeto que estaba vacio de arriba Usuario y mantiene los datos
            $alertas =  $usuario->validarNuevaCuenta();
            
            if(empty($alertas)) {
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else {
                    //hashear password
                    $usuario->hashPassword();

                    //generer token unico
                    $usuario->crearToken();

                    //enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //crea el usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
            
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router ) {
        
        $router->render('auth/mensaje', [

        ]);
    }

    public static function confirmar(Router $router ) {
        $alertas = [];

        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');

        }else {
            //modificar a usuario modificado
            $usuario->confirmado = '1';
            $usuario->token = null;

            $usuario->guardar(); //para qeu actualice el campo confirmado y token

            Usuario::setAlerta('exito', 'Cunenta Comprobada Correctamente');    
        }

        $alertas = Usuario::getAlertas(); //como creamos la alerta sobre la marcha ponemos esto
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}