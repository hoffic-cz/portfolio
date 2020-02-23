<?php
declare(strict_types=1);


namespace App\Stats;


use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserTracker
{
    private const UUID_KEY = 'visitor';

    /** @var string */
    private $uuid;

    /**
     * UserTracker constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->uuid = $session->get(self::UUID_KEY);

        if (is_null($this->uuid)) {
            $this->uuid = $this->generateUuid();
            $session->set('visitor', $this->uuid);
            $session->save();
        } else {
            $this->uuid = $session->get(self::UUID_KEY);
        }
    }

    /**
     * @return string
     */
    private function generateUuid(): string
    {
        try {
            return Uuid::uuid4()->toString();
        } catch (\Exception $e) {
            throw new \LogicException($e);
        }
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
