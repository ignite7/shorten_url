<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\SelfCastingModel;
use Database\Factories\UrlFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string $id
 * @property string $source
 * @property ?User $user
 * @property Collection<int, Request> $requests
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
final class Url extends Model
{
    /**
     * @use HasFactory<UrlFactory>
     *
     * @phpstan-use SelfCastingModel<$this>
     */
    use HasFactory, HasUlids, KeepsDeletedModels, SelfCastingModel;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'source',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'user_id',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Request, $this>
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'source' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
