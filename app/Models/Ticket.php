<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TicketMessage;

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