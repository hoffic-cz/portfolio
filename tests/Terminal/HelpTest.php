<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class HelpTest extends BaseTest
{

    public function testContainsExplanation()
    {
        self::assertContains('terminal', self::executeIndependentCommand('help'));
    }

    public function testContainsEncouragement()
    {
        self::assertContains('play', self::executeIndependentCommand('help'));
    }

    public function testContainsPurpose()
    {
        self::assertContains('fun', self::executeIndependentCommand('help'));
    }

    public function testContainsHint()
    {
        self::assertContains('secret', self::executeIndependentCommand('help'));
    }

    public function testSuggestions1()
    {
        self::assertContains('gitlab', self::executeIndependentCommand('help'));
    }

    public function testSuggestions2()
    {
        self::assertContains('email', self::executeIndependentCommand('help'));
    }
}
