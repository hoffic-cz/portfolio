<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;
use App\Util\CmdImplProvider;

class ManCommand implements Command, Usage
{
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
        if (empty($params)) {
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

    function usage(): CommandOutput
    {
        return new CommandOutput('WIP');
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
}
