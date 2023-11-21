<?php

// ALT + 60 "<"
// ALT + 62 ">"
// ALT + 92 "\"

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); // esta en esta ubicacion (__DIR__) // busca ".env"
$dotenv->safeLoad(); // para que no tire error.

/*Lo que hace el metodo "safeLoad()" es que si no existe el .env
no nos va marcar un error, eso es importante porque al menos 
aqui sabemos que si va existir, pero en el servidor esas
variables de entorno se van a inyectar directamente por
un panel especial, y entonces es probable que ese archivo no 
exista sino que exista mas bien en alguna configuracion del servidor. */

require 'funciones.php';
require 'database.php';

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);