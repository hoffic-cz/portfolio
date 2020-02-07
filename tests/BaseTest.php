<?php
declare(strict_types=1);


namespace App\Tests;


use App\Object\CommandOutput;
use App\Terminal\Terminal;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

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
     * @return CommandOutput
     */
    protected static function executeIndependentCommandRaw(string $command): CommandOutput
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        return $terminal->command($command);
    }

    /**
     * @param string $command
     * @return string
     */
    protected static function executeIndependentCommand(string $command): string
    {
        return self::executeIndependentCommandRaw($command)->getStdout();
    }

    /**
     * @param string $command
     * @param SessionInterface $session
     * @return CommandOutput
     */
    protected static function executeInSessionRaw(
        string $command,
        SessionInterface $session
    ): CommandOutput
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);
        $terminal->setSession($session);

        return $terminal->command($command);
    }

    /**
     * @param string $command
     * @param SessionInterface $session
     * @return string
     */
    protected static function executeInSession(
        string $command,
        SessionInterface $session
    ): ?string
    {
        return self::executeInSessionRaw($command, $session)->getStdout();
    }

    /**
     * @return SessionInterface
     */
    protected static function getTestSession(): SessionInterface
    {
        $session = new Session(new MockArraySessionStorage());

        $session->start();

        return $session;
    }
}
