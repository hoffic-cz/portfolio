<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\Terminal;

class LsCommand implements Command
{
    /** @var CdCommand */
    private $cdCommand;

    /**
     * LsCommand constructor.
     * @param CdCommand $cdCommand
     */
    public function __construct(CdCommand $cdCommand)
    {
        $this->cdCommand = $cdCommand;
    }

    function execute(array $params): CommandOutput
    {
        if (count($params) <= 1) {
            $output = $this->listCommands();

            return new CommandOutput($output);
        } elseif ($params[1] === '-a') {
            $output = $this->listCommands(true);

            return new CommandOutput($output);
        } elseif (substr($params[1], 0, 7) === 'secrets') {
            return $this->cdCommand->execute($params);
        } else {
            return new CommandOutput('Unknown option.');
        }
    }

    private function listCommands(bool $all = false): string
    {
        $output = '';

        foreach (Terminal::COMMANDS as $name => $properties) {
            if ($all || $properties[1]) {
                $output .= $this->renderCommandEntry($name) . PHP_EOL;
            }
        }

        $output .= 'drwx------ 1 root root          4096 Jan 20 11:00  secrets ';

        return $output;
    }

    private function renderCommandEntry(string $command, string $date = 'Jan 01 23:45'): string
    {
        return sprintf(
            '-rwxr-xr-x 1 root root       % 7s %s  %s ',
            crc32($command) % 100000,
            $date,
            $command
        );
    }
}
