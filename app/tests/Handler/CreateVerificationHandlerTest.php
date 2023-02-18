<?php

namespace App\Tests\Handler;

use App\Notification\Infrastructure\Service\SmsSenderProvider;
use App\Verification\Application\Service\CreateVerificationHandler;
use App\Verification\Infrastructure\Service\CreateVerification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Verification\Application\Model\CreateVerificationCommand;

class CreateVerificationHandlerTest extends KernelTestCase
{
    /**
     * @var $container
     */
    private $container;
    protected function setUp(): void
    {
        self::bootKernel();
        $this->container= static::$kernel->getContainer();
    }

    public function handlerDataProvider(): array
    {
        return [
            'SMS confirmation 1' => ['command' => $this->createCommand('01000025061887','mobile_confirmation')],
            'SMS confirmation 2' => ['command' => $this->createCommand('010049834444','mobile_confirmation')],
            'Email confirmation 1' => ['command' => $this->createCommand('samyemad4@gmail.com','email_confirmation')],
            'Email confirmation 2' => ['command' => $this->createCommand('samyemad45@gmail.com','email_confirmation')],
        ];
    }
    /**
     * @dataProvider handlerDataProvider
     *
     * @param CreateVerificationCommand $command
     *
     */
    public function testResult(CreateVerificationCommand $command)
    {

        $createVerificationHandler=new CreateVerificationHandler($this->container->get(CreateVerification::class));
        $result=$createVerificationHandler($command);
        $this->assertIsArray($result);
        $this->assertEquals(200,$result['code']);

    }

    /**
     * @param string $identity
     * @param string $type
     * @return CreateVerificationCommand
     */
    private function createCommand(string $identity, string $type): CreateVerificationCommand
    {
        $createVerificationCommand = new CreateVerificationCommand();
        $createVerificationCommand->setIdentity($identity);
        $createVerificationCommand->setIdentityType($type);
        return $createVerificationCommand;

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}