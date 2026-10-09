<?php
include_once("functions.php");
require_once("controlador.php");
include_once("../connection/conexao.php");

$opcao = $_POST["s"];

switch ($opcao) {
	case 1:
		gerarRelatorio1();
        break;
	default:
		echo json_encode(falha("Serviço não disponível!"));
}

exit;

function gerarRelatorio1() {
    global $conn;

    try {
        $dados = [
            'data_inicial' =>  $_POST["data_inicial"], 
            'data_final'   =>  $_POST["data_final"], 
            'titulo'       =>  isset($_POST["titulo"]) ? $_POST["titulo"] : null
        ];

        if (empty($dados["data_inicial"]) || empty($dados["data_final"]) || is_null($dados["titulo"])) {
            echo json_encode(falha("Preencha todos os filtros corretamente.")); 
            return;
        }

        if(strtotime($dados["data_inicial"]) > strtotime($dados["data_final"])) {
            echo json_encode(falha("A data inicial é maior que a data final.")); 
            return;
        }

        $select = $conn->prepare(<<<SQL
            WITH
            sql_parametros AS (
                SELECT
                    UNIX_TIMESTAMP(?) AS data_inicial,
                    UNIX_TIMESTAMP(?) AS data_final,
                    CAST(? AS INT) AS titulo_id
            ),
            card_labels AS (
                SELECT
                    odal.card_id,
                    JSON_ARRAYAGG(dl.id) AS label_ids
                FROM 
                    oc_deck_assigned_labels odal
                JOIN oc_deck_labels dl 
                    ON dl.id = odal.label_id
                    AND dl.board_id = 12
                GROUP BY
                    odal.card_id
            )

            SELECT
                inf.id AS "ID",
                inf.title AS "ABA",
                inf.arquivado AS "ARQUIVADO?",
                inf.titulo_card AS "TÍTULO CARD",
            	inf.description AS "DESCRIÇÃO CARD",
                (
                    SELECT
                        GROUP_CONCAT(dl.title ORDER BY dl.id SEPARATOR ', ')
                    FROM 
                        oc_deck_assigned_labels odal
                    JOIN oc_deck_labels dl ON dl.id = odal.label_id
                    WHERE 
                        odal.card_id = inf.id
                ) AS "TAGS",
                DATE_FORMAT(FROM_UNIXTIME(inf.created_at - 3*3600), '%d/%m/%Y') AS "DATA CRIAÇÃO"
            FROM (
                SELECT
                    odc.id,
                    ods.title,
                    ods.title AS titulo_stack,
                    odc.created_at,
                    odc.duedate,
                    DATEDIFF(DATE_SUB(odc.duedate, INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 3 HOUR)) AS dias_faltantes,
                    ods.`order` AS ordem,
                    odc.title AS titulo_card,
                    (CASE WHEN odc.archived = 0 THEN 'NÃO' ELSE 'SIM' END) AS arquivado,
                    odc.description
                FROM
                    oc_deck_boards odb 
                CROSS JOIN sql_parametros sp
                JOIN oc_deck_stacks ods 
                    ON ods.board_id = odb.id
                    AND (
                        ods.deleted_at IS NULL
                        OR ods.deleted_at = 0
                    )
                LEFT JOIN oc_deck_cards odc 
                    ON odc.stack_id = ods.id
                    AND (
                        odc.deleted_at IS NULL
                        OR odc.deleted_at = 0
                    )
                    AND (
                        odc.created_at >= sp.data_inicial
                        AND odc.created_at <= sp.data_final
                    )
                LEFT JOIN card_labels cl ON cl.card_id = odc.id
                WHERE
                    odb.id = 12
                    AND (
                        odb.deleted_at IS NULL
                        OR odb.deleted_at = 0
                    )
                    AND (
                        sp.titulo_id = 0
                        OR JSON_CONTAINS(cl.label_ids, sp.titulo_id)
                    )
            ) inf
            ORDER BY
                id
        SQL);
        $select->bind_param("ssi", $dados["data_inicial"], $dados["data_final"], $dados["titulo"]);
        $select->execute();
        $result = $select->get_result();
        $resultado = $result->fetch_all(MYSQLI_ASSOC);
        $select->close();

        if (empty($resultado)) {
            echo json_encode(falha("Nenhuma informação foi localizada")); 
            return;
        }

        $retorno = sucesso("Listagem realizada com sucesso", $resultado);
        $retorno["type"] = 1;

        echo json_encode($retorno); return;
    } catch (Exception $e) {
        echo json_encode(falha("Erro de execução SQL!", $e->getMessage(), true));
        return;
    }
}