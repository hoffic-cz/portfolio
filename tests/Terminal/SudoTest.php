<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class SudoTest extends BaseTest
{
    public function testSameOutputAsWithout1()
    {
        $withSudo = self::executeIndependentCommand('sudo cd ls');
        $withoutSudo = self::executeIndependentCommand('cd ls');

        self::assertSame($withoutSudo, $withSudo);
    }

    public function testSameOutputAsWithout2()
    {
        $withSudo = self::executeIndependentCommand('sudo cat ls');
        $withoutSudo = self::executeIndependentCommand('cat ls');

        self::assertSame($withoutSudo, $withSudo);
    }

    public function testNested()
    {
        $withSudo = self::executeIndependentCommand('sudo sudo sudo sudo cat ls');
        $withoutSudo = self::executeIndependentCommand('cat ls');

        self::assertSame($withoutSudo, $withSudo);
    }

    public function testIgnoreAsArgument()
    {
        $withSudo = self::executeIndependentCommand('cd sudo dir');
        $withoutSudo = self::executeIndependentCommand('cd dir');

        self::assertNotSame($withoutSudo, $withSudo);
    }
}
