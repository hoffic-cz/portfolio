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

    /** @var PwdCommand */
    private $pwdCommand;

    /** @var CdCommand */
    private $cdCommand;

    /**
     * Terminal constructor.
     * @param NotFoundCommand $notFoundCommand
     * @param IntroCommand $introCommand
     * @param ClearCommand $clearCommand
     * @param CatCommand $catCommand
     * @param PwdCommand $pwdCommand
     * @param CdCommand $cdCommand
     */
    public function __construct(
        NotFoundCommand $notFoundCommand,
        IntroCommand $introCommand,
        ClearCommand $clearCommand,
        CatCommand $catCommand,
        PwdCommand $pwdCommand,
        CdCommand $cdCommand
    )
    {
        $this->notFoundCommand = $notFoundCommand;
        $this->introCommand = $introCommand;
        $this->clearCommand = $clearCommand;
        $this->catCommand = $catCommand;
        $this->pwdCommand = $pwdCommand;
        $this->cdCommand = $cdCommand;
    }

    public function command(string $command): CommandOutput
    {
        $parts = explode(' ', $command);

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
            case 'pwd':
                $output = $this->pwdCommand->execute($parts);
                break;
            case 'cd':
                $output = $this->cdCommand->execute($parts);
                break;
            default:
                $output = $this->notFoundCommand->execute($parts);
                break;
        }

        return $output;
    }
}
