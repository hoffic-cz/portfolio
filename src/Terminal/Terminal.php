<?php
declare(strict_types=1);


namespace App\Terminal;


use App\Object\CommandOutput;
use App\Terminal\Command\CatCommand;
use App\Terminal\Command\CdCommand;
use App\Terminal\Command\ClearCommand;
use App\Terminal\Command\Command;
use App\Terminal\Command\ContactCommand;
use App\Terminal\Command\IntroCommand;
use App\Terminal\Command\LsCommand;
use App\Terminal\Command\NotFoundCommand;
use App\Terminal\Command\PwdCommand;
use App\Terminal\Command\RmCommand;
use App\Terminal\Command\TailCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Terminal
{
    public const COMMANDS = [
        'intro' => [IntroCommand::class, true],
        'clear' => [ClearCommand::class, true],
        'cat' => [CatCommand::class, true],
        'tail' => [TailCommand::class, true],
        'pwd' => [PwdCommand::class, true],
        'cd' => [CdCommand::class, true],
        'rm' => [RmCommand::class, true],
        'contact' => [ContactCommand::class, true],
        'ls' => [LsCommand::class, true],
        'help' => [null, true],
        'man' => [null, true],
        'egg' => [null, false],
    ];

    public const DIRECTORIES = [
        'secrets'
    ];

    /** @var ContainerInterface */
    private $container;

    /**
     * Terminal constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function command(string $command): CommandOutput
    {
        $parts = explode(' ', $command);
        $this->removeSudo($parts);
        $name = $parts[0];

        /** @var Command $implementation */
        if (isset(self::COMMANDS[$name])) {
            $implementation = $this->container->get(self::COMMANDS[$name][0]);
        } elseif (in_array($name, self::DIRECTORIES)) {
            return new CommandOutput(sprintf('bash: %s: Is a directory', $name));
        } else {
            $implementation = $this->container->get(NotFoundCommand::class);
        }

        return $implementation->execute($parts);
    }

    private function removeSudo(array &$parts)
    {
        while (isset($parts[0]) && $parts[0] === 'sudo') {
            array_shift($parts);
        }
    }
}
