<?php

namespace Model;

class Cita extends ActiveRecord {
    // Base de datos config
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];

    /* $columnasDB es importante, en activeRecord lo tenemos vacio pero
    en cada clase le completamos los campos de las columnas que estan en
    la bd.
    
    Los atributos que tenemos aqui debajo con el "public" sirven cuando
    instanciamos, cuando creamos una nueva cita con lo que el usuario nos da,
    entonces ahi creamos la forma de los datos para despues ya pasarlos a activeRecord*/

    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
    }
}