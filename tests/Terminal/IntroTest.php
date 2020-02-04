<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Terminal\Terminal;
use App\Tests\BaseTest;

class IntroTest extends BaseTest
{
    public function testContainsIntro()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command('intro');

        self::assertContains(
            "I'm a software engineer based in London",
            $output->getStdout()
        );
    }

    public function testDoesNotContainOtherOutput()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command('intro');

        self::assertFalse($output->hasSpecialOutput());
    }

    public function testContainsHints1()
    {
        $this->assertContains("'ls'", self::executeIndependentCommand('intro'));
    }

    public function testContainsHints2()
    {
        $this->assertContains("'help'", self::executeIndependentCommand('intro'));
    }
}
