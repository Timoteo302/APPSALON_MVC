
let paso = 1;

const pasoInicial = 1;
const pasoFinal = 3;


const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

//cuando todo el documento este cargado.
document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
})


function iniciarApp() {
    
    mostrarSeccion(); // Muestra y oculta las secciones (dependen de la variable paso que esta arriba)
    tabs(); // Cambia la seccion cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    pagSiguiente();
    pagAnterior();

    consultarAPI(); // Consulta la API en el backend de php

    idCliente();
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarBuscarFecha(); // Añade la fecha en el objeto de cita

    mostrarResumen(); // Muestra el resumen de la cita
}


function mostrarSeccion(){

    // Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');

    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar')
    }


    // Seleccionar la seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrar');


    /*La parte de quitar y agregar la clase de "actual" para los tabs la
    colocamos aca, porque esta funcion la mandamos a llamar todo el
    tiempo (cuando se da click en los tabs), la funcion tabs() solamente se
    manda a llamar una sola vez.
    En tabs() no funcionaria el codigo porque, se manda llamar una sola vez*/

    // Quita la clase de "actual" al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual')
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');

}


function tabs() {
    //los botones no tienen clases pero utilizamos la etiqueta de button, asi los seleccionamos:
    const botones = document.querySelectorAll('.tabs button');
    // console.log(botones);

    botones.forEach( (boton) =>{
        boton.addEventListener('click', (e)=>{
            //detectar a que le dimos click, con "event(e)"

            /*Con "dataset" podemos acceder a los atributos que nosotros hemos
            creado, ejemplo: "dataset.paso", "dataset.precio", porque
            en nuestro button le ponemos como atributo:  "data-paso='1'".
            Si ponemos "console.log(e.target.dataset)",
            nos mostrará esto: "DOMStringMap {paso: '2'}", pero si nosotros le agregamos el 
            ".paso" nos mostrara lo que hay dentro de ese atributo, accedemos al
            valor y nos mostrara "1", "2","3" */

            // console.log(parseInt(e.target.dataset.paso))
            paso = parseInt(e.target.dataset.paso);

            mostrarSeccion();


            /*Llamamos a botonesPaginador() aqui porque esta funcion "botonesPaginador()"
            la llamamos una sola vez, y solo funcionará una sola vez los botones
            paginadores y el paso siempre seria igual a 1, entonces aqui la funcion
            tabs(), cuando apretemos cada tab, la funcion botonesPaginador() se llamara
            constantemente y con la variable "paso" actualizado */
            botonesPaginador();

        })
    } )

}


function botonesPaginador(){

    /* Si a esta funcion solo la llamamos una sola vez en "inciarApp()" no funcionará.
    Porque esta funcion la llamamos una sola vez en "IniciarApp()" y no la estamos 
    mandando a llamar cuando estamos cambiando de pagina (mediante los tabs) y los tabs son
    los que cambian de pagina, entonces funciona la primera vez y luego ya no, para 
    solucionar esto lo que hariamos es, como dijimos que los tabs cambian de pagina, 
    tenemos que ir a la funcion de tabs() y llamar alli esta funcion.
    Entonces una vez que se ejecute el evento para cada boton en la funcion tabs()
    se vuelve a mandar a llamar y cambia los botones paginadores (porque se actualiza
    el "paso" tambien)).*/

    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar')
        paginaSiguiente.classList.remove('ocultar')
    } else if (paso === 3){
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.add('ocultar')

        mostrarResumen();
    } else{
        paginaAnterior.classList.remove('ocultar')
        paginaSiguiente.classList.remove('ocultar')
    }


    //2- llamamos mostrarSeccion()
    mostrarSeccion();
}


function pagAnterior() {
    const pagAnterior = document.querySelector('#anterior');
    pagAnterior.addEventListener('click', function(){

        /*Verficiamos si paso es menor o igual a pasoInicial. 
        Si es así, la ejecución se detiene y no se realiza ninguna 
        acción adicional. De lo contrario, si paso es mayor que 
        pasoInicial, se decrementa el valor de paso en 1.
        
        return -> detiene la ejecucion y sale del codigo*/
        if(paso <= pasoInicial) return;
        paso--;

        // console.log(paso);

        /*
        1- llamo a botonesPaginador() para mostrar los paginadores
        correspondientes segun en que pagina estemos.
        2- ahora como segundo paso dentro de botonesPaginador() al final, llamaremos
        a la funcion mostrarSeccion(), cuando estemos cambiado de paginador, se llamara
        a cada seccion correspondiente y se mostrará.  
        
        La variable paso que esta aqui, cuando llamemos a botonesPaginador(), esta variable
        paso ya tendra un valor asignado, segun en que paso estemos, tendra un paso
        que sera la seccion o tab en el que estemos.
        Luego en la funcion "botonesPaginador()" nosotros al final llamamos a la funcion
        "mostrarSeccion()", la llamamos para que muestre la seccion correspondiente y la clase de
        los tabs.
        Todo esto lo hacemos en base a la variable "paso" que tenemos acá, que esta 
        va cambiando segun en que pagina estemos y se va pasando como en cascada la variable, 
        primero a la funcion "botonesPaginador()" y luego de ahi se pasa a "mostrarSeccion()",
        estas 2 funciones utilizan la variable "paso" y esta variable se va pasando con el
        valor que sale de acá.*/
        botonesPaginador();
    })
}


function pagSiguiente() {
    const pagSiguiente = document.querySelector('#siguiente');
    pagSiguiente.addEventListener('click', function(){

        if(paso >= pasoFinal) return;
        paso++;

        botonesPaginador();
    })
}



async function consultarAPI() {

    try {
        const url = 'http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        // console.log(servicios)
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error)
    }
} 


function mostrarHorariosDisp(citasHora, fechaSel){
    const horariosDisponibles = ['09:00', '10:00', '11:00', '12:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];

    const horariosOcupados = citasHora.map(cita => {
        const hora = cita.hora
        const horaFormateada = hora.slice(0, -3); // Eliminar los ultimos tres caracteres ':00'
        return horaFormateada;
    })

    const horariosDisponiblesDiv = document.querySelector('#horarios');
    horariosDisponiblesDiv.innerHTML = '';
    // console.log(horariosOcupados)

    const horaActual = new Date().getHours();

    // Dia actual
    const diaActual = new Date().getDate();
    
    // Fecha seleccionada por el usuario
    partesFecha = fechaSel.split('-')
    // console.log(partesFecha[2]) //dia


    // Recorrer los horarios disponibles y mostrarlos
    horariosDisponibles.forEach(horario => {

        const hora2Digitos = horario.slice(0, -3);

        const horaDiv = document.createElement('div');
        horaDiv.textContent = horario;
        horaDiv.setAttribute('data-hora', horario); // Agregar el atributo de data-set con el valor del horario
        // console.log(horaDiv)


        // Verificar si el horario está ocupado y agregar una clase correspondiente
        if (horariosOcupados.includes(horario)) {
            horaDiv.classList.add('horario-ocupado', 'horario');

        }else {
            horaDiv.classList.add('horario');
            horaDiv.addEventListener('click', () => seleccionarHorario(horario));
        }
        
        // si la hora es menor a la hora actual, no estara disponible
        if (hora2Digitos <= horaActual){ 
            horaDiv.classList.add('horario-ocupado', 'horario');
        }

        // para que al seleccionar otro dia, remueva la clase ocupado
        if (diaActual != partesFecha[2]){
            horaDiv.classList.remove('horario-ocupado')
        }

        horariosDisponiblesDiv.appendChild(horaDiv);
    });

    

}

let horarioSeleccionado = '';
function seleccionarHorario(horario) {

    if (horarioSeleccionado) {
        const elementoAnterior = document.querySelector(`[data-hora="${horarioSeleccionado}"]`);
        elementoAnterior.classList.remove('seleccionado');
    }
    
    cita.hora = horario;
    horarioSeleccionado = horario;

    const elementoActual = document.querySelector(`[data-hora="${horario}"]`);
    elementoActual.classList.add('seleccionado');


    console.log(cita); // Para verificar que se actualiza correctamente
}

function mostrarServicios(servicios){
    servicios.forEach( servicio => {

        /*Para "seleccionarServicio()", si lo hicieramos de esta forma: 
        " servicioDiv.onclick = seleccionarServicio(servicio) "
        pensariamos que funcionaria, pero en realidad no, ¿por qué?.
        Cuando nosotros ponemos los "()" a la funcion es que estamos
        llamando a dicha funcion y se pasan cada servicio super rapido 
        en cada vuelta y por eso nos aparecen todos los servicios 
        en vez de uno o el que estamos seleccionando.
        Para solucionar este problema lo que hacemos es con un "callback" */

        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;  //agregamos un atributo personalizado "idServicio".
        /* <div class="servicio" data-id-servicio="1"></div> */

        // callback
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        }
        
        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)

        document.querySelector('#servicios').appendChild(servicioDiv);

        // console.log(servicioDiv);

    })
}

/*Cada vez que se presione un servicio, nosotros lo agregaremos al objeto
de "citas" en el campo "servicios" (q es un arreglo),
y si lo volvemos a presionar, se quitará del campo "servicios[]" */

function seleccionarServicio(servicio){
    // console.log(servicio);
    /*Este objeto lo iremos escribiendo en el arreglo de
    "servicios", dentro del objeto de cita{} */
    
    /*Extraemos el arreglo de "servicios", porque vamos a estar 
    escribiendo en el.*/
    const { id } = servicio;
    const { servicios } = cita;

    // console.log(servicios);
    
    // Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    
    /*"SERVICIO" es el que estamos seleccionando, cuando doy click
    en algun servicio.
    "SERVICIOS" Es el array que esta dentro del objeto cita. */

    // Comprobar si un servicio ya fue agregado o removido
    /* Compruebo con los "id" cada servicio de "servicios" (que esta dentro
    de cita el objeto) con el id del servicio seleccionado */
    if( servicios.some( agregado => agregado.id === id ) ) {
        // Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
        divServicio.classList.remove('seleccionado');
    } else{
        // Agregarlo
        // Tomo una copia de los servicios dentro con "...", y le agrego el nuevo servicio
        // Va a ir agregando cada servicio en el arreglo de servicios, cuando demos click en c/u
        if(cita.servicios.length < 3){
            cita.servicios = [...servicios, servicio ];
            divServicio.classList.add('seleccionado');
        }
    }
    // console.log(cita);
}


// ALMACENANDO EL ID DEL CLIENTE
function idCliente() {
    cita.id = document.querySelector('#id').value;
}


// ALMACENANDO EL NOMBRE DEL CLIENTE
function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

// ALMACENANDO LA HORA DE LA CITA
function seleccionarBuscarFecha() {
    const inputFecha = document.querySelector('#fecha')
    inputFecha.addEventListener('input', async function(e){
        // console.log( new Date(e.target.value).getUTCDay());
        e.preventDefault();
        const fechaSeleccionada = e.target.value;

        const dia = new Date(e.target.value).getUTCDay();

        if( [0].includes(dia) ) {
            e.target.value = ''
            mostrarAlerta('Domingos no permitidos', 'error', '.formulario');
            document.querySelector('#horarios').innerHTML = '';
            return
        } else{
            try {

                // consultamos a nuestra API mandando la fecha seleccionada por el usuario
                const url = `http://localhost:3000/api/citas?fecha=${fechaSeleccionada}`;
    
                const resultado = await fetch(url);
                const citasHora = await resultado.json(); //retorna todo lo relacionado a esa fecha
                // console.log(citasHora)
                mostrarHorariosDisp(citasHora, fechaSeleccionada)

                // Actualizar la variable 'cita.fecha'
                cita.fecha = e.target.value
    
            } catch (error) {
                console.log(error)
            }
        }
    })

    /*Los domingos no abre el Salón de belleza entonces, nosotros
    tenemos que evitar que se pueda seleccionar esos dias, lo que haremos
    es ayudarnos con el event.
    si nosotros ponemos new Date(); en la consola veremos que nos da 
    mucha informacion, dia, mes, dia en num, hora.
    pero si ponemos "new Date().getUTCDay();" lo que obtendremos sera un
    numero que nos dira el dia, ejemplo "4"
    0 = domingo
    1 = lunes
    2 = martes
    3 = miercoles
    4 = jueves
    5 = viernes
    6 = sabado
    Entonces con esto podemos ayudarnos  */
}


function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    /*Elimina la alerta previa, para que no haya muchas alertas*/
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta')
    alerta.classList.add(tipo)

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        // Eliminar alerta
        setTimeout( ()=>{
            alerta.remove();
        }, 4000)
    }
    // console.log(alerta);

    /* "elemento": 
    Seria el o la clase a donde lo pondriamos a la alerta (como el padre),
    ejemplo: ".formulario" es un formulario que tiene esa clase, pero la alerta
    se pondra al final del formlulario
    ejemplo 2: ".contenido-resumen"  es un elemento que es una clase
    que hemos creado en el div de la seccion 3.*/
}


function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el contenido cada vez que yo llame a "mostrarResumen"
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild)
    }

    // validar:
    /*Cita es un objeto pero lo podemos validar de la sig forma: (con object.values())
    "console.log(Object.values(cita))"  <-- es un metodo especificamente
    diseñado para objetos, con su ayuda podemos validar si estan vacios 
    los atributos*/
    
    // console.log(Object.values(cita))
    // console.log(cita.servicios.length)

    if(Object.values(cita).includes('') || cita.servicios.length === 0 ) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false)
        return;
    }

    /*  "Object.values()"  -->
    Lo que hace es que toma los valores de las propiedades
    de un objeto(o sea las keys) y los retorna como array.
    ejemplo:
    const objeto = { a: 1, b: 2, c: 3 };
    const valores = Object.values(objeto);
    console.log(valores); ---> [1, 2, 3]

    Toma como argumento el objeto del cual se desean obtener los valores
    y devuelve un nuevo array que contiene los valores de todas las propiedades
    del mismo objeto y en el mismo orden. */

    const {nombre, fecha, hora, servicios} = cita;

    // HEADING PARA SERVICIOS EN RESUMEN
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);


    // Iterar sobre los servicios porque son un arreglo
    precioDec = 0;
    precioTotal = 0;
    servicios.forEach(servicio => {
        const {id, precio, nombre} = servicio; 
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;
        precioDec = parseFloat(precio);
        precioTotal += precioDec;
        // console.log(precioTotal); 

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })


    // HEADING PARA CITA EN RESUMEN
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    // Formatear el div de resumen
    // Nombre, Fecha y Hora
    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() +1;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));
    /*Si nosotros mostramos esto a la consola ahora:
    console.log(fechaUTC);
    nos diria, ejemplo: nosotros en la fecha del formulario seleccionamos
    el dia 14 pero cuando nos vamos al resumen y nos muestra en consola el dia
    12, por que? Bueno porque cada vez que usamos el "new Date()" tiene un 
    dezfase de un dia, y como nosotros lo usamos 1 vez por eso aparece 13 en vez de 14
    Lo que podemos hacer es ponerle al dia un "+1". */

    // console.log(fechaUTC);

    const opciones = { 
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);

    /*getDate() --> dia del mes, ejemplo 19 (empieza del 0)
    getDay() --> dia de la semana, ejemplo 0 = domingo */

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;


    // Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    console.log(cita);

    const precioFinal = document.createElement('P');
    precioFinal.innerHTML = `<span>Precio Total:</span> $${precioTotal}`;
    
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(precioFinal);

    resumen.appendChild(botonReservar);
}


/* De js enviar datos por medio de Fetch para poder leerlos mediante
un controlador en PHP */
async function reservarCita() {

    // POSTMAN, herramienta para probar url, peticiones
    // NETWORK, en el google (fetch), nos mostrara las peticiones
    
    /*Se crea un solo objeto y enviarlo al servidor, no vamos a
    estar creando muchos formData(), luego la API se encarga de 
    manejar los datos.
    La forma de agregar datos: "variable.append()" */


    const {id, nombre, fecha, hora, servicios} = cita;
    // obtener los id de los servicios
    const idServicio = servicios.map( servicio => servicio.id )
    // console.log(idServicio); // 1,2,4,6

    /* En la Base de Datos, tenemos la tabla serviciosId, y necesitamos solo el id 
    de cada servicio, no el nombre,precio, porque eso ya lo tenemos en la
    BD, entonces lo que haremos será extraer cada id, de cada servicio
    seleccionado.
    Entonces en la varible creada "idServicio[]" estaran el id de cada uno
    de los servicios seleccionados por el cliente.
    Luego esta variable se la agregamos al formData() como
    "servicios" para que llegue al controlador.
    
    Tambien la tabla, necesita el usuario id, a ese lo tomamos desde
    cita.id y ya se lo pasamos como variable al formData()*/


    /*Este formData() tiene toda la informacion que vamos a mandar via POST,
    pero fetch() no sabe la existencia de este formData() entonces se mandara vacia 
    la informacion. Tenemos que decirle a fetch que aqui va encontrar los datos
    que queremos enviar al servidor para poderlos leer con el "$_POST" que  esta
    en el "APIController.php".
    La forma en que fetch sabra que existen estos datos, es que despues
    de este metodo post que tenemos aqui debajo en la variable "respuesta"
    le agregamos un "body: datos" datos, porque asi se llama esta variable
    que contiene todos los datos a enviar.
    Body, es el cuerpo de la peticion que vamos a enviar
    De esta manera fetch identifica este formData() detecta los datos
    que estamos teniendo, y los va enviar como parte de la peticion POST hacia la 
    url que pusimos mas abajo, y el $_POST lo tenemos en "APIController.php" */
    const datos = new FormData();
    datos.append('fecha', fecha); 
    datos.append('hora', hora); 
    datos.append('usuarioId', id);
    datos.append('servicios', idServicio); 

    /*Codigo para comprobar todo: */
    // console.log([...datos]);
    // return
    // datos.append('nombre','juan'); // nombre:juan

    try {
        // Peticion hacia la api
        const url = 'http://localhost:3000/api/citas'

        /* fetch() 2 parametros:
        1- la url
        2- puede ser un objeto de configuracion (puede ser opcional),
        pero cuando enviamos una peticion tipo "POST" es obligatorio
        entonces creamos un objeto y le decimos que sera metodo POST.

        Basicamente es: este fetch va utilizar un metodo POST hacia la url
        que le pasamos, y nuestra API va buscar si tiene una url (endpoint) registrada
        y tambien si esa url soporta "POST", entonces de esa forma se conectarán
        este script para mandar datos, con nuestro controlador de PHP.*/
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        })
        // console.log(respuesta); //para ver si la conexion es correcta, status: 200

        const resultado = await respuesta.json(); //para ver la respuesta.
        console.log(resultado.resultado);

        if(resultado.resultado){
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            }).then( ()=>{
                /*Recarga la pagina para que los usuarios no puedan reservar
                otra cita de nuevo. */
                setTimeout( ()=>{
                    window.location.reload();
                }, 2000) 
            } )
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita',
        })
    }

    // servidor PHP
    // php -S localhost:3000
    // php -S 127.0.0.1:3000   <-- ip del localhost
}
