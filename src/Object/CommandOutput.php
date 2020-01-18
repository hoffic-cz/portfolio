<?php


namespace App\Object;


class CommandOutput
{
    /** @var string */
    private $stdout;

    /** @var string */
    private $alert;

    /**
     * CommandOutput constructor.
     * @param string $stdout
     * @param string $alert
     */
    public function __construct(string $stdout, string $alert)
    {
        $this->stdout = $stdout;
        $this->alert = $alert;
    }

    /**
     * @return string
     */
    public function getStdout(): string
    {
        return $this->stdout;
    }

    /**
     * @return string
     */
    public function getAlert(): string
    {
        return $this->alert;
    }

    public function hasSpecialOutput(): bool
    {
        return !is_null($this->alert);
    }
}
