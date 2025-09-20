<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use App\Models\PushSubscription;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\UserProfile;
use InvalidArgumentException;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PushSubscription> $pushSubscriptions
 */

class User extends Authenticatable implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'active_2fa',
        'google2fa_secret',
        'confirmed_2fa',
        'active',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'google2fa_secret' => 'string',
            'active' => 'boolean',
            'active_2fa' => 'boolean',
            'confirmed_2fa' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Push subscriptions associated with the user.
     */
    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function additionalData(): HasOne
    {
        return $this->hasOne(UserAdditionalData::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    public function currentProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'current_profile_id');
    }

    public function switchProfile(UserProfile $profile): void
    {
        if ($profile->user_id !== $this->id) {
            throw new InvalidArgumentException('The provided profile does not belong to this user.');
        }

        $this->profiles()
            ->where('id', '!=', $profile->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        if (! $profile->is_default) {
            $profile->is_default = true;
            $profile->save();
        }

        $this->forceFill(['current_profile_id' => $profile->id])->save();

        $roleName = $profile->role?->name;
        $this->syncRoles($roleName ? [$roleName] : []);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path) {
            return asset('storage/'.$this->avatar_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? '');
    }

    /**
     * Dispatch a web push notification to every registered subscription.
     */
    public function pushNotify(array $payload): void
    {
        $subscriptions = $this->pushSubscriptions;

        if ($subscriptions->isEmpty()) {
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => config('app.url'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        $webPush = new WebPush($auth);

        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->p256dh_key,
                    'authToken' => $subscription->auth_key,
                ]),
                json_encode($payload)
            );
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSubscriptionExpired()) {
                PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
            }
        }
    }
}
