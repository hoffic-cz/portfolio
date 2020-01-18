<?php


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

    public function testValueStdout()
    {
        $output = new CommandOutput('Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getStdout());
    }

    public function testValueAlert()
    {
        $output = new CommandOutput(null, 'Hello Clem!');
        self::assertEquals('Hello Clem!', $output->getAlert());
    }
}
