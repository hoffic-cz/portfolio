<?php


namespace App\Tests;


use App\Terminal\Terminal;

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
}
