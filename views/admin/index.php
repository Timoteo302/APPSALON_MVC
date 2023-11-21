<h1 class="nombre-pagina">Panel de Administración</h1>

<!--PARA CERRAR LA SESION-->
<?php 
    include_once __DIR__ . '/../template/barra.php';
?>

<h2>Buscar Citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha; ?>"
            >
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0){
        echo "<h3>No Hay Citas en esta fecha</h3>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
    <?php 
        $idCita = 0;
        // debuguear($citas);

        /*
        En arreglos numéricos:
            $key se refiere al índice, // 0,1,2,3,4.
        En arreglos asociativos:
            $key se refiere a la clave y $value al valor asociado a esa clave.*/

    foreach( $citas as $key => $cita ){ //iteramos sobre cada una de las citas.
        // debuguear($key);

        // si son != entra y si son = no entra
        if($idCita !== $cita->id) {
            /*verificamos que no haya id iguales pq sino
            se vuelve a repetir la misma cita */
            $total = 0;
            
        ?>
            <li>
                <p>ID: <span><?php echo $cita->id ?></span></p>
                <p>Hora: <span><?php echo $cita->hora ?></span></p>
                <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
                <p>Email: <span><?php echo $cita->email ?></span></p>
                <p>Telefono: <span><?php echo $cita->telefono ?></span></p>

                <h3>Servicios</h3>
            <?php

            $idCita = $cita->id;
            
            //Los servicios si requiero que se repitan
        } // Fin de IF 
        
            $total += $cita->precio;
        ?>
                <p class="servicio"> <?php echo $cita->servicio . " $" . $cita->precio; ?> </p>  


            <?php 
            $actual = $cita->id;
            $proximo = $citas[$key + 1]->id ?? 0;
            /* "?? 0", porque va ser el ultimo elemento + 1
            entonces cuando estemos en el ultimo elemento va
            marcar como undefined entonces para que no suceda eso
            en el ultimo se pondra un 0 */

            if(esUltimo($actual, $proximo)) { ?>
                <p class="total">Total: <span>$<?php echo $total ?></span></p>


                <form action="/api/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $cita->id; ?>">

                    <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
        <?php }
            // echo "<hr>";
            // echo $actual;
            // echo "<hr>";
            // echo $proximo;
            
    } // Fin de Foreach() ?>
    </ul>
</div>

<?php 
    $script = "<script src='build/js/buscador.js'></script>";
?>