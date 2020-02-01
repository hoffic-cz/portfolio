<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class ExitTerminalTest extends BaseTest
{
    public function testNoCongratsByDefault()
    {
        self::assertNotContains('Well done! It took you', self::executeInSession('intro', 'blah'));
    }

    public function testCongratsAfterExitingAndReloading()
    {
        $userId = 'Clément';

        self::executeInSession('exit', $userId);

        self::assertContains('Well done! It took you', self::executeInSession('intro', $userId));
    }

    public function testIgnoreDifferentUsers()
    {
        self::executeInSession('exit', 'Clément');

        self::assertNotContains('Well done!', self::executeInSession('intro', 'Not Clément'));
    }

    public function testResetAfterShowing()
    {
        $userId = 'Clément';

        self::executeInSession('exit', $userId);
        self::executeInSession('intro', $userId);

        self::assertNotContains('Well done! It took you', self::executeInSession('intro', $userId));
    }

    public function testRepeatedInvocation()
    {
        $userId = 'Clément';

        self::executeInSession('exit', $userId);
        self::executeInSession('intro', $userId);
        self::executeInSession('exit', $userId);

        self::assertContains('Trying to improve your score?', self::executeInSession('intro', $userId));
    }
}
