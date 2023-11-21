<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}


function esUltimo(string $actual, string $proximo) : bool{
    if($actual !== $proximo){
        return true;
    }
    return false;
}


// Funcion que revisa que el usuario este autenticado.
function isAuth() : void{
    /*la hacemos para que no nos tire un error de variable "nombre"  y "id" de
    $_SESSION. */
    
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

/*Si el usuario no es un admin, lo enviamos a la pagina
principal para que pueda iniciar sesion. */
function isAdmin() : void {
    if(! isset($_SESSION['admin'])) {
        header('Location: /');
    }
}


// MUESTRA LOS MENSAJES
function mostrarNotificacion($codigo){
    $mensaje = '';

    switch($codigo){
        case 1:
            $mensaje = 'Creado Correctamente';
             break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado Correctamente';
            break;   
        
        default:
            $mensaje = false;
            break;
    }

    return $mensaje;
}
