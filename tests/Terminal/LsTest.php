<?php
declare(strict_types=1);


namespace App\Tests\Terminal;


use App\Tests\BaseTest;

class LsTest extends BaseTest
{
    public function testContainsSecretsFolder()
    {
        self::assertContains('secrets', self::executeIndependentCommand('ls'));
    }

    public function testSameAsCdSecretsFolder()
    {
        self::assertEquals(
            self::executeIndependentCommand('cd secrets'),
            self::executeIndependentCommand('ls secrets')
        );
    }

    public function testNotContainsEggsCommand()
    {
        self::assertNotContains('egg', self::executeIndependentCommand('ls'));
    }

    public function testContainsEggsCommandWithFlag()
    {
        self::assertContains(' egg ', self::executeIndependentCommand('ls -a'));
    }

    public function testContainsLsCommand()
    {
        self::assertContains(' ls ', self::executeIndependentCommand('ls'));
    }

    public function testContainsHelpCommand()
    {
        self::assertContains(' help ', self::executeIndependentCommand('ls'));
    }

    public function testContainsManCommand()
    {
        self::assertContains(' man ', self::executeIndependentCommand('ls'));
    }
}
