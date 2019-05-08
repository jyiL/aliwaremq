<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/7
 * Time: 3:28 PM
 */

namespace Jyil\AliwareMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

Class ConnectionUtil
{
    private $host;

    private $port;

    private $virtualHost;

    private $accessKey;

    private $accessSecret;

    private $resourceOwnerId;

    public function __construct($host, $port, $virtualHost, $accessKey, $accessSecret, $resourceOwnerId)
    {
        $this->host = $host;
        $this->port = $port;
        $this->virtualHost = $virtualHost;
        $this->accessKey = $accessKey;
        $this->accessSecret = $accessSecret;
        $this->resourceOwnerId = $resourceOwnerId;
    }

    /**
     * getUser
     *
     * @return string
     */
    private function getUser()
    {
        $t = '0:' . $this->resourceOwnerId . ':' . $this->accessKey;
        return base64_encode($t);
    }

    /**
     * getPassword
     *
     * @return string
     */
    private function getPassword()
    {
        $ts = (int)(microtime(true)*1000);
        $value = utf8_encode($this->accessSecret);
        $key = utf8_encode((string)$ts);
        $sig = strtoupper(hash_hmac('sha1', $value, $key, FALSE));
        return base64_encode(utf8_encode($sig . ':' . $ts));
    }

    /**
     * getConnection
     *
     * @return object
     */
    public function getConnection()
    {
        $username = $this->getUser();
        $password = $this->getPassword();

        return new AMQPStreamConnection(
            $this->host,
            $this->port,
            $username, $password,
            $this->virtualHost, false
        );
    }

    /**
     * @param \PhpAmqpLib\Connection\AbstractConnection $connection
     *
     * @throws
     */
    public static function shutdown($connection)
    {
        $connection->close();
    }

    /**
     * param \PhpAmqpLib\Connection\AbstractConnection $connection
     *
     * @throws
     */
    public static function cleanup_connection($connection)
    {
        // Connection might already be closed.
        // Ignoring exceptions.
        try {
            if($connection !== null) {
                $connection->close();
            }
        } catch (\ErrorException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}