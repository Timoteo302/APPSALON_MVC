
<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Restablece tu password escribiendo tu email a continuación</p>

<?php include_once __DIR__ . '/../template/alertas.php' ?>

<!--manda el action a la url, en este caso definimos la url nosotros como
"forget" en el public/index-->
<form action="forget" method="POST" class="formulario">

    <div class="campo">
        <label for="email">E-mail</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Tu E-mail">
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>