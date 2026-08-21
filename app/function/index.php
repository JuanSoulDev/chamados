<?php
include_once("functions.php");
require_once("controlador.php");
include_once("../connection/conexao.php");

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
                    MAX(l.label_id = 714) = 1 AS _bug,
                    MAX(l.label_id = 765) = 1 AS _configuracao,
                    MAX(l.label_id = 715) = 1 AS _customizar,
                    MAX(l.label_id = 750) = 1 AS _inconsistente,
                    MAX(l.label_id = 716) = 1 AS _novo,
                    MAX(l.label_id = 766) = 1 AS _unificacao
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
                COALESCE(li._bug, FALSE) AS _bug,
                COALESCE(li._configuracao, FALSE) AS _configuracao,
                COALESCE(li._customizar, FALSE) AS _customizar,
                COALESCE(li._inconsistente, FALSE) AS _inconsistente,
                COALESCE(li._novo, FALSE) AS _novo,
                COALESCE(li._unificacao, FALSE) AS _unificacao,
                YEAR(FROM_UNIXTIME(c.created_at)) AS ano, 
                MONTH(FROM_UNIXTIME(c.created_at)) AS mes,
                p.participantes,
                p.programadores,
                (
                    DATE(FROM_UNIXTIME(c.created_at)) BETWEEN
                    DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND
                    DATE_ADD(
                        DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY),
                        INTERVAL 6 DAY
                    )
                ) AS aberto_semana_atual
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
            LEFT JOIN (
                SELECT
                    au.card_id,
                    GROUP_CONCAT(u.displayname SEPARATOR ', ') AS participantes,
                    GROUP_CONCAT((CASE WHEN cu.id_category = 2 THEN u.displayname END) SEPARATOR ', ') AS programadores
                FROM
                    oc_deck_assigned_users au
                JOIN oc_users u ON u.uid = au.participant
                JOIN oc_deck_category_user cu ON cu.uid_user = u.uid
                GROUP BY
                    au.card_id       
            ) p ON p.card_id = c.id 
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
                TO_BASE64((
                    SELECT
                        GROUP_CONCAT(ds_oc_deck_ee SEPARATOR 'A*piB+d') AS dados
                    FROM
                        oc_deck_ee
                )) AS idx,
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
                        WHEN (
                            s.title IN ('Desenvolvimento', 'Chamados', 'Reajuste', 'Pause', 'Analise', 'Sprint Semanal', 'Implementações')
                            AND c.archived = 0
                        )
                        AND DATE(FROM_UNIXTIME(c.created_at))
                            BETWEEN
                                DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                AND
                                DATE_ADD(
                                    DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY),
                                    INTERVAL 6 DAY
                                )
                        THEN c.id
                    END
                ) AS abertos_da_semana,
                COUNT(
                    CASE
                        WHEN (
                            s.title IN ('Revisão', 'Finalizado', 'Sincronização')
                            OR c.archived = 1
                        )
                        AND DATE(FROM_UNIXTIME(c.created_at))
                            BETWEEN
                                DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                                AND
                                DATE_ADD(
                                    DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY),
                                    INTERVAL 6 DAY
                                )
                        THEN c.id
                    END
                ) AS finalizados_da_semana,
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
                            AND c.archived = 0
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
                categoria, acumulados DESC, finalizados ASC, idx
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