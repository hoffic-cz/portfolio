<?php
declare(strict_types=1);


namespace App\Tests;


use App\Object\CommandOutput;
use App\Terminal\Terminal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseTest extends KernelTestCase
{
    /**
     * @param string $service
     * @return object|null
     */
    protected static function getService(string $service)
    {
        if (is_null(self::$kernel)) {
            self::bootKernel();
        }

        return self::$container->get($service);
    }

    /**
     * @param string $command
     * @return string
     */
    protected static function executeIndependentCommand(string $command): string
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        return $terminal->command($command)->getStdout();
    }

    /**
     * @param string $command
     * @param string $uid
     * @return string
     */
    protected static function executeInSession(string $command, string $uid): string
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        return $terminal->command($command, $uid)->getStdout();
    }
}
