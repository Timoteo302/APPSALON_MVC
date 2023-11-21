
document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    buscarPorFecha();
} 

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');

    fechaInput.addEventListener('input', function (e){
        const fechaSeleccionada = e.target.value;

        // Mandamos fecha por GET, para recibir en el Controllador en PHP(AdminController.php)
        window.location = `?fecha=${fechaSeleccionada}`
        /*Una vez que el usuario seleccione una fecha vamos a
        redireccionarlo, vamos a enviar por metodo GET la 
        url y en el controlador lo vamos a validar.*/
    });

}