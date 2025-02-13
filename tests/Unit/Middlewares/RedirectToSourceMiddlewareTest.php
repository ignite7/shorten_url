<?php

declare(strict_types=1);

use App\Http\Middleware\RedirectToSourceMiddleware;
use App\Models\Url;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function (): void {
    Route::middleware(RedirectToSourceMiddleware::class)->get('/test/{url}', fn () => response('HTTP_FOUND', Response::HTTP_FOUND));
});

it('returns 404 if the url is not found', function (): void {
    $response = $this->get('/test/example');

    $response->assertNotFound();
});

it('returns 404 if the url exists but is inactive', function (): void {
    $url = Url::factory()->inactive()->create();

    $response = $this->get("/test/$url->id");

    $response->assertNotFound();
});

it('allows request if the url exists and is active', function (): void {
    $url = Url::factory()->create();

    $response = $this->get("/test/$url->id");

    $response->assertRedirect();
});
