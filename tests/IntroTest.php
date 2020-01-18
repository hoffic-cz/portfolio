<?php


namespace App\Tests;


use App\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class IntroTest extends TestCase
{
    public function testContainsIntro(Terminal $terminal)
    {
        $output = $terminal->command('intro');

        self::assertContains(
            "I'm a software engineer based in london",
            $output->getStdout()
        );
    }

    public function testDoesNotContainOtherOutput(Terminal $terminal)
    {
        $output = $terminal->command('intro');

        self::assertFalse($output->hasSpecialOutput());
    }
}
