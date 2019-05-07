<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/7
 * Time: 3:39 PM
 */

namespace Jyil\AliwareMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AliyunCredentialsProvider
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * send
     *
     * @param string $queue
     * @param string $message
     */
    public function send($queue = 'queue', $message)
    {
        $connectionUtil = $this->getConnectionUtil();

        $connection = $connectionUtil->getConnection();

        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage($message, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));



        $channel->basic_publish($msg, '', $queue);

        echo " [x] Sent 'Hello World!'\n";

        $channel->close();

        $connection->close();
    }

    /**
     * receive
     */
    public function receive($queue)
    {
        $connectionUtil = $this->getConnectionUtil();

        $connection = $connectionUtil->getConnection();

        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        // In order to defeat that we can use the basic_qos method with the prefetch_count = 1 setting.
        // This tells RabbitMQ not to give more than one message to a worker at a time.
        // Or, in other words,
        // don't dispatch a new message to a worker until it has processed and acknowledged the previous one.
        // Instead, it will dispatch it to the next worker that is not still busy.
        $channel->basic_qos(null, 1, null);

        $channel->basic_consume($queue, '', false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();

        $connection->close();
    }

    private function getConnectionUtil()
    {
        return new ConnectionUtil(
            $this->config['host'],
            $this->config['port'],
            $this->config['virtualHost'],
            $this->config['accessKey'],
            $this->config['accessSecret'],
            $this->config['resourceOwnerId']
        );
    }
}