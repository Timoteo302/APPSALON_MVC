<?php 

namespace Model;

/*Creamos este modelo que tenga en memoria las mismas columnas
que nos trae la base de datos, porque en la base de datos en la 
consulta que hacemos, es como que nos "crea" una tabla, luego de unir
las tablas con la informacion que queremos traer. 

/*Este no consulta una tabla, consulta 4 tablas.
Una vez que consultemos los datos se mantienen en memoria.*/

class AdminCita extends ActiveRecord {
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id','hora','cliente','email','telefono','servicio','precio'];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct($args = [])
    { 
        $this->id = $args['id'] ?? null;
        $this->hora= $args['hora'] ?? ''; 
        $this->cliente= $args['cliente'] ?? '';
        $this->email= $args['email'] ?? '';
        $this->telefono= $args['telefono'] ?? '';
        $this->servicio= $args['servicio'] ?? '';
        $this->precio= $args['precio'] ?? '';
    }
}