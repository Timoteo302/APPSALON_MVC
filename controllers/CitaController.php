<?php

namespace Controllers;

use DateTime;
use MVC\Router;
use Model\Servicio;

class CitaController{

    public static function index(Router $router){
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // debuguear($_SESSION);
        isAuth();
        // debuguear($_POST);
        
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}