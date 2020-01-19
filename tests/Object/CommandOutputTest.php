<?php
declare(strict_types=1);


namespace App\Tests\Object;


use App\Object\CommandOutput;
use App\Tests\BaseTest;

class CommandOutputTest extends BaseTest
{
    public function testEmptyStdout1()
    {
        $output = new CommandOutput();
        self::assertNull($output->getStdout());
    }

    public function testEmptyStdout2()
    {
        $output = new CommandOutput(null, 'Hello Clem!');
        self::assertNull($output->getStdout());
    }

    public function testEmptyAlert1()
    {
        $output = new CommandOutput('Hello Clem!');
        self::assertNull($output->getAlert());
    }

    public function testEmptyAlert2()
    {
        $output = new CommandOutput('Hello Clem!', null);
        self::assertNull($output->getAlert());
    }

    public function testValueStdout1()
    {
        $output = new CommandOutput('Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getStdout());
    }

    public function testValueStdout2()
    {
        $output = new CommandOutput();
        $output->setStdout('Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getStdout());
    }

    public function testValueAlert1()
    {
        $output = new CommandOutput(null, 'Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getAlert());
    }

    public function testValueAlert2()
    {
        $output = new CommandOutput();
        $output->setAlert('Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getAlert());
    }

    public function testEmptyTrigger1()
    {
        $output = new CommandOutput();
        self::assertNull($output->getTrigger());
    }

    public function testEmptyTrigger2()
    {
        $output = new CommandOutput('Hello', 'Clem!');
        self::assertNull($output->getTrigger());
    }

    public function testValueTrigger()
    {
        $output = new CommandOutput();
        $output->setTrigger('Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getTrigger());
    }
}
