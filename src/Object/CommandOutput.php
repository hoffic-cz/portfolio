<?php
declare(strict_types=1);


namespace App\Object;


class CommandOutput
{
    /** @var string|null */
    private $stdout;

    /** @var string|null */
    private $alert;

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
     * @return string
     */
    public function getAlert(): ?string
    {
        return $this->alert;
    }

    public function hasSpecialOutput(): bool
    {
        return !is_null($this->alert);
    }
}
