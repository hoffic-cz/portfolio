<?php
declare(strict_types=1);


namespace App\Terminal;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class History
{
    const MAX_LENGTH = 64;

    /** @var array */
    private $commands;

    /** @var SessionInterface */
    private $session;

    /** @var string */
    private $sessionKey;

    private function __construct()
    {
        // Private constructor
    }

    /**
     * @param SessionInterface $session
     * @param string $uid
     * @return History
     */
    public static function load(SessionInterface $session, string $uid): History
    {
        $history = new History();
        $history->session = $session;
        $history->sessionKey = 'history_' . $uid;

        if ($history->session->has($history->sessionKey)) {
            $history->commands = $history->session->get($history->sessionKey);
        }

        return $history;
    }

    public function save()
    {
        $this->session->set($this->sessionKey, $this->commands);
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
}
