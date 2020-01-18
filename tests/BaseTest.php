<?php
declare(strict_types=1);


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseTest extends KernelTestCase
{
    protected static function getService(string $service)
    {
        if (is_null(self::$kernel)) {
            self::bootKernel();
        }

        return self::$container->get($service);
    }
}
