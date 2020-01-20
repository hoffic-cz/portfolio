<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class ContactTest extends BaseTest
{
    public function testContainsEmail()
    {
        self::assertContains('petr@hoffic.dev', self::executeIndependentCommand('contact'));
    }

    public function testContainsHintInformalHiThroughTerminal()
    {
        $hint = <<<HINT
'say "<message>"'
HINT;

        self::assertContains($hint, self::executeIndependentCommand('contact'));
    }

    public function testContainsLinkedInLink()
    {
        self::assertContains('www.linkedin.com/in/hoffic-cz/', self::executeIndependentCommand('contact'));
    }
}
