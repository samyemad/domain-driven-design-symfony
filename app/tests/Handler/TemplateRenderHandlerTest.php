<?php

namespace App\Tests\Handler;


use App\Template\Application\Model\TemplateRenderCommand;
use App\Template\Application\Service\TemplateRenderHandler;
use App\Template\Domain\Service\Interfaces\TemplateResponseRenderInterface;
use App\Template\Infrastructure\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class TemplateRenderHandlerTest extends KernelTestCase
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
            'SMS confirmation 1' => ['command' => $this->createCommand('mobile-verification',['code' => 'efd8a9cfa'])],
        ];
    }
    /**
     * @dataProvider handlerDataProvider
     *
     * @param TemplateRenderCommand $command
     *
     */
    public function testResult(TemplateRenderCommand $command)
    {

        $templateRenderHandler=new TemplateRenderHandler(
            $this->container->get(TemplateRepository::class),
            $this->getTemplateResponseRenderMock()
        );
        $result=$templateRenderHandler($command);
        $this->assertEquals(200,$result->getStatusCode());
    }

    /**
     * @param string $slug
     * @param array $variables
     * @return TemplateRenderCommand
     */
    private function createCommand(string $slug, array $variables): TemplateRenderCommand
    {
        return new TemplateRenderCommand($slug,$variables);

    }
    private function getTemplateResponseRenderMock(): TemplateResponseRenderInterface
    {
        $mock = $this
            ->getMockBuilder(TemplateResponseRenderInterface::class)
            ->getMock();
        $mock
            ->method('process')
            ->willReturn(new Response('Your verification code is efd8a9cfa',Response::HTTP_OK));
        return $mock;
    }


    protected function tearDown(): void
    {
        parent::tearDown();
    }
}