<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * A read-shadow of the Identity service's user, synced on every SSO login.
 * This app never stores a password — authentication happens in Identity.
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $current_role
 * @property string|null $tenant_id
 * @property array<int, array{slug: string, name: string, base_url: string, as: string}>|null $accessible_apps
 * @property Carbon|null $identity_synced_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['id', 'name', 'email', 'current_role', 'tenant_id', 'accessible_apps', 'identity_synced_at'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'accessible_apps' => 'array',
            'identity_synced_at' => 'datetime',
        ];
    }
}
