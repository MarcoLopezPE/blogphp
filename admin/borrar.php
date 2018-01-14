<?php
session_start();

require 'config.php';
require '../functions.php';

comprobarSesion();

$conexion = conexion($bd_config);
if (! $conexion) {
    header('Location: ../error.php');
}

$id = limpiarDatos($_GET['id']);
if (! $id) {
    header('Location: ' . RUTA . '/admin');
}

$statement = $conexion->prepare('delete from articulos where id=:id');
$statement->execute(array(
    'id' => $id
));

header('Location: ' . RUTA . '/admin');

?>