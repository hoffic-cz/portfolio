<?php
declare(strict_types=1);


namespace App\Util;


use App\Terminal\Command\Command;
use App\Terminal\Command\NotFoundCommand;
use App\Terminal\Terminal;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CmdImplProvider
{
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

    public function get(string $name): Command
    {
        /** @var Command $implementation */
        if (isset(Terminal::COMMANDS[$name])) {
            $implementation = $this->container->get(Terminal::COMMANDS[$name][0]);
        } else {
            $implementation = $this->container->get(NotFoundCommand::class);
        }

        return $implementation;
    }
}
