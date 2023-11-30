<h1 class="nombre-pagina">Actualizar Servicio</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<div class="cont-a">
    <a href="/servicios" class="boton">Volver</a>
</div>

<?php
    // include_once __DIR__ . '/../template/barra.php';
    include_once __DIR__ . '/../template/alertas.php';
?>

<!--Eliminamos el action porque como en actualizar viene con un 
id, y nuestro routing solo lee la url "servicios/actualizar"
lo que hacemos es borrarle el action para que se mande automaticamente
a la misma url en que estamos, y va respertar el id.

action="/servicios/actualizar"  <-- borrar.  -->

<form method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" class="boton" value="Actualizar Servicio">
</form>