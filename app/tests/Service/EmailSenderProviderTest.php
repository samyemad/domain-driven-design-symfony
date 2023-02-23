<?php

namespace App\Tests\Service;

use App\Notification\Infrastructure\Service\EmailSenderProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\MailerInterface;

class EmailSenderProviderTest extends KernelTestCase
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
    public function emailDataProvider(): array
    {
        return [
            'Email' => ['body' => '<h1>Email Confirmation</h1>','recipient' =>'samyemad4@gmail.com'],
        ];
    }
    /**
     * @dataProvider emailDataProvider
     *
     * @param string $body
     * @param string $recipient
     *
     */
    public function testResult(string $body,string $recipient)
    {
        $emailSender= new EmailSenderProvider($this->getMailerMock(), $this->getLoggerMock(),$this->container->getParameter('test.admin_mailer'));
        $emailSender->process($body,$recipient);
    }
    private function getMailerMock(): MailerInterface
    {
        $mock = $this
            ->getMockBuilder(MailerInterface::class)
            ->getMock();
        $mock->expects(self::once())->method('send');
        return $mock;
    }

    private function getLoggerMock(): LoggerInterface
    {
        return  $this
            ->getMockBuilder(LoggerInterface::class)
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}