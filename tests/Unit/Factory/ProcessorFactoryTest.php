<?php

namespace Unit\Factory;

use App\Factory\ProcessorFactory;
use App\Model\Currency\CurrencyInterface;
use App\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;

final class TestProcessor implements ProcessorInterface
{
    public static function support(): string
    {
        return 'test';
    }

    public function process(CurrencyInterface $currency, array $currencyData): void
    {
    }
}

class ProcessorFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $processor = (new ProcessorFactory([new TestProcessor()]))->create('test');
        $this->assertInstanceOf(TestProcessor::class, $processor);
    }

    public function testCreateWithInvalidProcessor(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ProcessorFactory([new TestProcessor()]))->create('invalid');
    }
}