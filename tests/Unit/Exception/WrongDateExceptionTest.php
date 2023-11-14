<?php

namespace Unit\Exception;

use App\Exception\WrongDateException;
use PHPUnit\Framework\TestCase;

class WrongDateExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $this->expectExceptionMessage('Provided date "2019-01-01" is not valid');
        throw new WrongDateException('2019-01-01');
    }
}