<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SayCommand implements Command
{
    /** @var MailerInterface */
    private $mailer;

    /** @var string */
    private $emailsFrom;

    /** @var string */
    private $emailsTo;

    /**
     * SayCommand constructor.
     * @param string $emailsFrom
     * @param string $emailsTo
     * @param MailerInterface $mailer
     */
    public function __construct(string $emailsFrom, string $emailsTo, MailerInterface $mailer)
    {
        $this->emailsFrom = $emailsFrom;
        $this->emailsTo = $emailsTo;
        $this->mailer = $mailer;
    }

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $email = new Email();
        $email->from($this->emailsFrom);
        $email->to($this->emailsTo);

        $email->subject('Message from Hoffic.dev');
        $email->text(implode(' ', $params));

        $this->mailer->send($email);

        return new CommandOutput('Thank you for your message, it has been received :)');
    }
}
