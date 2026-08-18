<?php

session_start();

$limite = 10;
$periodo = 10; // segundos

$ip = $_SERVER['REMOTE_ADDR'];
$arquivo = sys_get_temp_dir() . "/rate_limit_" . md5($ip);

$agora = time();

$dados = [];

if (file_exists($arquivo)) {
    $dados = json_decode(file_get_contents($arquivo), true) ?? [];
}

// Remove requisições antigas
$dados = array_filter($dados, function ($tempo) use ($agora, $periodo) {
    return $tempo > ($agora - $periodo);
});

// Adiciona requisição atual
$dados[] = $agora;

// Salva
file_put_contents($arquivo, json_encode($dados));

if (count($dados) > $limite) {
    http_response_code(429);

    echo json_encode(falha("Muitas requisições. Aguarde alguns segundos."));

    exit;
}