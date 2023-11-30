<?php

namespace Model;

/*Active Record mantiene una referencia en memoria, crea un objeto exactamente igual a lo
que tenemos en nuestra BD por lo tanto tenemos que crear los atributos de las columnas
en nuestra clase.*/

class Usuario extends ActiveRecord{
    // Base de datos
    protected static $tabla = 'usuarios';

    /*Estos datos son muy importantes porque cuando actualizamos o insertamos un registro
    en memoria se mantiene esa referencia (que son los datos que tenemos dentro de este
    arreglo). De otra forma puede ser que tengamos problemas de datos faltantes, porque estamos
    tratando de crear un registro y alguno de ellos no se cumple.

    *Es un espejo con las mismas columnas que tenemos en nuestra tabla de la BD*/
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado','token'];

    // Creamos un atributo por cada uno de ellos (inician vacio)
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    /*al ser "public" podemos acceder a ellos ya sea en la clase misma o en el
    objeto una vez que sea instanciado.*/


    /*Aqui una vez yo instancie esta clase, vamos a ir agregando los argumentos
    con los atributos que definimos arriba, le agregamos el argumento a cada respectivo
    atributo.
    "$args[]" arreglo asociativo*/
    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0'; //bool
        $this->confirmado = $args['confirmado'] ?? '0'; //bool
        $this->token = $args['token'] ?? '';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta(){

        /*"self::$alertas"
        porque ActiveRecord tiene el atributo de $alertas, pero una vez
        que se hereda va existir aqui en usuario, entonces hace referencia
        a la clase en la cual estamos utilizando este atributo de la otra 
        clase (activeRecord) */
        
        if(!$this->nombre){
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if(!$this->apellido){
            self::$alertas['error'][] = "El apellido es obligatorio";
        }

        if(!$this->email){
            self::$alertas['error'][] = "El Email es obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][] = "La contraseña es obligatorio";
        }

        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "La contraseña debe contener al menos 6 caracteres";
        }

        if(!$this->telefono){
            self::$alertas['error'][] = "El telefono es obligatorio";
        }

        return self::$alertas;
    }

    // Validar el login (solo email y password)
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }

        if(!$this->password){
            self::$alertas['error'][] = "La Contraseña es Obligatorio o Incorrecto";
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = "El Email es Obligatorio";
        }
        return self::$alertas;
    }


    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = "La contraseña es Obligatorio";
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = "La contraseña debe tener al menos 6 caracteres";
        }
        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario() {   

        //Comilla sencilla '' el email, pq es un string.
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        // debuguear($query);

        $resultado = self::$db->query($query);
        // debuguear($resultado);

        // si hay un resultado significa que esa persona ya esta registrada
        if($resultado->num_rows){
            self::$alertas['error'][] = "El usuario ya está registrado";
        }

        return $resultado;
    }


    // Hashear password 
    public function hashPassword() {
        /*Hasheamos el password, con una funcion de php llamada: "password_hash()
        recibe 2 valores, 1 es el password y el 2 es el metodo de hash, que seria
        "PASSWORD_BCRYPT". 
        
        lo reescribe y ya no lo podemos ver y tampoco devolverlo para saber cual era.*/
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        // Funcion "uniqid()" lo que hace es que genera un par de numeros
        //como un id unico.
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        // debuguear($this);
        $resultado = password_verify($password, $this->password);
        
        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Contraseña Incorrecta o tu cuenta no ha sido confirmada';
        } else{
            return true;
        }

    }

    
}