<?php

namespace App\Models;

use App\Traits\SelfCastingModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $method
 * @property string $uri
 * @property Collection $query
 * @property Collection $headers
 * @property Collection $body
 * @property string $ip_address
 * @property string $user_agent
 * @property Url $url
 * @property User|null $user
 */
class Request extends Model
{
    /** @use HasFactory<\Database\Factories\RequestFactory> */
    use HasFactory, HasUlids, SelfCastingModel, SoftDeletes;

    protected $fillable = [
        'method',
        'uri',
        'query',
        'headers',
        'body',
        'ip_address',
        'user_agent',
    ];

    protected $hidden = [
        'url_id',
        'user_id',
    ];

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
            'body' => 'collection',
            'ip_address' => 'string',
            'user_agent' => 'string',
        ];
    }

    public function url(): BelongsTo
    {
        return $this->belongsTo(Url::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
