<?php 

    /*2 foreach() uno accede al arreglo principal y el otro a los msj.
    
    No sanitizamos porque el arreglo $alertas yo lo estoy generando en el 
    modelo, sanitizamos lo que el usuario escribe solamente, pero lo que 
    generamos nosotros, lo que php genera no es necesario.*/

    foreach($alertas as $key => $mensajes):
        // debuguear($key);
        // debuguear($mensajes);
        foreach($mensajes as $mensaje):
?>
    <div class="alerta <?php echo $key; ?>">
        <?php echo $mensaje; ?>
    </div>
<?php          
        endforeach;

    endforeach;
?>