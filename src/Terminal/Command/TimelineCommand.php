<?php
declare(strict_types=1);


namespace App\Terminal\Command;


use App\Object\CommandOutput;
use App\Terminal\History;
use App\Util\TimeUtil;
use DateTime;

class TimelineCommand implements Command
{
    private const LEFT_PADDING = 4;
    private const TOP_PADDING = 2;
    private const ACTIVITY_WIDTH = 28;
    private const PERIOD_SHOWN = 3 * 12 + 6;
    private const DATE_FORMAT = 'F Y';
    private const ACTIVITY_HEADING = 'Activity';

    /** @var array */
    private $timeline;

    /** @var DateTime */
    private $endDate;

    /** @var array */
    private $ticks = [];

    /**
     * TimelineCommand constructor.
     * @param array $timeline
     * @param TimeUtil $timeUtil
     */
    public function __construct(array $timeline, TimeUtil $timeUtil)
    {
        $this->timeline = $timeline;
        $this->endDate = $timeUtil->now();

        $this->calculateTicks();
    }

    private function calculateTicks()
    {
        $year = intval($this->endDate->format('Y'));

        while (true) {
            $date = $this->parseDate('January ' . $year);
            $position = $this->positionDate($date);

            if ($position >= 0) {
                $this->ticks[$position] = $year;
            } else {
                break;
            }

            $year--;
        }

        $this->ticks = array_reverse($this->ticks, true);
    }

    function execute(array $params, ?History $history = null): CommandOutput
    {
        $output = str_repeat(PHP_EOL, self::TOP_PADDING);

        $output .= $this->header();
        $output .= $this->divider();

        foreach ($this->timeline as $activity => $period) {
            [$start, $end] = $this->extractDates($period);

            // -1 means include the first (non-full) month
            $startPosition = $this->positionDate($start) - 1;
            $endPosition = $this->positionDate($end);

            if ($startPosition >= 0) {
                $output .= $this->row($activity,
                    $startPosition,
                    $endPosition);
            }
        }

        $output .= PHP_EOL;

        return new CommandOutput($output);
    }

    private function header(): string
    {
        $output = str_repeat(' ',
            self::LEFT_PADDING
            + self::ACTIVITY_WIDTH
            - strlen(self::ACTIVITY_HEADING));
        $output .= self::ACTIVITY_HEADING;

        $output .= str_repeat(' ', array_key_first($this->ticks));

        foreach ($this->ticks as $year) {
            $output .= $year . str_repeat(' ', 8);
        }

        return $output . PHP_EOL;
    }

    private function divider(): string
    {
        $output = str_repeat(' ',
                self::LEFT_PADDING
                + self::ACTIVITY_WIDTH
                + 1) // 1 for the space between activities and timeline
            . '+';

        for ($position = 1; $position <= self::PERIOD_SHOWN; $position++) {
            if (isset($this->ticks[$position])) {
                $output .= '+';
            } else {
                $output .= '-';
            }
        }

        return $output . PHP_EOL;
    }

    private function row(string $activity, int $start, int $end): string
    {
        return sprintf(
                '%s% ' . self::ACTIVITY_WIDTH . 's |%s%s',
                str_repeat(' ', self::LEFT_PADDING),
                $activity,
                str_repeat(' ', $start),
                str_repeat('X', $end - $start)) . PHP_EOL;
    }

    private function extractDates(string $period): array
    {
        $dates = explode(' - ', $period);

        $start = $this->parseDate($dates[0]);

        if ($dates[1] === 'now') {
            $end = $this->endDate;
        } else {
            $end = $this->parseDate($dates[1]);
        }

        return [$start, $end];
    }

    private function parseDate(string $date): DateTime
    {
        $parsed = DateTime::createFromFormat(self::DATE_FORMAT, $date);
        $parsed->setTime(0, 0, 0, 0);
        $parsed->setDate(
            intval($parsed->format('Y')),
            intval($parsed->format('n')),
            1);

        return $parsed;
    }

    private function positionDate(DateTime $date): int
    {
        $difference = $this->endDate->diff($date);

        $months = $difference->y * 12 + $difference->m;

        return self::PERIOD_SHOWN - $months;
    }
}
