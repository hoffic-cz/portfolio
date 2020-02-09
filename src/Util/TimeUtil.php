<?php
declare(strict_types=1);


namespace App\Util;


use DateTime;

class TimeUtil
{
    /** @var DateTime|null */
    private $simulatedTime = null;

    /**
     * @return DateTime|null
     */
    public function getSimulatedTime(): ?DateTime
    {
        return clone $this->simulatedTime;
    }

    /**
     * @param DateTime|null $simulatedTime
     */
    public function setSimulatedTime(?DateTime $simulatedTime): void
    {
        $this->simulatedTime = clone $simulatedTime;
    }

    /**
     * @return DateTime
     */
    public function now(): DateTime
    {
        try {
            if (is_null($this->simulatedTime)) {
                return new DateTime();
            } else {
                return $this->getSimulatedTime();
            }
        } catch (\Exception $e) {
            throw new \LogicException();
        }
    }
}
