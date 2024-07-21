<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios); //convertir un arreglo a json
    }

    public static function guardar() {
        //almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //almacena los servicios con el Id de la cita 
        $idServicios = explode(',', $_POST['servicios']); //explode separa la cadena de string por la coma

        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar() ;
        }
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);//http refere lo sacamos cuando debugueamos $_SERVER y nos direcciona a la misma pagina
        }
    }
}