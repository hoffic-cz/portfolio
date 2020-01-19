<?php
declare(strict_types=1);


namespace App\Terminal;


use App\Object\CommandOutput;
use App\Terminal\Command\CatCommand;
use App\Terminal\Command\CdCommand;
use App\Terminal\Command\ClearCommand;
use App\Terminal\Command\IntroCommand;
use App\Terminal\Command\NotFoundCommand;
use App\Terminal\Command\PwdCommand;
use App\Terminal\Command\RmCommand;
use App\Terminal\Command\TailCommand;

class Terminal
{
    /** @var NotFoundCommand */
    private $notFoundCommand;

    /** @var IntroCommand */
    private $introCommand;

    /** @var ClearCommand */
    private $clearCommand;

    /** @var CatCommand */
    private $catCommand;

    /** @var TailCommand */
    private $tailCommand;

    /** @var PwdCommand */
    private $pwdCommand;

    /** @var CdCommand */
    private $cdCommand;

    /** @var RmCommand */
    private $rmCommand;

    /**
     * Terminal constructor.
     * @param NotFoundCommand $notFoundCommand
     * @param IntroCommand $introCommand
     * @param ClearCommand $clearCommand
     * @param CatCommand $catCommand
     * @param TailCommand $tailCommand
     * @param PwdCommand $pwdCommand
     * @param CdCommand $cdCommand
     * @param RmCommand $rmCommand
     */
    public function __construct(
        NotFoundCommand $notFoundCommand,
        IntroCommand $introCommand,
        ClearCommand $clearCommand,
        CatCommand $catCommand,
        TailCommand $tailCommand,
        PwdCommand $pwdCommand,
        CdCommand $cdCommand,
        RmCommand $rmCommand
    )
    {
        $this->notFoundCommand = $notFoundCommand;
        $this->introCommand = $introCommand;
        $this->clearCommand = $clearCommand;
        $this->catCommand = $catCommand;
        $this->tailCommand = $tailCommand;
        $this->pwdCommand = $pwdCommand;
        $this->cdCommand = $cdCommand;
        $this->rmCommand = $rmCommand;
    }

    public function command(string $command): CommandOutput
    {
        $parts = explode(' ', $command);
        $this->removeSudo($parts);

        $output = null;

        switch ($parts[0]) {
            case 'intro':
                $output = $this->introCommand->execute($parts);
                break;
            case 'clear':
                $output = $this->clearCommand->execute($parts);
                break;
            case 'cat':
                $output = $this->catCommand->execute($parts);
                break;
            case 'tail':
                $output = $this->tailCommand->execute($parts);
                break;
            case 'pwd':
                $output = $this->pwdCommand->execute($parts);
                break;
            case 'cd':
                $output = $this->cdCommand->execute($parts);
                break;
            case 'rm':
                $output = $this->rmCommand->execute($parts);
                break;
            default:
                $output = $this->notFoundCommand->execute($parts);
                break;
        }

        return $output;
    }

    private function removeSudo(array &$parts)
    {
        while (isset($parts[0]) && $parts[0] === 'sudo') {
            array_shift($parts);
        }
    }
}
