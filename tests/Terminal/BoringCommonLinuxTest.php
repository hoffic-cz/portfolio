<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class BoringCommonLinuxTest extends BaseTest
{
    public function testPwdOutput()
    {
        self::assertContains('/usr/bin/', self::executeIndependentCommand('pwd'));
    }

    public function testCatStupidResponse()
    {
        self::assertContains('dog', self::executeIndependentCommand('cat'));
    }

    public function testTailStupidResponse()
    {
        self::assertContains('dog', self::executeIndependentCommand('tail'));
    }

    public function testClearInsecureResponse()
    {
        self::assertContains('insecure', self::executeIndependentCommand('clear'));
    }
}
