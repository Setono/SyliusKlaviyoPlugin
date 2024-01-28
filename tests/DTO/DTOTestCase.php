<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO;

use PHPUnit\Framework\TestCase;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactory;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class DTOTestCase extends TestCase
{
    private ?Serializer $serializer = null;

    protected PropertiesFactoryInterface $propertiesFactory;

    protected function setUp(): void
    {
        $this->propertiesFactory = new PropertiesFactory(new Container());
    }

    protected function getSerializer(): Serializer
    {
        if (null === $this->serializer) {
            $finder = new Finder();
            $finder->files()->in(__DIR__ . '/../../src/Resources/config/serialization');
            $files = array_map(
                static fn (\SplFileInfo $file) => new XmlFileLoader($file->getRealPath()),
                iterator_to_array($finder),
            );

            $classMetadataFactory = new ClassMetadataFactory(new LoaderChain($files));
            $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)];

            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }

    protected function normalize(object $obj): array
    {
        $data = $this->getSerializer()->normalize($obj, null, [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            'groups' => 'setono:sylius-klaviyo:event',
        ]);

        self::assertIsArray($data);

        return $data;
    }

    /**
     * @test
     */
    public function it_normalizes(): void
    {
        $data = $this->normalize($this->getDTO());
        self::assertEquals($this->getExpectedData(), $data);
    }

    abstract protected function getDTO(): object;

    abstract protected function getExpectedData(): array;
}
