<?php

declare(strict_types=1);

use App\Actions\Urls\TransferAnonymousUrlsToUser;
use App\Models\Request;
use App\Models\Url;
use App\Models\User;

describe('as job',
    /**
     * @throws Throwable
     */
    function (): void {
        it('can transfer anonymous url to user if user url is nullable', function (): void {
            $url = Url::factory()->withoutUser()->create();
            $request = Request::factory()->withoutUser()->create([
                'url_id' => $url->id,
                'anonymous_token' => $url->anonymous_token,
            ]);
            $user = User::factory()->regularRole()->create();

            Queue::fake();

            TransferAnonymousUrlsToUser::dispatch($url->anonymous_token, $user->id);

            TransferAnonymousUrlsToUser::assertPushed(1, static function (TransferAnonymousUrlsToUser $job, array $args) use ($url, $user): bool {
                $job->handle($url->anonymous_token, $user->id);

                return $args[0] === $url->anonymous_token && $args[1] === $user->id;
            });

            $this->assertDatabaseMissing(Url::class, [
                'id' => $url->id,
                'user_id' => null,
                'anonymous_token' => $url->anonymous_token,
            ]);
            $this->assertDatabaseMissing(Request::class, [
                'id' => $request->id,
                'user_id' => null,
                'anonymous_token' => $url->anonymous_token,
            ]);
            $this->assertDatabaseHas(Url::class, [
                'id' => $url->id,
                'user_id' => $user->id,
                'anonymous_token' => null,
            ]);
            $this->assertDatabaseHas(Request::class, [
                'id' => $request->id,
                'user_id' => $user->id,
                'anonymous_token' => null,
            ]);
        });

        it('cannot transfer anonymous url to user if user url is not nullable', function (): void {
            $anonymousToken = fake()->uuid();
            $url = Url::factory()->create();
            $request = Request::factory()->create([
                'url_id' => $url->id,
            ]);
            $user = User::factory()->regularRole()->create();

            Queue::fake();

            TransferAnonymousUrlsToUser::dispatch($anonymousToken, $user->id);

            TransferAnonymousUrlsToUser::assertPushed(1, static function (TransferAnonymousUrlsToUser $job, array $args) use ($anonymousToken, $user): bool {
                $job->handle($anonymousToken, $user->id);

                return $args[0] === $anonymousToken && $args[1] === $user->id;
            });

            $this->assertDatabaseMissing(Url::class, [
                'id' => $url->id,
                'user_id' => $user->id,
                'anonymous_token' => $anonymousToken,
            ]);
            $this->assertDatabaseMissing(Request::class, [
                'id' => $request->id,
                'user_id' => $user->id,
                'anonymous_token' => $anonymousToken,
            ]);
            $this->assertDatabaseHas(Url::class, [
                'id' => $url->id,
                'user_id' => $url->user_id,
                'anonymous_token' => $url->anonymous_token,
            ]);
            $this->assertDatabaseHas(Request::class, [
                'id' => $request->id,
                'user_id' => $request->user_id,
                'anonymous_token' => $url->anonymous_token,
            ]);
        });
    });

describe('as action', function (): void {
    it('can transfer anonymous url to user if user url is nullable', function (): void {
        $url = Url::factory()->withoutUser()->create();
        $request = Request::factory()->withoutUser()->create([
            'url_id' => $url->id,
            'anonymous_token' => $url->anonymous_token,
        ]);
        $user = User::factory()->regularRole()->create();

        TransferAnonymousUrlsToUser::run($url->anonymous_token, $user->id);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'user_id' => null,
            'anonymous_token' => $url->anonymous_token,
        ]);
        $this->assertDatabaseMissing(Request::class, [
            'id' => $request->id,
            'user_id' => null,
            'anonymous_token' => $url->anonymous_token,
        ]);
        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'user_id' => $user->id,
            'anonymous_token' => null,
        ]);
        $this->assertDatabaseHas(Request::class, [
            'id' => $request->id,
            'user_id' => $user->id,
            'anonymous_token' => null,
        ]);
    });

    it('cannot transfer anonymous url to user if user url is not nullable', function (): void {
        $anonymousToken = fake()->uuid();
        $url = Url::factory()->create();
        $request = Request::factory()->create([
            'url_id' => $url->id,
        ]);
        $user = User::factory()->regularRole()->create();

        TransferAnonymousUrlsToUser::run($anonymousToken, $user->id);

        $this->assertDatabaseMissing(Url::class, [
            'id' => $url->id,
            'user_id' => $user->id,
            'anonymous_token' => $anonymousToken,
        ]);
        $this->assertDatabaseMissing(Request::class, [
            'id' => $request->id,
            'user_id' => $user->id,
            'anonymous_token' => $anonymousToken,
        ]);
        $this->assertDatabaseHas(Url::class, [
            'id' => $url->id,
            'user_id' => $url->user_id,
            'anonymous_token' => $url->anonymous_token,
        ]);
        $this->assertDatabaseHas(Request::class, [
            'id' => $request->id,
            'user_id' => $request->user_id,
            'anonymous_token' => $url->anonymous_token,
        ]);
    });
});

it('has unique job id', function (): void {
    $user = User::factory()->create();
    $job = new TransferAnonymousUrlsToUser();
    $jobUniqueId = $job->getJobUniqueId($user->id);

    expect($jobUniqueId)->toBe("transfer-anonymous-urls-to-user-$user->id");
});
