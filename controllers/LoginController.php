<?php

/*Token:
tenemos que generar un token unico para que podamos identificar que
es una persona la que crea la cuenta y no un robot nada mas llenando
formularios. */

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){

        $alertas = [];

        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            // si las alertas estan vacias, significa que usuario dio email y password
            if(empty($alertas)){
                // Verificar que la cuenta exista (usuario)
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    // Verificar el password y confirmado
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        // Autenticar al usuario, con variable de $_SESSION
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento (depende si es admin o no)
                        if($usuario->admin === 1){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                        // debuguear($_SESSION);
                    } 
                     
                }else{
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

    public static function logout(Router $router){
        
        $_SESSION = [];
        header('Location: /');
    }



    public static function forget(Router $router){
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado){
                    // debuguear('si existe y esta confirmado');
                    
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->actualizar();

                    Usuario::setAlerta('exito', 'Revisa tu email');

                    // Enviar un email con el token para el usuario restablecer la contraseña
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                } else{
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/forget-password',[
            'alertas' => $alertas
        ]);
    }



    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Válido');
            $error = true;
        }
        
        // almacenar nuevo password y guardarlo (si es que todo lo anterior paso la validacion)
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                //eliminamos el password anterior
                $usuario->password = null;
                //restablecemos el password del usuario por el que escribió en el form
                $usuario->password = $password->password;
                //hashear el password
                $usuario->hashPassword();
                //borramos el token
                $usuario->token = null;

                /*Importante, lo volvemos a asignar todo a la variable de $usuario porque esa 
                variable tiene la instancia de todo el objeto, todo lo que tenemos en la BD,
                lo que hacemos es tomar de $password (instancia solo para que el usuario digite un 
                password en el form) y nosotros lo tomamos, luego se lo asignamos de nuevo
                a la instancia padre por asi decirlo, que en este caso es $usuario.*/

                $resultado = $usuario->actualizar();
                if($resultado){
                    header('Location: /');
                }
            }
        }

        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);
    }



    public static function crear(Router $router){

        $usuario = new Usuario;

        // alertas vacias
        $alertas = [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);       
            
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta este vacio, asi pasa a ser logueado
            if(empty($alertas)){
                
                // Verificar que el usuario no este registrado ya
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else{
                    // No esta registrado
                    
                    //Hashear el password
                    $usuario->hashPassword();

                    // Generar un Token unico
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->crear();
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


    public static function mensaje(Router $router){

        $router->render('auth/mensaje');

    }

    public static function confirmar(Router $router){

        $alertas = [];

        $token = s($_GET['token']);
        // debuguear($token);
        
        $usuario = Usuario::where('token', $token);
        // debuguear($usuario);

        if(empty($usuario)){
            // Mostrar msj de error
            Usuario::setAlerta('error', 'Token No Válido');
        }else{
            // Modificar a usuario confirmado
            $usuario->confirmado = '1';
            // Eliminar token (ya usuario confirmado,para q nadie pueda hacer operaciones con ese token)
            $usuario->token = null;

            // Actualizar el registro
            $usuario->actualizar();

            // Retroalimentación al usuario
            Usuario::setAlerta('exito', 'Cuenta Confirmada Correctamente');
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);

    }
    
}