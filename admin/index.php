<?php
// Archivo index.php del admin
session_start();

require 'config.php';
require '../functions.php';

$conexion = conexion($bd_config);

comprobarSesion();

if (! $conexion) {
    header('Location: ../error.php');
}

$posts = obtenerPost($blog_config['post_por_pagina'], $conexion);

require '../views/admin_index.view.php';

?>