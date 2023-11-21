<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen"></div>
        
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>


    <?php
        /*En algunos archivos no voy a tener el archivo js, solo lo 
        necesito en el archivo de cita/index.php que alli estarán las funcionalidades,
        porque si cargo el js para todos los archivos, presentará errores porque
        no en todas las paginas estaran los mismos elementos.
        
        Si la variable $script es nula o no está definida, se imprimirá una cadena
        vacía (''). En caso contrario, si la variable $script tiene un valor
        asignado, se imprimirá ese valor.*/
        
        echo $script ?? '';
    ?>
            
</body>
</html>