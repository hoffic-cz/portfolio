<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;
use App\Util\CmdImplProvider;

class ManCommand implements Command, Manual, Usage
{
    private const BOOKWORM_THRESHOLD = 3;

    /** @var CmdImplProvider */
    private $cmdImplProvider;

    /**
     * ManCommand constructor.
     * @param CmdImplProvider $cmdImplProvider
     */
    public function __construct(CmdImplProvider $cmdImplProvider)
    {
        $this->cmdImplProvider = $cmdImplProvider;
    }

    function execute(array $params, ?History $history = null): CommandOutput
    {
        array_shift($params); // Discard 'man'

        if ($this->triggerBookworm($history)) {
            return $this->bookwormEgg();
        } elseif (empty($params)) {
            return $this->usage();
        } else {
            $name = array_shift($params);
            $implementation = $this->cmdImplProvider->get($name);

            if ($implementation instanceof Manual) {
                return $implementation->manual($params, $history);
            } else {
                return $this->specialCases($name, $implementation, $history);
            }
        }
    }

    private function triggerBookworm(?History $history)
    {
        if (!is_null($history) && !$history->hasNote('bookworm')) {
            $count = 0;
            foreach ($history->getCommands() as $command) {
                if ($command === 'man') {
                    $count++;
                }
            }
            if ($count >= self::BOOKWORM_THRESHOLD) {
                $history->setNote('bookworm', true);
                return true;
            }
        }

        return false;
    }

    private function bookwormEgg(): CommandOutput
    {
        $output = new CommandOutput();

        $output->setAlert('oH hEY! wE GoT US a BoOKwOrm -:- KEEP UP THE GOOD WORK!');

        return $output;
    }

    function usage(): CommandOutput
    {
        return new CommandOutput(/** @lang text */ <<<'STDOUT'
Usage: 'man <command>'
STDOUT
        );
    }

    private function specialCases(string $name, Command $implementation, ?History $history): CommandOutput
    {
        if ($implementation instanceof NotFoundCommand) {
            return new CommandOutput(sprintf(
                "How about looking up a man page for a command that exists? "
                . ":P '%s' does not exist.",
                $name));
        } else {
            return new CommandOutput('No manual entry. This command has no hidden functionality.');
        }
    }

    function manual(array $params, ?History $history = null): CommandOutput
    {
        return new CommandOutput(<<<'STDOUT'
Hmm... I think you would love the movie Inception.

Who would believe there'd be a manual page for how to use a manual.

The question is, do you know how to exit Vim?
STDOUT
        );
    }
}
