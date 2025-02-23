<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

beforeEach(function (): void {
    $this->modelCount = 4;
});

arch('models')
    ->expect('App\Models')
    ->toHaveMethod('casts')
    ->toExtend(Model::class);

arch('ensure factories', function (): void {
    expect($models = getModels())->toHaveCount($this->modelCount);

    foreach ($models as $model) {
        /* @var HasFactory $model */
        expect($model::factory())
            ->toBeInstanceOf(Factory::class);
    }
});

arch('ensure datetime casts', function (): void {
    expect($models = getModels())->toHaveCount($this->modelCount);

    foreach ($models as $model) {
        /* @var HasFactory $model */
        $instance = $model::factory()->create();

        $dates = collect($instance->getAttributes())
            ->filter(fn ($_, $key): bool => str_ends_with((string) $key, '_at'))
            ->reject(fn ($_, $key): bool => in_array($key, ['created_at', 'updated_at']));

        foreach ($dates as $key => $value) {
            expect($instance->getCasts())->toHaveKey(
                $key,
                'datetime',
                sprintf(
                    'The %s cast on the %s model is not a datetime cast.',
                    $key,
                    $model,
                ),
            );
        }
    }
});

/**
 * Get all models in the app/Models directory.
 *
 * @return array<int, class-string<Model>>
 */
function getModels(): array
{
    $models = glob(__DIR__.'/../../app/Models/*.php');

    return collect($models)
        ->map(fn ($file): string => 'App\Models\\'.basename($file, '.php'))->toArray();
}
