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
use Illuminate\Support\Carbon;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string $id
 * @property string $source
 * @property ?User $user
 * @property Collection<Request> $requests
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
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
        'id',
        'user_id',
    ];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}
