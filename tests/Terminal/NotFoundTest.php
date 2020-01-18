<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Terminal\Terminal;
use App\Tests\BaseTest;

class NotFoundTest extends BaseTest
{
    public function testNotFound()
    {
        /** @var Terminal $terminal */
        $terminal = self::getService(Terminal::class);

        $output = $terminal->command(':void:');

        self::assertContains(
            "Command ':void:' not found, did you mean:",
            $output->getStdout()
        );
    }
}
