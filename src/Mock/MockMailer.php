<?php
declare(strict_types=1);


namespace App\Mock;


use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class MockMailer implements MailerInterface
{
    /** @var RawMessage */
    private $message;

    /** @var Envelope */
    private $envelope;

    /**
     * @inheritDoc
     */
    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        $this->message = $message;
        $this->envelope = $envelope;
    }

    public function getSender(): string
    {
        return $this->envelope->getSender()->getAddress();
    }

    public function getRecipients(): array
    {
        $recipients = [];

        foreach ($this->envelope->getRecipients() as $address) {
            $recipients[] = $address->getAddress();
        }

        return $recipients;
    }

    public function getContents(): ?string
    {
        return $this->message->getTextBody();
    }
}
