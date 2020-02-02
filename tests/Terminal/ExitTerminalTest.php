<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class ExitTerminalTest extends BaseTest
{
    public function testNoCongratsByDefault()
    {
        self::assertNotContains('Well done! It took you', self::executeIndependentCommand('intro'));
    }

    public function testCongratsAfterExitingAndReloading()
    {
        $session = self::getTestSession();

        self::executeInSession('exit', $session);

        self::assertContains(
            'Well done! It took you',
            self::executeInSessionRaw('intro', $session)->getAlert());
    }

    public function testIgnoreDifferentUsers()
    {
        $sessionA = self::getTestSession();
        $sessionB = self::getTestSession();

        self::executeInSession('exit', $sessionA);

        self::assertNotContains(
            'Well done!',
            self::executeInSessionRaw('intro', $sessionB)->summary());
    }

    public function testResetAfterShowing()
    {
        $session = self::getTestSession();

        self::executeInSession('exit', $session);
        self::executeInSession('intro', $session);

        self::assertNotContains(
            'Well done! It took you',
            self::executeInSessionRaw('intro', $session)->summary());
    }

    public function testRepeatedInvocation()
    {
        $session = self::getTestSession();

        self::executeInSession('exit', $session);
        self::executeInSession('intro', $session);
        self::executeInSession('exit', $session);

        self::assertContains(
            'Trying to improve your score?',
            self::executeInSessionRaw('intro', $session)->getAlert());
    }

    public function testNotInfiniteAfterRepeated()
    {
        $session = self::getTestSession();

        self::executeInSession('exit', $session);
        self::executeInSession('intro', $session);
        self::executeInSession('exit', $session);
        self::executeInSession('intro', $session);

        self::assertNotContains(
            'Trying to improve your score?',
            self::executeInSessionRaw('intro', $session)->summary());
    }
}
