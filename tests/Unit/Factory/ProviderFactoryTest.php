<?php

namespace Unit\Factory;

use App\Factory\ProviderFactory;
use App\Processor\ProcessorInterface;
use App\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;

class TestProvider implements ProviderInterface
{
    public static function support(): string
    {
        return 'test';
    }

    public function provide(array $iterable): array
    {
        // TODO: Implement provide() method.
    }

    public function setCurrencies(array $currencies): void
    {
        // TODO: Implement setCurrencies() method.
    }

    public function setProcessor(ProcessorInterface $processor): void
    {
        // TODO: Implement setProcessor() method.
    }
}

class ProviderFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $provider = (new ProviderFactory([new TestProvider()]))->create('test');
        $this->assertInstanceOf(TestProvider::class, $provider);
    }

    public function testCreateWithInvalidProvider(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ProviderFactory([new TestProvider()]))->create('invalid');
    }
}