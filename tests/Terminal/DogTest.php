<?php
declare(strict_types=1);


namespace App\Tests\Terminal;

use App\Tests\BaseTest;

class DogTest extends BaseTest
{
    function testContainsWow()
    {
        self::assertContains('wow', self::executeIndependentCommand('dog'));
    }

    function testContainsExplorer()
    {
        self::assertContains('explorer', self::executeIndependentCommand('dog'));
    }
}
