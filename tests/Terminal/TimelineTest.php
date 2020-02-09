<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;
use App\Util\TimeUtil;

class TimelineTest extends BaseTest
{
    public function testDatesRendering()
    {
        /** @var TimeUtil $timeUtil */
        $timeUtil = self::getService(TimeUtil::class);
        $timeUtil->setSimulatedTime(new \DateTime('2020-02-03 00:00'));

        self::assertContains(
            'Activity     2017        2018        2019        2020',
            self::executeIndependentCommand('timeline'));
    }

    public function testTicksRendering()
    {
        /** @var TimeUtil $timeUtil */
        $timeUtil = self::getService(TimeUtil::class);
        $timeUtil->setSimulatedTime(new \DateTime('2020-02-03 00:00'));

        self::assertContains(
            ' +----+-----------+-----------+-----------+-',
            self::executeIndependentCommand('timeline'));
    }

    public function testWorksInFuture()
    {
        /** @var TimeUtil $timeUtil */
        $timeUtil = self::getService(TimeUtil::class);
        $timeUtil->setSimulatedTime(new \DateTime('2030-02-03 00:00'));

        self::executeIndependentCommand('timeline');

        // Checking that this test didn't end with an exception
        self::assertTrue(true);
    }


}
