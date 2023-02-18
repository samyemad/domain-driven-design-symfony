<?php

namespace App\Tests\Service;

use App\Notification\Infrastructure\Service\TemplateRenderProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Notification\Domain\Service\Interfaces\TemplateRenderProviderInterface;

class TemplateRenderServiceTest extends KernelTestCase
{

   private $container;
   
    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
    }

    public function searchDataProvider(): array
    {
        return [
            'Mobile Verification' => ['channel' => 'mobile-verification', 'code' => 1234],
            'Email Verification' => ['channel' => 'email-verification', 'code' => 123456],
        ];
    }
    /**
     * @dataProvider searchDataProvider
     *
     * @param string $channel
     * @param int    $code
     *
     */
    public function testResult(string $channel,int $code)
    {
        $templateRender = $this->container->get(TemplateRenderProvider::class);
        $result=$templateRender->process($channel,$code);
        $this->assertIsString($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}