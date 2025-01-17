<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\SelfCastingModel;
use Database\Factories\RequestFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string $id
 * @property string $method
 * @property string $uri
 * @property Collection<int, string> $query
 * @property Collection<int, string> $headers
 * @property string $body
 * @property string $ip_address
 * @property string $user_agent
 * @property Url $url
 * @property ?User $user
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
final class Request extends Model
{
    /**
     * @use HasFactory<RequestFactory>
     *
     * @phpstan-use SelfCastingModel<$this>
     */
    use HasFactory, HasUlids, KeepsDeletedModels, SelfCastingModel;

    protected $fillable = [
        'url_id',
        'user_id',
        'method',
        'uri',
        'query',
        'headers',
        'body',
        'ip_address',
        'user_agent',
    ];

    protected $hidden = [
        'id',
        'url_id',
        'user_id',
    ];

    /**
     * @return BelongsTo<Url, $this>
     */
    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'method' => 'string',
            'uri' => 'string',
            'query' => 'collection',
            'headers' => 'collection',
            'body' => 'string',
            'ip_address' => 'string',
            'user_agent' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
