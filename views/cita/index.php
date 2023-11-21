
<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>


<!--PARA CERRAR LA SESION-->
<?php 
    include_once __DIR__ . '/../template/barra.php';
?>

<div id="app">

    <nav class="tabs">
        <!--"data-paso=''" es un atributo personalizado, lo que haremos
        es mapear estos pasos, el "paso-1"(paso-1, que se encuentra en el id
        del primer div que tenemos alli abajo) con el 1, el 2 con el 2 y asi.
        y cuando yo presione aqui en el button, me muestra el 1, me muestra el 2,
        el 3, segun donde yo haya dado click.
    
        Importante que en html5 agregaron estos atributos, si agregamos
        "data-" (data-guion) y podemos crear nuestros propios atributos-->

        <button type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion"> 
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>

        <!--Este div va estar vacio pq con JS voy a consultar la BD
        en php, la voy a exportar a JSON y voy a insertar aqui los datos-->
        <div id="servicios" class="listado-servicios"></div>

    </div>

    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>

        <div class="mensaje naranja">Selecciona una fecha primero</div>

        <!--Este form no va tener un "action", ni tampoco ningun "method",
        vamos a estar gurdando todo en un objeto de javascript-->
        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                type="text"
                id="nombre"
                placeholder="Tu Nomber"
                value="<?php echo $nombre; ?>"
                disabled
                >
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <input 
                type="date"
                id="fecha"
                min="<?php echo date('Y-m-d'); ?>"
                >
                <!-- "min=""" para poner un limite de seleccionamiento para las fechas -->
                <!--asi estaba antes el min:
                min="<php echo date('Y-m-d', strtotime('+1 day')); ?>"-->
            </div>

            <?php
            /* Supongamos que el cliente que nos pide la web nos dice, "quiero que se puedan
            seleccionar los turnos desde el dia siguiente, no el mismo dia.
            Entonces usamos la funcion "strtotime()" 
            Y le pasamos "+1 day" entonces al dia, en el calendario, formulario, se le sumara
            un dia, y desde ahi podremos elegir los turnos*/
            // strtotime() -> convierte el string de tiempo a fecha

            // date(Y-M-D) = 2023-JUL-THURS
            // date(y-m-d) = 23-07-18
            // date(y-m-l) = l" nombre completo del dia
            // date(n) -> retorna el numero del dia
            
            // HORA
            // date(H:i)  ?>
            <input type="hidden" id="id" value="<?php echo $id; ?>">

            
        </form>
        <div class="listado-horarios"  id="horarios"></div>

        <div class="cont-horarios">
            <p>Horarios disponibles</p>
            <li>Abierto de Lunes a Sabado, desde:</li>
            <li>10:00 hasta 12:00hs</li>
            <li>15:00 hasta 22:00hs</li>
        </div>

    </div>
    
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">
        &laquo; Anterior
        </button>
        <button id="siguiente" class="boton">
        Siguiente &raquo;
        </button>
    </div>
</div>

<!--Creo una variable $script que sera igual al script que querre cargar en este
archivo, pero como lo tengo que cargar en el layout.php porque es el archivo 
principal, entonces lo que haremos es en el layout.php sera que si
existe la variable script, la imprimimos con "echo" y si no existe imprimimos
un string vacio.-->
<?php 
    $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src='build/js/app.js'></script>
    "
?>