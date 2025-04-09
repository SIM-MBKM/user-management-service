<?php

require __DIR__ . '/vendor/autoload.php';

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    '172.17.0.3',
    5672,
    'admin',
    'admin',
    'user_sync'
);

$channel = $connection->channel();

$channel->basic_consume(
    'user_management_queue',
    '',
    false,
    false,
    false,
    false,
    function ($msg) {
        echo " [x] Received ", $msg->body, "\n";
        $msg->ack();
    }
);

echo " [*] Waiting for messages\n";

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
