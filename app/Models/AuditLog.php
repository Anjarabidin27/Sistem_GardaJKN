<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_type',
        'actor_id',
        'action',
        'entity_type',
        'entity_id',
        'changes_json',
    ];

    protected $casts = [
        'changes_json' => 'array',
    ];

    public function actor()
    {
        return $this->morphTo();
    }
}
