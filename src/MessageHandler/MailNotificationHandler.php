<?php

namespace App\MessageHandler;

use App\Message\MailNotification;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class MailNotificationHandler implements MessageHandlerInterface
{
    private $mailer;
    private $sendTo;

    public function __construct($sendTo, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->sendTo = $sendTo;
    }

    public function __invoke(MailNotification $message)
    {
        $email = (new Email())
            ->from("ticketing@messenger.poc")
            ->to($this->sendTo)
            ->subject('New Incident #' . $message->getId() . ' - ' . $message->getTitle())
            ->html('<p>' . $message->getDescription() . '</p>');

        $this->mailer->send($email);
    }
}