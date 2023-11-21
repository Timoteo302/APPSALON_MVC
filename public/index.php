<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIController;
use Controllers\CitaController;
use Controllers\LoginController;
use Controllers\ServicioController;
use MVC\Router;

$router = new Router();

// Iniciar sesión
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Recuperar Password --- forget(olvidar)
$router->get('/forget', [LoginController::class, 'forget']);
$router->post('/forget', [LoginController::class, 'forget']);

// Otra vista para recuperar contraseña desde el email que mandamos nosotros
$router->get('/recuperar', [LoginController::class, 'recuperar']);
$router->post('/recuperar', [LoginController::class, 'recuperar']);

// Crear cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);
$router->post('/crear-cuenta', [LoginController::class, 'crear']);

// Confirmar cuenta
$router->get('/confirmar', [LoginController::class, 'confirmar']);

$router->get('/mensaje', [LoginController::class, 'mensaje']);


// AREA PRIVADA
$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);

// API DE CITAS
$router->get('/api/servicios', [APIController::class, 'index']);

/*URL que se va encargar de leer los datos que mandare por medio de
formData(), que es la cita que se mandaria cuando se presione "Reservar" */
$router->post('/api/citas', [APIController::class, 'guardar']);

/*Para eliminar, en una API no seria un metodo POST y no seria
eliminar, seria un metodo tipo "delete", no soportado automaticamente
la mayoria de los frameworks si lo van a soportar, pero HTTP
por si solo no lo soporta, solo get y post, entonces usamos un post. */
$router->post('/api/eliminar',[APIController::class, 'eliminar']);
$router->get('/api/citas',[APIController::class, 'obtener_citas']);  // obtener las citas segun la fecha seleccionada

// CRUD DE SERVICIOS
$router->get('/servicios', [ServicioController::class,'index']);
$router->get('/servicios/crear', [ServicioController::class,'crear']);
$router->post('/servicios/crear', [ServicioController::class,'crear']);
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();