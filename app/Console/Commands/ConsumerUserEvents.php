<?php

namespace App\Console\Commands;

use App\DTOs\UserDTO;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumerUserEvents extends Command
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:user-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume user events from auth service via user_events topic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.connections.user_sync.host'),
            config('rabbitmq.connections.user_sync.port'),
            config('rabbitmq.connections.user_sync.user'),
            config('rabbitmq.connections.user_sync.password'),
            config('rabbitmq.connections.user_sync.vhost')
        );

        $channel = $connection->channel();

        $channel->exchange_declare('user_events', 'topic', false, true, false);
        $channel->queue_declare('user_management_queue', false, true, false, false);
        $channel->queue_bind('user_management_queue', 'user_events', 'user.*');

        $callback = function (AMQPMessage $msg) {
            try {
                $data = json_decode($msg->getBody(), true);
                Log::debug('Processing message', $data);

                $userDTO = UserDTO::fromQueueMessage($data['payload']);

                DB::transaction(function () use ($userDTO) {
                    $this->userRepository->updateOrCreateFromDto($userDTO);
                });

                Log::info('Message processed successfully');
                $msg->ack();
            } catch (\Exception $e) {
                Log::error("Failed processing message: {$e->getMessage()}");
                $msg->nack(true);
            }
        };

        $channel->basic_consume(
            'user_management_queue',
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
