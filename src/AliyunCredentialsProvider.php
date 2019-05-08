<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/7
 * Time: 3:39 PM
 */

namespace Jyil\AliwareMQ;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPRuntimeException;

class AliyunCredentialsProvider
{
    const WAIT_BEFORE_RECONNECT_uS = 1000000;

    private $config;

    public $passive = false;

    // 持久化
    public $durable = true;

    // 独占模式
    public $exclusive = false;

    // 消费者断开连接时是否删除队列
    public $autoDelete = false;

    public $noLocal = false;

    public $noAck = false;

    public $nowait = false;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * send
     *
     * @param string $queue
     * @param string $message
     * @param string $exchange
     */
    public function send($queue, $message, $exchange = '')
    {
        $connectionUtil = $this->getConnectionUtil();

        $connection = $connectionUtil->getConnection();

        $channel = $connection->channel();

        $channel->set_ack_handler(
            function (AMQPMessage $message) {
                echo "Message acked with content " . $message->body . PHP_EOL;
            }
        );
        $channel->set_nack_handler(
            function (AMQPMessage $message) {
                echo "Message nacked with content " . $message->body . PHP_EOL;
            }
        );

        /*
         * bring the channel into publish confirm mode.
         * if you would call $ch->tx_select() before or after you brought the channel into this mode
         * the next call to $ch->wait() would result in an exception as the publish confirm mode and transactions
         * are mutually exclusive
         */
        $channel->confirm_select();

        $channel->queue_declare($queue, $this->passive, $this->durable, $this->exclusive, $this->autoDelete);

        $msg = new AMQPMessage($message, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        $channel->basic_publish($msg, $exchange, $queue);

        /*
         * watching the amqp debug output you can see that the server will ack the message with delivery tag 1 and the
         * multiple flag probably set to false
         */
        $channel->wait_for_pending_acks();

//        echo " [x] Sent 'Hello World!'\n";

        $channel->close();

        $connection->close();
    }

    /**
     * receive
     *
     * @param string $queue
     * @param string $consumerTag
     */
    public function receive($queue, $consumerTag = '')
    {
        $connectionUtil = $this->getConnectionUtil();

        $connection = NULL;

        while(true){
            try {
                $connection = $connectionUtil->getConnection();
                register_shutdown_function('shutdown', $connection);

                $channel = $connection->channel();

                $channel->queue_declare($queue, $this->passive, $this->durable, $this->exclusive, $this->autoDelete);

                echo " [*] Waiting for messages. To exit press CTRL+C\n";

                $callback = function ($msg) {
                    echo ' [x] Received ', $msg->body, "\n";

                    // 手动确认消息    参数1：该消息的index
                    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                };

                // In order to defeat that we can use the basic_qos method with the prefetch_count = 1 setting.
                // This tells RabbitMQ not to give more than one message to a worker at a time.
                // Or, in other words,
                // don't dispatch a new message to a worker until it has processed and acknowledged the previous one.
                // Instead, it will dispatch it to the next worker that is not still busy.
                $channel->basic_qos(null, 1, null);

                $channel->basic_consume($queue, $consumerTag, $this->noLocal, $this->noAck, $this->exclusive, $this->nowait, $callback);

                while (count($channel->callbacks)) {
                    $channel->wait();
                }

                $channel->close();

                $connection->close();
            } catch(AMQPRuntimeException $e) {
                echo $e->getMessage() . PHP_EOL;
                ConnectionUtil::cleanup_connection($connection);
                usleep(self::WAIT_BEFORE_RECONNECT_uS);
            } catch(\RuntimeException $e) {
                echo "Runtime exception " . $e->getMessage(). PHP_EOL;
                ConnectionUtil::cleanup_connection($connection);
                usleep(self::WAIT_BEFORE_RECONNECT_uS);
            } catch(\ErrorException $e) {
                echo "Error exception " . $e->getMessage() . PHP_EOL;
                ConnectionUtil::cleanup_connection($connection);
                usleep(self::WAIT_BEFORE_RECONNECT_uS);
            }
        }
    }

    /**
     * getConnectionUtil
     *
     * @return object
     */
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