<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string|null $phone
 * @property string|null $company
 * @property string|null $country
 * @property string|null $city
 * @property string|null $address
 * @property string|null $postcode
 * @property bool $two_factor_enabled
 * @property string|null $two_factor_secret
 * @property int $failed_login_attempts
 * @property string|null $locked_until
 * @property bool $is_active
 * @property string|null $avatar
 * @property string|null $referral_code
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'company',
        'country',
        'city',
        'address',
        'postcode',
        'two_factor_enabled',
        'two_factor_secret',
        'failed_login_attempts',
        'locked_until',
        'is_active',
        'avatar',
        'referral_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'two_factor_enabled'   => 'boolean',
            'is_active'            => 'boolean',
            'locked_until'         => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Get the user's avatar URL, falling back to a default gravatar-style URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $hash = md5(strtolower(trim($this->email ?? '')));

        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
    }

    // -------------------------------------------------------------------------
    // Role helpers
    // -------------------------------------------------------------------------

    /** Returns true when the user is a super_admin. */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Returns true for any admin role (super_admin, billing_manager,
     * technical_admin, support_agent, sales_manager).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'billing_manager',
            'technical_admin',
            'support_agent',
            'sales_manager',
        ], true);
    }

    /** Returns true when the user is a regular customer. */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /** Returns true when the user is a billing_manager. */
    public function isBillingManager(): bool
    {
        return $this->role === 'billing_manager';
    }

    /** Returns true when the user is a technical_admin. */
    public function isTechnicalAdmin(): bool
    {
        return $this->role === 'technical_admin';
    }

    /** Returns true when the user is a support_agent. */
    public function isSupportAgent(): bool
    {
        return $this->role === 'support_agent';
    }

    /** Returns true when the user is a sales_manager. */
    public function isSalesManager(): bool
    {
        return $this->role === 'sales_manager';
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** @return HasMany<HostingAccount> */
    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }

    /** @return HasMany<Domain> */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /** @return HasMany<Invoice> */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /** @return HasMany<Payment> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** @return HasMany<SupportTicket> */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /** @return HasOne<Affiliate> */
    public function affiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class);
    }

    /** @return HasMany<Order> */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
