<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service;

use App\Notification\Domain\Service\Interfaces\SmsSenderProviderInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

final class SmsSenderProvider implements SmsSenderProviderInterface
{
    private LoggerInterface $logger;
    private string $smsToken;
    private string $smsUrl;

    public function __construct(LoggerInterface $logger, string $smsToken, string $smsUrl)
    {
        $this->logger = $logger;
        $this->smsToken = $smsToken;
        $this->smsUrl = $smsUrl;
    }

    /**
     * render SMS Api and send notification message.
     */
    public function process(string $body): int
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->smsUrl,
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
        return '/message?token='.$this->smsToken;
    }

    private function getCredentials(string $body): array
    {
        $data['title'] = 'Mobile Verification';
        $data['message'] = $body;

        return $data;
    }
}
