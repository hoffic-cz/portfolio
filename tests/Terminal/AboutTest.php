<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class AboutTest extends BaseTest
{
    public function testContainsName()
    {
        self::assertContains("I'm Petr", self::executeIndependentCommand('about'));
    }

    public function testTechnicalComplexity()
    {
        self::assertContains(
            "Technical complexity",
            self::executeIndependentCommand('about'),
            '',
            true);
    }
}
