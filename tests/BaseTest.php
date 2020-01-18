<?php
declare(strict_types=1);


namespace App\Tests;


use App\Object\CommandOutput;
use App\Terminal\Terminal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseTest extends KernelTestCase
{
    protected static function getService(string $service)
    {
        if (is_null(self::$kernel)) {
            self::bootKernel();
        }

        return self::$container->get($service);
    }

    protected static function executeIndependentCommand(string $command): string
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        return $terminal->command($command)->getStdout();
    }
}
