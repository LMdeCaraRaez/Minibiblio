<?php

namespace App\Factory;

use App\Entity\Socio;
use App\Repository\SocioRepository;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Zenstruck\Foundry\Persistence\Proxy;
use Zenstruck\Foundry\Persistence\ProxyRepositoryDecorator;

/**
 * @extends PersistentProxyObjectFactory<Socio>
 *
 * @method        Socio|Proxy                              create(array|callable $attributes = [])
 * @method static Socio|Proxy                              createOne(array $attributes = [])
 * @method static Socio|Proxy                              find(object|array|mixed $criteria)
 * @method static Socio|Proxy                              findOrCreate(array $attributes)
 * @method static Socio|Proxy                              first(string $sortedField = 'id')
 * @method static Socio|Proxy                              last(string $sortedField = 'id')
 * @method static Socio|Proxy                              random(array $attributes = [])
 * @method static Socio|Proxy                              randomOrCreate(array $attributes = [])
 * @method static SocioRepository|ProxyRepositoryDecorator repository()
 * @method static Socio[]|Proxy[]                          all()
 * @method static Socio[]|Proxy[]                          createMany(int $number, array|callable $attributes = [])
 * @method static Socio[]|Proxy[]                          createSequence(iterable|callable $sequence)
 * @method static Socio[]|Proxy[]                          findBy(array $attributes)
 * @method static Socio[]|Proxy[]                          randomRange(int $min, int $max, array $attributes = [])
 * @method static Socio[]|Proxy[]                          randomSet(int $number, array $attributes = [])
 */
final class SocioFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Socio::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'dni' => self::faker()->dni(),
            "apellidos" => self::faker()->lastName(),
            "nombre" => self::faker()->firstName(),
            "telefono" => self::faker()->mobileNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Socio $socio): void {})
        ;
    }
}
