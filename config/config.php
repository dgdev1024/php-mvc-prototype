<?php
    define('CONFIG', array(
        'uri' => $_GET['uri'],
        'uri-parts' => explode('/', $_GET['uri']),
        'domain' => 'http://localhost:8080/projects/mvc',
        'method' => $_SERVER['REQUEST_METHOD'],
        'database' => array(
            'host' => '127.0.0.1:8081',
            'user' => 'root',
            'pass' => '',
            'charset' => 'UTF8'
        )
    ));

    require_once 'config/autoload.php';
    require_once 'config/database.php';
?>