<?php

declare(strict_types=1);

use App\Http\Resources\UrlResource;
use App\Models\Url;

it('can get the URL resource', function (): void {
    $url = Url::factory()->create();

    $resource = new UrlResource($url);

    expect($resource->toArray(request()))->toMatchArray([
        'id' => $url->id,
        'source' => $url->source,
        'clicks' => 0,
        'created_at' => $url->created_at
    ]);
});
