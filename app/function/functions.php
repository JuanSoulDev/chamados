<?php
function sucesso($mensagem, $sucesso = null, $encod = false) {
	return array(
		'result' => true,
		'msg'	 => $mensagem,
		'p1'	 => $encod ? base64_encode($sucesso) : $sucesso,
		'encod'	 => $encod
	);
}

function falha($mensagem, $erros = ['erros' => null], $encod = false) {
	$erro = array(
		'result' => false,
		'msg' 	 => $mensagem,
		'p1'	 => $encod ? base64_encode($erros) : $erros,
		'encod'	 => $encod	
	);

	return $erro;
}