<?php

namespace App\MessageHandler;

use App\Message\SendEmailNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendEmailNotificationHandler
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendEmailNotification $notification): void
    {
        $email = new TemplatedEmail()
            ->from($notification->getFrom())
            ->to($notification->getTo())
            ->subject($notification->getSubject())
            ->htmlTemplate("email/" . $notification->getTemplate(). ".html.twig")
            ->context($notification->getContext());
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {

        }
    }
}
