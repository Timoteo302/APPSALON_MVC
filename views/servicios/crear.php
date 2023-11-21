<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Completa todos los campos para añadir un nuevo servicio</p>


<div class="cont-a">
    <a href="/servicios" class="boton">Volver</a>
</div>


<?php
    // include_once __DIR__ . '/../template/barra.php';
    include_once __DIR__ . '/../template/alertas.php';
?>


<form action="/servicios/crear" method="post" class="formulario">
    <?php 
        include_once __DIR__ . '/formulario.php';
    ?>

    <input type="submit" class="boton" value="Guardar Servicio">
</form>

