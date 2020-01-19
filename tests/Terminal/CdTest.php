<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class CdTest extends BaseTest
{
    private const EXPECTED_EASTER_EGG_OUTPUT = <<<EEEO
I can't let you in, but
EEEO;


    public function testNotPermitted()
    {
        self::assertContains('Permission denied', self::executeIndependentCommand('cd'));
    }

    public function testSecretsDirEasterEgg1()
    {
        self::assertContains(self::EXPECTED_EASTER_EGG_OUTPUT, self::executeIndependentCommand('cd secrets/'));
    }

    public function testSecretsDirEasterEgg2()
    {
        self::assertContains(self::EXPECTED_EASTER_EGG_OUTPUT, self::executeIndependentCommand('cd secrets'));
    }
}
