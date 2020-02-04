<?php
declare(strict_types=1);


namespace App\Terminal;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class History
{
    const MAX_LENGTH = 64;

    private const SESSION_KEY = 'history';

    /** @var array */
    private $commands = [];

    /** @var array */
    private $notes = [];

    /** @var SessionInterface */
    private $session;

    private function __construct()
    {
        // Private constructor
    }

    /**
     * @param SessionInterface $session
     * @return History
     */
    public static function load(SessionInterface $session): History
    {
        $history = new History();
        $history->session = $session;

        if ($history->session->has(self::SESSION_KEY)) {
            $package = $history->session->get(self::SESSION_KEY);
            $history->commands = $package['commands'];
            $history->notes = $package['notes'];
        }

        return $history;
    }

    /**
     * @param string $command
     */
    public function log(string $command)
    {
        if (count($this->commands) >= self::MAX_LENGTH) {
            array_shift($this->commands);
        }

        array_push($this->commands, $command);

        $this->save();
    }

    /**
     * @param string $command
     * @return bool
     */
    public function has(string $command): bool
    {
        return in_array($command, $this->commands);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setNote(string $name, $value)
    {
        $this->notes[$name] = $value;

        $this->save();
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getNote(string $name)
    {
        return $this->hasNote($name) ? $this->notes[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasNote(string $name): bool
    {
        return isset($this->notes[$name]);
    }

    /**
     * @param string $name
     */
    public function unsetNote(string $name)
    {
        if (isset($this->notes[$name])) {
            unset($this->notes[$name]);
        }

        $this->save();
    }

    private function save()
    {
        $package = [
            'commands' => $this->commands,
            'notes' => $this->notes,
        ];

        $this->session->set(self::SESSION_KEY, $package);
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}
