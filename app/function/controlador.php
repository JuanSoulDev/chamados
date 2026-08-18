<?php
$limite = 20;
$periodo = 10; // segundos
$agora = time();
$dados = [];

$ip = $_SERVER['REMOTE_ADDR'];
$arquivo = sys_get_temp_dir() . "/request_" . md5($ip);

if (file_exists($arquivo)) {
    $dados = json_decode(file_get_contents($arquivo), true) ?? [];
}

$dados = array_filter($dados, function ($tempo) use ($agora, $periodo) {
    return $tempo > ($agora - $periodo);
});

$dados[] = $agora;

file_put_contents($arquivo, json_encode($dados));

if (count($dados) > $limite) {
    http_response_code(429);

    echo json_encode(falha("Muitas requisições. Aguarde alguns segundos."));

    exit;
}