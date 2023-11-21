<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {

    public static function index(Router $router) {

        isAdmin();
        
        $servicios = Servicio::all();

        $resultado = $_GET['r'] ?? null;

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios,
            'resultado' => $resultado
        ]);
    }


    public static function crear(Router $router) {

        isAdmin();


        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->crear();
                header('Location: /servicios?r=1');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }


    public static function actualizar(Router $router) {

        isAdmin();

        
        if(!is_numeric($_GET['id'])) return; // se detiene la ejecucion si es falso
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->actualizar();
                header('Location: /servicios?r=2');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }


    public static function eliminar() {

        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            // debuguear($servicio);
            $servicio->eliminar();
            header('Location: /servicios?r=3q');
        }

    }
}