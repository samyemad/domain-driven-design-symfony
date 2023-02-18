<?php

namespace App\Tests\Service;

use App\Notification\Infrastructure\Service\SmsSenderProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Verification\Infrastructure\Service\CodeGeneratorProvider;

class SmsRenderServiceTest extends KernelTestCase
{
    /**
     * @var $client
     */
    private $container;


    protected function setUp(): void
    {
        self::bootKernel();
        $this->container= static::$kernel->getContainer();
    }

    public function searchDataProvider(): array
    {
        return [
            'SMS verification 1' => ['body' => 'Your verification code is 123456789'],
            'SMS verification 2' => ['body' => 'Your verification code is 987654321'],
        ];
    }
    /**
     * @dataProvider searchDataProvider
     *
     * @param string $body
     *
     */
    public function testResult(string $body)
    {;

        $smsRender = $this->container->get(SmsSenderProvider::class);

        $result=$smsRender->process($body);

        $this->assertEquals(200,$result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}