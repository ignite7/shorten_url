<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\SelfCastingModel;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property string $id
 * @property string $role
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property Collection<int, Url> $urls
 * @property Collection<int, Request> $requests
 * @property string $password
 * @property ?string $remember_token
 * @property ?Carbon $email_verified_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property-read string $fullName
 */
final class User extends Authenticatable
{
    /**
     * @use HasFactory<UserFactory>
     *
     * @phpstan-use SelfCastingModel<$this>
     */
    use HasFactory, HasUlids, KeepsDeletedModels, Notifiable, SelfCastingModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'updated_at',
    ];

    /**
     * @return HasMany<Url, $this>
     */
    public function urls(): HasMany
    {
        return $this->hasMany(Url::class);
    }

    /**
     * @return HasMany<Request, $this>
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => 'string',
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'remember_token' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn (): string => "$this->first_name $this->last_name"
        );
    }
}
