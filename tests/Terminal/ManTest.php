<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class ManTest extends BaseTest
{
    public function testLsShowsHint()
    {
        self::assertContains(' -a ', self::executeIndependentCommand('man ls'));
    }

    public function testCommandNotFound()
    {
        self::assertContains(
            'Does not exist',
            self::executeIndependentCommand('man does-not-exist'),
            '',
            true);
    }

    public function testNoManPage()
    {
        self::assertContains(
            'No manual entry',
            self::executeIndependentCommand('man help'),
            '',
            true);
    }

    public function testManMan()
    {
        self::assertContains(
            'Inception',
            self::executeIndependentCommand('man man'),
            '',
            true);
    }

    public function testManNerd()
    {
        $session = self::getTestSession();

        self::executeInSession('something', $session);
        self::executeInSession('man something', $session);
        self::executeInSession('something-else', $session);
        self::executeInSession('man something-else', $session);

        self::assertContains(
            'bookworm',
            self::executeInSessionRaw('man whatever', $session)->getAlert(),
            '',
            true);
    }

    public function testManNerdJustOnce()
    {
        $session = self::getTestSession();

        self::executeInSession('something', $session);
        self::executeInSession('man something', $session);
        self::executeInSession('something-else', $session);
        self::executeInSession('man something-else', $session);
        self::executeInSession('man whatever', $session);

        self::assertNotContains(
            'bookworm',
            self::executeInSessionRaw('man whatever', $session)->summary(),
            '',
            true);
    }

    public function testEmptyProgram()
    {
        self::assertContains('man <command>', self::executeIndependentCommand('man'));
    }
}
