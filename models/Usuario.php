<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $password1;
    public $elefono;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password1 = $args['password1'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido es obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }

        if(!$this->telefono) {
            self::$alertas['error'][] = 'El Telefono es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es obligatorio';
        }

        if(strlen( $this->password) < 4) {
            self::$alertas['error'][] = 'La Contraseña debe tener al menos 4 caractéres';
        }

        if($this->password !=  $this->password1) {
            self::$alertas['error'][] = 'La Contraseña no son iguales';
        }

        return self::$alertas;
    }

    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1"; 
        $resultado = self::$db->query($query);
        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }
        return $resultado;
    }

    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function validarLogin() {

        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'La Contraseña es obligatorio';
        }

        return self::$alertas;
    }

    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Contraseña incorrecta o cuenta no verificada';
        }else {
            return true;
        }
    }

    public function validarEmailRecuperacion() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        if($this->password !== $this->password1) {
            self::$alertas['error'][] = 'La contraseña no son iguales';
        }

        return self::$alertas;
    }

}