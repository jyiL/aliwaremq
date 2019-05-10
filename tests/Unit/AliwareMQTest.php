<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/10
 * Time: 11:04 AM
 */

namespace Jyil\AliwareMQ\Tests\Unit;

use Jyil\AliwareMQ\Tests\TestCase;
use Jyil\AliwareMQ\AliyunCredentialsProvider;

class AliwareMQTest extends TestCase
{
    public function testSend()
    {
        $aliYunProvider = $this->createMock(AliyunCredentialsProvider::class);

        $aliYunProvider->method('send')->willReturn(true);

        $this->assertNotFalse(true, $aliYunProvider->send('queue', 'Hello World!!'));
    }
}