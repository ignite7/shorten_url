<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\SelfCastingModel;
use Database\Factories\EmailVerificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property int $id
 * @property string $email
 * @property string $verification_code
 * @property Carbon $expires_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
final class EmailVerification extends Model
{
    /**
     * @use HasFactory<EmailVerificationFactory>
     *
     * @phpstan-use SelfCastingModel<$this>
     */
    use HasFactory, KeepsDeletedModels, SelfCastingModel;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'verification_code',
        'expires_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'updated_at',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'email' => 'string',
            'verification_code' => 'hashed',
            'expires_at' => 'datetime',
        ];
    }
}
