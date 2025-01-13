<?php

namespace App\Models;

use App\Traits\SelfCastingModel;
use Database\Factories\UrlFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string $id
 * @property string $source
 * @property User|null $user
 * @property Collection<Request> $requests
 */
class Url extends Model
{
    /** @use HasFactory<UrlFactory> */
    use HasFactory, HasUlids, KeepsDeletedModels, SelfCastingModel;

    protected $fillable = [
        'user_id',
        'source',
    ];

    protected $hidden = [
        'user_id',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'source' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
