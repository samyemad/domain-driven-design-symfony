<?php

namespace App\Tests\Handler;

use App\Notification\Infrastructure\Service\SmsSenderProvider;
use App\Verification\Application\Service\CreateVerificationHandler;
use App\Verification\Domain\Service\Interfaces\CodeGeneratorProviderInterface;
use App\Verification\Domain\Service\Interfaces\UserProviderInterface;
use App\Verification\Infrastructure\Service\CreateVerification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Verification\Application\Model\CreateVerificationCommand;
use App\Verification\Domain\Entity\UserInfo;

class CreateVerificationHandlerTest extends KernelTestCase
{

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

        $createVerificationHandler=new CreateVerificationHandler(
            $this->container->get(CreateVerification::class),
            $this->getUserProviderMock(),
            $this->getCodeGeneratorProviderMock()
        );
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
        return new CreateVerificationCommand($identity,$type);

    }

    private function getUserProviderMock(): UserProviderInterface
    {
        $mock = $this
            ->getMockBuilder(UserProviderInterface::class)
            ->getMock();
        $mock
            ->method('process')
            ->willReturn(new Userinfo('192.168.1.1','chrome'));
        return $mock;
    }

    private function getCodeGeneratorProviderMock(): CodeGeneratorProviderInterface
    {
        $mock = $this
            ->getMockBuilder(CodeGeneratorProviderInterface::class)
            ->getMock();
        $mock
            ->method('process')
            ->willReturn('723eae02f');
        return $mock;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}