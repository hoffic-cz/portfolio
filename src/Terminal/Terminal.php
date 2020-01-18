<?php
declare(strict_types=1);


namespace App\Terminal;


use App\Object\CommandOutput;
use App\Terminal\Command\IntroCommand;
use App\Terminal\Command\NotFoundCommand;

class Terminal
{
    /** @var NotFoundCommand */
    private $notFoundCommand;

    /** @var IntroCommand */
    private $introCommand;

    /**
     * Terminal constructor.
     * @param NotFoundCommand $notFoundCommand
     * @param IntroCommand $introCommand
     */
    public function __construct(
        NotFoundCommand $notFoundCommand,
        IntroCommand $introCommand
    )
    {
        $this->notFoundCommand = $notFoundCommand;
        $this->introCommand = $introCommand;
    }

    public function command(string $command): CommandOutput
    {
        $parts = explode(' ', $command);

        $output = null;

        switch ($parts[0]) {
            case 'intro':
                $output = $this->introCommand->execute($parts);
                break;
            default:
                $output = $this->notFoundCommand->execute($parts);
                break;
        }

        return $output;
    }
}
