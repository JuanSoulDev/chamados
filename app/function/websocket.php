<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../connection/conexao.php';
require_once __DIR__ . '/functions.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use React\EventLoop\Loop;
use React\Socket\SocketServer;

class WebSocket implements MessageComponentInterface
{
    private array $clientes = [];
    private ?int $ultimoIdCard = null;
    private ?int $ultimoIdAssignedUsers = null;

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clientes[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Não precisa fazer nada.
    }

    public function onClose(ConnectionInterface $conn)
    {
        unset($this->clientes[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    public function verificarAlteracao()
    {
        global $conn;

        $result = $conn->query(<<<SQL
            SELECT
                MAX(c.id) AS ultimo_id_card,
                MAX(au.id) AS ultimo_id_assigned_users
            FROM
                oc_deck_stacks s
            JOIN oc_deck_cards c 
                ON c.stack_id = s.id
                AND c.deleted_at = 0
                AND c.archived = 0
            LEFT JOIN oc_deck_assigned_users au ON au.card_id = c.id
            WHERE
                s.board_id = 12
        SQL);

        if (!$result) {
            return;
        }

        $ids = $result->fetch_assoc();
        $ultimo_id_card = (int) $ids["ultimo_id_card"];
        $ultimo_id_assigned_users = (int) $ids["ultimo_id_assigned_users"];

        // Primeira consulta
        if ($this->ultimoIdCard === null && $this->ultimoIdAssignedUsers === null) {
            $this->ultimoIdCard = $ultimo_id_card;
            $this->ultimoIdAssignedUsers = $ultimo_id_assigned_users;
            return;
        }

        // Novo registro encontrado
        if ($this->ultimoIdCard !== $ultimo_id_card || $this->ultimoIdAssignedUsers !== $ultimo_id_assigned_users) {
            $this->ultimoIdCard = $ultimo_id_card;
            $this->ultimoIdAssignedUsers = $ultimo_id_assigned_users;

            foreach ($this->clientes AS $cliente) {
                $cliente->send(json_encode(sucesso("Modificação localizada")));
            }
        }
    }
}

$websocket = new WebSocket();

$loop = Loop::get();

$loop->addPeriodicTimer(2, function () use ($websocket) {
    $websocket->verificarAlteracao();
});

$socket = new SocketServer('0.0.0.0:8080', [], $loop);

$server = new IoServer(
    new HttpServer(
        new WsServer($websocket)
    ),
    $socket,
    $loop
);

echo "WebSocket iniciado em ws://localhost:8080\n";

$server->run();