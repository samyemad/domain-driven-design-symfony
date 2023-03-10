<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service;

use App\Notification\Domain\Service\Interfaces\TemplateRenderProviderInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

final class TemplateRenderProvider implements TemplateRenderProviderInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * render template Api and get the body response from this Api.
     */
    public function process(string $channel, string $code): ?string
    {
        $responseBody = null;
        $client = new \GuzzleHttp\Client([
            'base_uri' => $_ENV['APP_INTERNAL_DOMAIN'],
            'debug' => false,
            'defaults' => [
                'exceptions' => true,
            ],
            'headers' => $this->getHeaders(),
        ]);
        $credentials = $this->getCredentials($channel, $code);
        try {
            $response = $client->post($this->getUri(), [
                'body' => json_encode($credentials),
            ]);
            $responseBody = $response->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            $error['error'] = $exception->getMessage();
            $error['request'] = $exception->getRequest();
            if ($exception->hasResponse()) {
                $error['response'] = $exception->getResponse();
            }
            $this->logger->error('Error occurred in get request.', ['error' => $error]);
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage(), ['error' => $e]);
        }

        return $responseBody;
    }

    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'x-api-key' => $_ENV['API_KEY'],
        ];
    }

    private function getUri(): string
    {
        return '/templates/render';
    }

    private function getCredentials(string $channel, string $code): array
    {
        $data['slug'] = $channel;
        $data['variables']['code'] = $code;

        return $data;
    }
}
