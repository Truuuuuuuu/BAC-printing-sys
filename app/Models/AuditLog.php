<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class AuditLog extends Model
{

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id', 'action', 'auditable_type', 'auditable_id', 'metadata',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivityAttribute(): string
    {
        $model = $this->auditable_type ? class_basename($this->auditable_type) : '';
        $name  = $this->metadata['name'] ?? "#{$this->auditable_id}";

        return match($this->action) {
            'project.created' => "Created project <strong>{$name}</strong>",
            'project.updated' => "Updated project <strong>{$name}</strong>",
            'project.deleted' => "Deleted project <strong>{$name}</strong>",
            'bid.created' => "Created bid for <strong>{$name}</strong>",
            'bid.updated' => "Updated bid for <strong>{$name}</strong>",
            'bid.deleted' => "Deleted bid for <strong>{$name}</strong>",
            'auth.login'      => "Logged in",
            'auth.login_failed' => "Failed login attempt",
            default           => $this->action,
        };
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('action', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }

        return $query;
    }
    
}
