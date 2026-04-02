<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilParticipant extends Model
{
    protected $guarded = ['id'];

    public function activity()
    {
        return $this->belongsTo(Pil::class, 'pil_id');
    }
}
