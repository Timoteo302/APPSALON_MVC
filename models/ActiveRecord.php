<?php
namespace Model;


class ActiveRecord {

    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];
    protected static $alertas = [];

    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    public static function consultarSQL($query) {
        $resultado = self::$db->query($query);

        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        $resultado->free();

        return $array;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;

        if($registro){
            foreach ($registro as $key => $value) {
                if (property_exists($objeto, $key)) {
                    $objeto->$key = $value;
                }
            }
        }

        return $objeto;
    }

    public function atributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = $value;
        }

        return $sanitizado;
    }

    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function guardar() {
        $resultado = '';

        if (!is_null($this->id)) {
            $resultado = $this->actualizar();
        } else {
            $resultado = $this->crear();
        }
        return $resultado;
    }

    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return static::crearObjeto($resultado);
    }

    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param('s', $valor);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return static::crearObjeto($resultado);
    }

    // Consultar por fecha-dia-actual
    public static function where_date($columna, $valor) {
        $query = "SELECT * FROM " . 'citas' . " WHERE DATE_FORMAT($columna, '%Y-%m-%d') = ?";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param('s', $valor);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $filas = [];
        while ($row = $result->fetch_assoc()) {
            $filas[] = $row;
        }
        $stmt->close();
        return $filas;
    }
    

    public static function SQL($consulta) {
        $query = $consulta;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT ?";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return static::crearObjeto($resultado);
    }

    public function crear() {
        $atributos = $this->sanitizarAtributos();

        $placeholders = implode(', ', array_fill(0, count($atributos), '?'));

        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= implode(', ', array_keys($atributos));
        $query .= ") VALUES (";
        $query .= $placeholders;
        $query .= ")";

        $values = array_values($atributos);

        $stmt = self::$db->prepare($query);

        if ($stmt) {
            $types = str_repeat('s', count($values));
            $stmt->bind_param($types, ...$values);

            $stmt->execute();

            $resultado = $stmt->affected_rows;

            $stmt->close();

            return [
                'resultado' => $resultado,
                'id' => self::$db->insert_id
            ];
        } else {
            return false;
        }
    }

    public function actualizar() {
        $atributos = $this->sanitizarAtributos();

        $values = array_values($atributos);
        $values[] = $this->id;

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= implode('=?, ', array_keys($atributos)) . '=? ';
        $query .= "WHERE id = ? LIMIT 1";

        $stmt = self::$db->prepare($query);

        if ($stmt) {
            $types = str_repeat('s', count($values));
            $stmt->bind_param($types, ...$values);

            $stmt->execute();

            $resultado = $stmt->affected_rows;

            $stmt->close();

            return $resultado;
        } else {
            return false;
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = ? LIMIT 1";

        $stmt = self::$db->prepare($query);

        if ($stmt) {
            $stmt->bind_param('i', $this->id);
            $stmt->execute();

            $resultado = $stmt->affected_rows;

            $stmt->close();

            return $resultado;
        } else {
            return false;
        }
    }
}
