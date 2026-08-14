<?php
    $host = '93.127.212.177'; 
    $usuario = 'sicap';
    $senha = 'sicap1405';
    $banco = 'nextcloud';

    $conn = new mysqli($host, $usuario, $senha, $banco);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    };
?>
