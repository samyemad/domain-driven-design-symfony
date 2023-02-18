<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service;

use App\Notification\Domain\Service\Interfaces\EmailSenderProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailSenderProvider implements EmailSenderProviderInterface
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger = null)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * create new verification email and send it.
     */
    public function process(string $body, string $recipient): void
    {
        $email = (new Email())
            ->from($_ENV['ADMIN_MAILER'])
            ->to($recipient)
            ->subject('Email verification')
            ->html($body);
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}
