<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;

class VimCommand implements Command, Trigger
{
    const START_TIME_KEY = 'maze_start_time';
    const HAS_REOPENED = 'vim_reopened';

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $history->setNote(self::START_TIME_KEY, microtime(true));

        $output = new CommandOutput();
        $output->setTrigger('maze');
        return $output;
    }

    function trigger(array $params, ?History $history = null): CommandOutput
    {
        $output = new CommandOutput();

        $history->getNote(self::START_TIME_KEY);

        $output->setAlert(sprintf(
            'Congratulations! You managed to exit vim in %.2f seconds!',
            $this->gatherTimeAmount($history)));

        return $output;
    }

    private function gatherTimeAmount(History $history): float
    {
        $amount = microtime(true) - $history->getNote(self::START_TIME_KEY);
        $history->unsetNote(self::START_TIME_KEY);
        $history->setNote(self::HAS_REOPENED, true);

        return $amount;
    }
}
