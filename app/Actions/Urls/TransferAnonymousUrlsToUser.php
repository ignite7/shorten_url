<?php

declare(strict_types=1);

namespace App\Actions\Urls;

use App\Models\Request;
use App\Models\Url;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsObject;
use Throwable;

final class TransferAnonymousUrlsToUser
{
    use AsJob, AsObject;

    /**
     * @param  string  $anonymousToken
     * @param  string  $userId
     * @return void
     *
     * @throws Throwable
     */
    public function handle(string $anonymousToken, string $userId): void
    {
        $values = [
            'user_id' => $userId,
            'anonymous_token' => null,
        ];

        DB::transaction(static function () use ($anonymousToken, $values): void {
            Url::query()
                ->whereNull('user_id')
                ->where('anonymous_token', $anonymousToken)
                ->update($values);

            Request::query()
                ->whereNull('user_id')
                ->where('anonymous_token', $anonymousToken)
                ->update($values);
        });
    }

    /**
     * @param  string  $userId
     * @return string
     */
    public function getJobUniqueId(string $userId): string
    {
        return "transfer-anonymous-urls-to-user-$userId";
    }
}
