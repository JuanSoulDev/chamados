<?php
session_start();
include_once("../connection/conexao.php");
include_once("../function/functions.php");

$opcao = $_POST["s"];

switch ($opcao) {
	case 1:
		listarTitulos();
		break;
	case 2:
		listarChamados();
		break;
	case 3:
		listarQuantitativos();
		break;
	case 4:
		listarDeck();
		break;
	default:
		echo json_encode(falha("Serviço não disponível!"));
}

exit;

function listarTitulos() {
    global $conn;

    try {
        $select = $conn->prepare(<<<SQL
            SELECT
                id,
                title
            FROM
                oc_deck_labels odl
            WHERE
                odl.board_id = 12
            ORDER BY
                title
        SQL);
        $select->execute();
        $result = $select->get_result();
        $resultado = $result->fetch_all(MYSQLI_ASSOC);
        $select->close();

        if (empty($resultado)) {
            echo json_encode(falha("Nenhum título foi localizado")); 
            return;
        }

        echo json_encode(sucesso("Listagem realizada com sucesso", $resultado)); return;
    } catch (Exception $e) {
        echo json_encode(falha("Erro de execução SQL!", $e->getMessage(), true));
        return;
    }
}

function listarChamados() {
    global $conn;

    try {
        $ano = $_POST["ano"];
        $mes = $_POST["mes"];
        $titulo = $_POST["titulo"];

        $select = $conn->prepare(<<<SQL
            WITH
            labels_identificacao AS (
                SELECT
                    l.card_id,
                    MAX(l.label_id IN(715, 716)) = 1 AS e_implementacao,
                    MAX(l.label_id IN(714, 750, 765, 766)) = 1 AS e_suporte
                FROM
                    oc_deck_assigned_labels l
                WHERE
                    l.label_id IN(714, 715, 716, 750, 765, 766)
                GROUP BY
                    l.card_id
            )

            SELECT
                c.id,
                c.title AS titulo_card,
                TO_CHAR(FROM_UNIXTIME(c.created_at), 'DD/MM/YYYY') AS data_criacao_card,
                s.title AS aba,
                c.archived AS arquivado,
                s.title IN ('Revisão', 'Finalizado', 'Sincronização') AS finalizado,
                s.title IN ('Desenvolvimento', 'Chamados', 'Reajuste', 'Pause', 'Analise', 'Sprint Semanal', 'Implementações') AS aberto,
                s.title IN ('Desenvolvimento') AS desenvolvimento,
                (
                    (YEAR(FROM_UNIXTIME(c.created_at)) = sp.ano)
                    AND (
                        sp.mes = 0 
                        OR MONTH(FROM_UNIXTIME(c.created_at)) = sp.mes
                    )
                    AND (
                        sp.titulo = 0
                        OR EXISTS (
                            SELECT
                                1
                            FROM
                                oc_deck_assigned_labels l
                            WHERE
                                l.card_id = c.id
                                AND l.label_id = sp.titulo
                        )
                    )
                ) AS card_filtrado,
                COALESCE(li.e_implementacao, FALSE) AS e_implementacao,
                COALESCE(li.e_suporte, FALSE) AS e_suporte,
                YEAR(FROM_UNIXTIME(c.created_at)) AS ano, 
                MONTH(FROM_UNIXTIME(c.created_at)) AS mes,
                (
                    SELECT
                        GROUP_CONCAT(u.displayname SEPARATOR ', ')
                    FROM
                        oc_deck_assigned_users au
                    JOIN oc_users u ON u.uid = au.participant
                    WHERE
                        au.card_id = c.id
                ) AS participantes
            FROM
                oc_deck_boards b
            CROSS JOIN (
                SELECT
                    ? AS ano,
                    ? AS mes,
                    ? AS titulo
            ) sp
            JOIN oc_deck_stacks s 
                ON s.board_id = b.id
                AND (
                    s.deleted_at IS NULL
                    OR s.deleted_at = 0
                )
            JOIN oc_deck_cards c
                ON c.stack_id = s.id
                AND (
                    c.deleted_at IS NULL
                    OR c.deleted_at = 0
                )
            LEFT JOIN labels_identificacao li ON li.card_id = c.id
            WHERE
                b.id = 12
                AND (
                    b.deleted_at IS NULL
                    OR b.deleted_at = 0
                )
        SQL);
        $select->bind_param("iii", $ano, $mes, $titulo);
        $select->execute();
        $result = $select->get_result();
        $resultado = $result->fetch_all(MYSQLI_ASSOC);
        $select->close();

        if (empty($resultado)) {
            echo json_encode(falha("Nenhum chamado foi localizado")); 
            return;
        }

        echo json_encode(sucesso("Listagem realizada com sucesso", $resultado)); return;
    } catch (Exception $e) {
        echo json_encode(falha("Erro de execução SQL!", $e->getMessage(), true));
        return;
    }
}

function listarQuantitativos() {
    global $conn;

    try {
        $ano = $_POST["ano"];
        $mes = $_POST["mes"];
        $titulo = $_POST["titulo"];

        $select = $conn->prepare(<<<SQL
            SELECT
                uc.category AS categoria,
                u.uid AS id_usuario,
                u.displayname AS nome_usuario,
                cu.active AS usuario_ativo,
                COUNT(*) AS acumulados,
                COUNT(
                    CASE
                        WHEN 
                            s.title IN ('Revisão', 'Finalizado', 'Sincronização')
                            OR c.archived = 1
                        THEN c.id
                    END
                ) AS finalizados,
                COUNT(
                    CASE
                        WHEN 
                            s.title IN ('Desenvolvimento', 'Chamados', 'Reajuste', 'Pause', 'Analise', 'Sprint Semanal', 'Implementações')
                            AND c.archived = 0
                        THEN
                            c.id
                    END
                ) AS abertos,
                COUNT(
                    CASE
                        WHEN
                            (
                                (YEAR(FROM_UNIXTIME(c.created_at)) = sp.ano)
                                AND (
                                    sp.mes = 0 
                                    OR MONTH(FROM_UNIXTIME(c.created_at)) = sp.mes
                                )
                                AND (
                                    sp.titulo = 0
                                    OR EXISTS (
                                        SELECT
                                            1
                                        FROM
                                            oc_deck_assigned_labels l
                                        WHERE
                                            l.card_id = c.id
                                            AND l.label_id = sp.titulo
                                    )
                                )
                            )
                        THEN
                            c.id
                    END
                ) AS acumulados_filtrados,
                COUNT(
                    CASE
                        WHEN 
                            (
                                s.title IN ('Revisão', 'Finalizado', 'Sincronização')
                                OR c.archived = 1
                            )
                            AND (
                                (YEAR(FROM_UNIXTIME(c.created_at)) = sp.ano)
                                AND (
                                    sp.mes = 0 
                                    OR MONTH(FROM_UNIXTIME(c.created_at)) = sp.mes
                                )
                                AND (
                                    sp.titulo = 0
                                    OR EXISTS (
                                        SELECT
                                            1
                                        FROM
                                            oc_deck_assigned_labels l
                                        WHERE
                                            l.card_id = c.id
                                            AND l.label_id = sp.titulo
                                    )
                                )
                            )
                            
                        THEN c.id
                    END
                ) AS finalizados_filtrados,
                COUNT(
                    CASE
                        WHEN 
                            (
                                s.title IN ('Revisão', 'Finalizado', 'Sincronização')
                                OR c.archived = 1
                            )
                            AND DATE(FROM_UNIXTIME(c.created_at)) = CURDATE()
                        THEN c.id
                    END
                ) AS finalizados_do_dia,
                COUNT(
                    CASE
                        WHEN 
                            s.title IN ('Desenvolvimento', 'Chamados', 'Reajuste', 'Pause', 'Analise', 'Sprint Semanal', 'Implementações')
                            AND c.archived = 0
                            AND (
                                (YEAR(FROM_UNIXTIME(c.created_at)) = sp.ano)
                                AND (
                                    sp.mes = 0 
                                    OR MONTH(FROM_UNIXTIME(c.created_at)) = sp.mes
                                )
                                AND (
                                    sp.titulo = 0
                                    OR EXISTS (
                                        SELECT
                                            1
                                        FROM
                                            oc_deck_assigned_labels l
                                        WHERE
                                            l.card_id = c.id
                                            AND l.label_id = sp.titulo
                                    )
                                )
                            )
                        THEN
                            c.id
                    END
                ) AS abertos_filtrados,
                COUNT(
                    CASE
                        WHEN 
                            s.title = 'Desenvolvimento' 
                        THEN
                            c.id
                    END
                ) AS desenvolvimento
            FROM
                user_category uc
            JOIN oc_deck_category_user cu ON cu.id_category = uc.id
            JOIN oc_users u ON u.uid = cu.uid_user
            JOIN oc_deck_assigned_users au ON au.participant = u.uid
            JOIN oc_deck_cards c 
                ON c.id = au.card_id
                AND (
                    c.deleted_at IS NULL
                    OR c.deleted_at = 0
                )
            JOIN oc_deck_stacks s 
                ON s.id = c.stack_id
                AND (
                    s.deleted_at IS NULL
                    OR s.deleted_at = 0
                )
            JOIN oc_deck_boards b 
                ON b.id = s.board_id
                AND b.id = 12
                AND (
                    b.deleted_at IS NULL
                    OR b.deleted_at = 0
                )
            CROSS JOIN (
                SELECT
                    ? AS ano,
                    ? AS mes,
                    ? AS titulo
            ) sp
            WHERE
                uc.category IN('SUPORTE', 'DESENVOLVIMENTO')
            GROUP BY
                id_usuario
            ORDER BY
                categoria, acumulados DESC, finalizados ASC
        SQL);
        $select->bind_param("iii", $ano, $mes, $titulo);
        $select->execute();
        $result = $select->get_result();
        $resultado = $result->fetch_all(MYSQLI_ASSOC);
        $select->close();

        if (empty($resultado)) {
            echo json_encode(falha("Nenhum quantitativo foi localizado")); 
            return;
        }

        echo json_encode(sucesso("Listagem realizada com sucesso", $resultado)); return;
    } catch (Exception $e) {
        echo json_encode(falha("Erro de execução SQL!", $e->getMessage(), true));
        return;
    }
}

function listarDeck() {
    global $conn;

    try {
        $select = $conn->prepare(<<<SQL
            SELECT
                GROUP_CONCAT(ds_oc_deck_ee SEPARATOR '') AS dados
            FROM
                oc_deck_ee
        SQL);
        $select->execute();
        $result = $select->get_result();
        $resultado = $result->fetch_assoc()["dados"];
        $select->close();

        if (empty($resultado)) {
            echo json_encode(falha("Nenhum deck foi localizado")); 
            return;
        }

        echo json_encode(sucesso("Listagem realizada com sucesso", $resultado)); return;
    } catch (Exception $e) {
        echo json_encode(falha("Erro de execução SQL!", $e->getMessage(), true));
        return;
    }
}