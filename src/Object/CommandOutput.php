<?php
declare(strict_types=1);


namespace App\Object;


class CommandOutput
{
    /** @var string|null */
    private $stdout;

    /** @var string|null */
    private $alert;

    /** @var string|null */
    private $trigger;

    /**
     * CommandOutput constructor.
     * @param string|null $stdout
     * @param string|null $alert
     */
    public function __construct(?string $stdout = null, ?string $alert = null)
    {
        $this->stdout = $stdout;
        $this->alert = $alert;
    }

    /**
     * @return string
     */
    public function getStdout(): ?string
    {
        return $this->stdout;
    }

    /**
     * @param string|null $stdout
     */
    public function setStdout(?string $stdout): void
    {
        $this->stdout = $stdout;
    }

    /**
     * @return string
     */
    public function getAlert(): ?string
    {
        return $this->alert;
    }

    /**
     * @param string|null $alert
     */
    public function setAlert(?string $alert): void
    {
        $this->alert = $alert;
    }

    public function hasSpecialOutput(): bool
    {
        return !is_null($this->alert);
    }

    /**
     * @return string|null
     */
    public function getTrigger(): ?string
    {
        return $this->trigger;
    }

    /**
     * @param string|null $trigger
     */
    public function setTrigger(?string $trigger): void
    {
        $this->trigger = $trigger;
    }

    /**
     * @return string
     */
    public function summary(): string
    {
        return sprintf(
            'stdout: %s, alert: %s, trigger: %s',
            $this->stdout,
            $this->alert,
            $this->trigger
        );
    }

    public function __toString()
    {
        return $this->summary();
    }
}
