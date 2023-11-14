<?php

namespace Integration\Factory;

use App\Enum\CurrencyTypeEnum;
use App\Processor\CurrencyProcessor;
use App\Processor\HistoricalCurrencyProcessor;
use App\Processor\ProcessorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Factory\ProcessorFactory;

class ProcessFactoryTest extends KernelTestCase
{
    public function testCreateWillReturnProcessor(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $processor = $container->get(ProcessorFactory::class)->create(CurrencyTypeEnum::RATES);

        $this->assertInstanceOf(ProcessorInterface::class, $processor);
    }

    /**
     * @dataProvider currencyTypesDataProvider
     */
    public function testCreateWillReturnValidProcessor(string $type, $expectedClass): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $processor = $container->get(ProcessorFactory::class)->create($type);

        $this->assertInstanceOf($expectedClass, $processor);
    }

    public function testCreateWillThrowInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $container->get(ProcessorFactory::class)->create('invalid');
    }

    public function currencyTypesDataProvider(): array
    {
        return [
            [CurrencyTypeEnum::RATES, CurrencyProcessor::class],
            [CurrencyTypeEnum::HISTORICAL_RATES, HistoricalCurrencyProcessor::class],
        ];
    }
}