<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class ManTest extends BaseTest
{
    public function testLsShowsHint()
    {
        self::assertContains(' -a ', self::executeIndependentCommand('man ls'));
    }

    public function testCommandNotFound()
    {
        self::assertContains(
            'Does not exist',
            self::executeIndependentCommand('man does-not-exist'),
            '',
            true);
    }

    public function testNoManPage()
    {
        self::assertContains(
            'No manual entry',
            self::executeIndependentCommand('man help'),
            '',
            true);
    }

    public function testManMan()
    {
        self::assertContains(
            'Inception',
            self::executeIndependentCommand('man man'),
            '',
            true);
    }
}
