<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Enums\CookieKey;
use App\Enums\HttpMethod;
use App\Enums\SessionKey;
use App\Http\Middleware\AnonymousTokenMiddleware;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Response;
use Inertia\ResponseFactory;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\AsObject;

final class ViewUrls
{
    use AsController, AsObject;

    /**
     * @param  ActionRequest  $request
     * @return LengthAwarePaginator<Url>|AnonymousResourceCollection
     */
    public function handle(ActionRequest $request): LengthAwarePaginator|AnonymousResourceCollection
    {
        $user = $request->user();
        $anonymousToken = $request->cookie(CookieKey::ANONYMOUS_TOKEN->value);
        $query = Url::query();

        if (is_null($user) && is_null($anonymousToken)) {
            return UrlResource::collection($query->whereRaw('1 = 0')->paginate());
        }

        if ($user instanceof User) {
            $query->where('user_id', $user->id);
        } else {
            $query
                ->whereNull('user_id')
                ->where('anonymous_token', $anonymousToken);
        }

        $order = $request->query('order') === 'asc' ? 'asc' : 'desc';

        return UrlResource::collection(
            $query
                ->select('id', 'source', 'created_at')
                ->withCount([
                    'requests' => static function (Builder $query): void {
                        $route = mb_rtrim(route('redirect-to-source', ['url' => 0]), '0');
                        $query->where('method', HttpMethod::GET->value)
                            ->whereRaw("uri = CONCAT('$route', urls.id)");
                    },
                ])
                ->when(
                    $request->query('orderBy') === 'clicks',
                    static function (Builder $query) use ($order): void {
                        $query->orderBy('requests_count', $order);
                    },
                    static function (Builder $query) use ($order): void {
                        $query->orderBy('created_at', $order);
                    }
                )
                ->paginate(5)
        );
    }

    /**
     * @return array<int, string>
     */
    public function getControllerMiddleware(): array
    {
        return [AnonymousTokenMiddleware::class];
    }

    /**
     * @param  ActionRequest  $request
     * @return Response|ResponseFactory
     */
    public function asController(ActionRequest $request): Response|ResponseFactory
    {
        return inertia('Home/index', [
            'lastShortenedUrl' => fn () => $request->session()->get(SessionKey::LAST_SHORTENED_URL->value),
            'anonymousToken' => fn () => $request->cookie(CookieKey::ANONYMOUS_TOKEN->value),
            'urls' => fn (): LengthAwarePaginator|AnonymousResourceCollection => $this->handle($request),
        ]);
    }
}
