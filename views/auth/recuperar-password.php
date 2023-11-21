<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Coloca tu nuevo password a continuación</p>

<?php include_once __DIR__ . '/../template/alertas.php' ?>

<!--Por las dudas si esta mal el token, asi no aparezca el formulario.-->
<?php if($error) return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password"
        id="password"
        name="password"
        placeholder="Tu nuevo Contraseña">
    </div>

    <input type="submit" value="Guardar Nuevo Contraseña" class="boton">

</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="forget">¿Aún no tienes cuenta? Crear una</a>
</div>