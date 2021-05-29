<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Terminal\Terminal;
use App\Tests\BaseTest;
use App\Mock\MockMailer;

class SayTest extends BaseTest
{
    public function testSaySendsEmails()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);
        /** @var MockMailer $mailer */
        $mailer = self::getService(MockMailer::class);

        $terminal->command('say Salut Clément!');

        self::assertContains('Salut Clément!', $mailer->getContents());
    }

    public function testThankForMessage()
    {
        self::assertContains(
            'Thank you',
            self::executeIndependentCommand('say Salut Clément!'));
    }

    public function testConfirmReceived()
    {
        self::assertContains(
            'Received',
            self::executeIndependentCommand('say Salut Clément!'),
            '',
            true);
    }

    public function testDisplayUsageOnEmptyArgs()
    {
        self::assertContains(
            'remember',
            self::executeIndependentCommand('say'),
            '',
            true);
    }
}
