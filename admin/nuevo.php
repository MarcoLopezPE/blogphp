<?php
session_start();

require 'config.php';
require '../functions.php';

comprobarSesion();

$conexion = conexion($bd_config);
if (! $conexion) {
    header('Location: ../error.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = limpiarDatos($_POST['titulo']);
    $extracto = limpiarDatos($_POST['extracto']);
    $texto = $_POST['texto'];
    $thumb = $_FILES['thumb']['tmp_name'];
    
    $archivo_subido = '../' . $blog_config['carpeta_imagenes'] . limpiarEspacios($_FILES['thumb']['name']);
    
    move_uploaded_file($thumb, $archivo_subido);
    
    $statement = $conexion->prepare('insert into articulos(id, titulo, extracto, texto, thumb) values (null, :titulo, :extracto, :texto, :thumb)');
    $statement->execute(array(
        ':titulo' => $titulo,
        ':extracto' => extracto,
        ':texto' => $texto,
        ':thumb' => limpiarEspacios($_FILES['thumb']['name'])
    ));
    
    header('Location: ' . RUTA . '/admin');
}

require '../views/nuevo.view.php';

?>