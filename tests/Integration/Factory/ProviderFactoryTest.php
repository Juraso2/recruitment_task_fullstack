<?php

namespace Integration\Factory;

use App\Enum\CurrencyTypeEnum;
use App\Factory\ProviderFactory;
use App\Processor\ProcessorInterface;
use App\Provider\HistoricalRatesProvider;
use App\Provider\ProviderInterface;
use App\Provider\RatesProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Factory\ProcessorFactory;

class ProviderFactoryTest extends KernelTestCase
{
    public function testCreateWillReturnProvider(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $provider = $container->get(ProviderFactory::class)->create(CurrencyTypeEnum::RATES);

        $this->assertInstanceOf(ProviderInterface::class, $provider);
    }

    /**
     * @dataProvider currencyTypesDataProvider
     */
    public function testCreateWillReturnValidProvider(string $type, $expectedClass): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $provider = $container->get(ProviderFactory::class)->create($type);

        $this->assertInstanceOf($expectedClass, $provider);
    }

    public function testCreateWillThrowInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $container->get(ProviderFactory::class)->create('invalid');
    }

    public function currencyTypesDataProvider(): array
    {
        return [
            [CurrencyTypeEnum::RATES, RatesProvider::class],
            [CurrencyTypeEnum::HISTORICAL_RATES, HistoricalRatesProvider::class],
        ];
    }
}