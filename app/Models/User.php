<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole;
use App\Models\Notification;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $fillable = [
    'name',
    'email',
    'password',
    'role'
];
public function createdTickets()
{
    return $this->hasMany(Ticket::class, 'customer_id');
}

public function assignedTickets()
{
    return $this->hasMany(Ticket::class, 'agent_id');
}
public function isAdmin(): bool
{
    return $this->role === UserRole::ADMIN->value;
}

public function isSupervisor(): bool
{
    return $this->role === UserRole::SUPERVISOR->value;
}

public function isAgent(): bool
{
    return $this->role === UserRole::AGENT->value;
}

public function isCustomer(): bool
{
    return $this->role === UserRole::CUSTOMER->value;
}
public function notifications()
{
    return $this->hasMany(Notification::class);
}
}

