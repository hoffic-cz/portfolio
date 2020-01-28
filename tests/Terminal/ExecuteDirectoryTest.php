<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class ExecuteDirectoryTest extends BaseTest
{
    public function testExecuteDirectory()
    {
        self::assertContains(
            'is a directory',
            self::executeIndependentCommand('secrets'),
            '',
            true
        );
    }
}
