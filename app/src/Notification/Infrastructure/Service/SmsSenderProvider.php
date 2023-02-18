<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service;

use App\Notification\Domain\Service\Interfaces\SmsSenderProviderInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

final class SmsSenderProvider implements SmsSenderProviderInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * render SMS Api and send notification message.
     */
    public function process(string $body): int
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $_ENV['APP_SMS_URL'],
            'debug' => false,
            'defaults' => [
                'exceptions' => false,
            ],
        ]);
        $credentials = $this->getCredentials($body);
        try {
            $response = $client->post($this->getUri(), [
                'form_params' => $credentials,
            ]);

            return $response->getStatusCode();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $error['error'] = $e->getMessage();
            $error['request'] = $e->getRequest();
            if ($e->hasResponse()) {
                $error['response'] = $e->getResponse();
            }
            $this->logger->error('Error occurred in get request.', ['error' => $error]);

            return $e->getResponse()->getStatusCode();
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage(), ['error' => $e]);

            return $e->getCode();
        }
    }

    private function getUri(): string
    {
        return '/message?token='.$_ENV['APP_SMS_TOKEN'];
    }

    private function getCredentials(string $body): array
    {
        $data['title'] = 'Mobile Verification';
        $data['message'] = $body;

        return $data;
    }
}
