<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TicketMessage;
use App\States\Ticket\TicketState;
use App\States\Ticket\OpenState;
use App\States\Ticket\AssignedState;
use App\States\Ticket\InProgressState;
use App\States\Ticket\ResolvedState;
use App\States\Ticket\ClosedState;
use App\States\Ticket\ReopenedState;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'customer_id',
        'agent_id',
        'category_id',
        'priority_id',
        'status'
    ];
    protected $casts = [
        'sla_deadline' => 'datetime',
    ];

    public function getState(): TicketState
    {
        return match ($this->status) {
            'open' => new OpenState($this),
            'assigned' => new AssignedState($this),
            'in_progress' => new InProgressState($this),
            'resolved' => new ResolvedState($this),
            'closed' => new ClosedState($this),
            'reopened' => new ReopenedState($this),
            default => new OpenState($this),
        };
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }
public function isExpired(): bool
{
    if ($this->sla_deadline === null) {
        return false;
    }

    return now()->greaterThan($this->sla_deadline);
}

public function remainingHours(): int
{
    if ($this->sla_deadline === null) {
        return 0;
    }

    if ($this->isExpired()) {
        return 0;
    }

    return now()->diffInHours($this->sla_deadline);
}
public function activityLogs()
{
    return $this->hasMany(ActivityLog::class);
}
}