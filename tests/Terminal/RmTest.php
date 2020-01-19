<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Terminal\Terminal;
use App\Tests\BaseTest;

class RmTest extends BaseTest
{
    public function testHint()
    {
        self::assertContains("Did you mean 'sudo rm -Rf /'?", self::executeIndependentCommand('rm '));
    }

    public function testRecursiveRootRemove1()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command('sudo rm -Rf /');

        self::assertEquals('rm', $output->getTrigger());
    }

    public function testRecursiveRootRemove2()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command('rm -Rf /');

        self::assertEquals('rm', $output->getTrigger());
    }

    public function testRecursiveRootRemove3()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command('sudo rm -Rf /*');

        self::assertEquals('rm', $output->getTrigger());
    }

    public function testNotOtherInputs()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command('sudo rm -Rf /something-else/');

        self::assertNull($output->getTrigger());
    }
}
