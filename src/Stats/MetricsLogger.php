<?php
declare(strict_types=1);


namespace App\Stats;


use App\Util\TimeUtil;
use Doctrine\DBAL\Connection;

class MetricsLogger
{
    /**
     * @var Connection
     */
    private $connection;

    /** @var TimeUtil */
    private $timeUtil;

    /** @var UserTracker */
    private $userTracker;

    /**
     * MetricsLogger constructor.
     * @param Connection $connection
     * @param TimeUtil $timeUtil
     * @param UserTracker $userTracker
     */
    public function __construct(
        Connection $connection,
        TimeUtil $timeUtil,
        UserTracker $userTracker
    )
    {
        $this->connection = $connection;
        $this->timeUtil = $timeUtil;
        $this->userTracker = $userTracker;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string|null $params
     */
    public function log(string $type, string $name, string $params = null)
    {
        try {
            $qb = $this->connection->createQueryBuilder();
            $qb->insert('activity');
            $qb->values([
                'time' => '?',
                'visitor' => '?',
                'type' => '?',
                'name' => '?',
                'params' => '?',
            ]);
            $qb->setParameters([
                $this->timeUtil->now()->format('c'),
                $this->userTracker->getUuid(),
                $type,
                $name,
                $params
            ]);
            $qb->execute();
        } catch (\Exception $e) {
            // TODO: Log this
        }
    }
}
