<?php

namespace App\Factory;

use App\Entity\ApiTokens;
use App\Repository\ApiTokensRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ApiTokens>
 *
 * @method        ApiTokens|Proxy create(array|callable $attributes = [])
 * @method static ApiTokens|Proxy createOne(array $attributes = [])
 * @method static ApiTokens|Proxy find(object|array|mixed $criteria)
 * @method static ApiTokens|Proxy findOrCreate(array $attributes)
 * @method static ApiTokens|Proxy first(string $sortedField = 'id')
 * @method static ApiTokens|Proxy last(string $sortedField = 'id')
 * @method static ApiTokens|Proxy random(array $attributes = [])
 * @method static ApiTokens|Proxy randomOrCreate(array $attributes = [])
 * @method static ApiTokensRepository|RepositoryProxy repository()
 * @method static ApiTokens[]|Proxy[] all()
 * @method static ApiTokens[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ApiTokens[]|Proxy[] createSequence(array|callable $sequence)
 * @method static ApiTokens[]|Proxy[] findBy(array $attributes)
 * @method static ApiTokens[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ApiTokens[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ApiTokensFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'ownedBy' => UserFactory::new(),
            'scopes' => [
                ApiTokens::SCOPE_TREASURE_CREATE,
                ApiTokens::SCOPE_USER_EDIT
            ],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ApiTokens $apiTokens): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ApiTokens::class;
    }
}
