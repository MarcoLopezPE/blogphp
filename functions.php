<?php

function logphp($cadena, $tipo)
{
    $arch = fopen(realpath('.') . "/logs/milog_" . date("Y-m-d") . ".txt", "a+");
    fwrite($arch, "[" . date("Y-m-d H:i:s") . " " . $_SERVER['REMOTE_ADDR'] . " " . $_SERVER['HTTP_X_FORWARDED_FOR'] . " - $tipo] " . $cadena . "\n");
    fclose($arch);
}

function conexion($bd_config)
{
    try {
        $conexion = new PDO('mysql:host=localhost;dbname=' . $bd_config['database'], $bd_config['username'], $bd_config['password']);
        return $conexion;
    } catch (PDOException $e) {
        logphp('File: /functions.php>conexion($bd_config)-> ' . $e, 'ERROR');
        return false;
    }
}

function limpiarDatos($datos)
{
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}

function paginaActual()
{
    return isset($_GET['p']) ? (int) $_GET['p'] : 1;
}

function obtenerPost($postPorPagina, $conexion)
{
    $inicio = (paginaActual() > 1) ? paginaActual() * $postPorPagina - $postPorPagina : 0;
    $sentencia = $conexion->prepare("select sql_calc_found_rows * from articulos limit $inicio, $postPorPagina");
    $sentencia->execute();
    return $sentencia->fetchAll();
}

function numeroPaginas($postPorPagina, $conexion)
{
    $totalPost = $conexion->prepare('select found_rows() as total');
    $totalPost->execute();
    $totalPost = $totalPost->fetch()['total'];
    $numeroPaginas = ceil($totalPost / $postPorPagina);
    return $numeroPaginas;
}

function idArticulo($id)
{
    return (int) limpiarDatos($id);
}

function obtenerPostPorId($conexion, $id)
{
    $resultado = $conexion->query("select * from articulos where id = $id limit 1");
    $resultado = $resultado->fetchAll();
    return ($resultado) ? $resultado : false;
}

function fecha($fecha)
{
    $timestamp = strtotime($fecha);
    $meses = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];
    $dia = date('d', $timestamp);
    $mes = date('m', $timestamp) - 1;
    $year = date('Y', $timestamp);
    $fecha = "$dia de " . $meses[$mes] . " del $year";
    return $fecha;
}

?>