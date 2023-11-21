<?php

namespace Controllers;

use DateTime;
use Model\Cita;
use MVC\Router;
use Model\Servicio;
use Model\CitaServicio;

class APIController {
    
    public static function index(){

        $servicios = Servicio::all();
        // Arreglo con los objetos de servicios -->
        // debuguear($servicios);

        // Convertir arreglo a JSON -->
        echo json_encode($servicios);
    }

    public static function obtener_citas(){

        $fechaActual = new DateTime();

        $fechaRestada = $fechaActual->format('Y-m-d');

        // Obtengo la fecha mandada por js
        $fecha = $_GET['fecha'] ?? $fechaRestada;

        // Busco la fecha en la bd (para ver si hay horarios ocupados)
        $citas = Servicio::where_date('fecha', $fecha);
        
        // Mando la fecha en modo json
        echo json_encode($citas);
    }

    public static function guardar(){

        // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->crear();

        $id = $resultado['id']; 


        // Almacena los Servicios con el ID de la Cita
        $idServicios = explode(",", $_POST['servicios']);

        foreach($idServicios as $idServicio){
            //constructor de CitaServicio
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->crear();
        }

        echo json_encode(['resultado' => $resultado]); // leer json en js
    }

    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];

            $cita = Cita::find($id);
            // debuguear($cita);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);

            /*Si debugueamos el $_SERVER nos da mucha informacion, una de ellas es
            HTTP_REFERER" que significa que es la pagina de donde
            veniamos antes de llegar a la que estamos. Entonces
            redigirimos al usuarios a donde estaba el usuario 
            anteriormente. */
        }
    }
}