<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class VimTest extends BaseTest
{
    public function testVimTriggerMaze()
    {
        self::assertContains(
            'maze',
            self::executeIndependentCommandRaw('vi whatever')->getTrigger());
    }

    public function testCompletedSuccessfully()
    {
        self::assertContains(
            'Congratulations',
            self::executeIndependentCommandRaw(': vim exit')->getAlert());
    }

    public function testGaveUp()
    {
        $session = self::getTestSession();

        self::executeInSession('vi whatever', $session);

        self::assertContains(
            'Next time try :q<enter>',
            self::executeInSessionRaw('intro', $session)->getAlert());
    }

    public function testImproveTime()
    {
        $session = self::getTestSession();

        self::executeInSession('vi something', $session);
        self::executeInSession(': vim exit', $session);
        self::executeInSession('vi something', $session);

        self::assertContains(
            'Improve',
            self::executeInSessionRaw(': vim exit', $session)->getAlert(),
            '',
            true);
    }
}
