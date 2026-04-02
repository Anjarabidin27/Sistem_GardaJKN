<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpjsKelilingParticipant extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'jam_mulai' => 'string',
        'jam_selesai' => 'string',
    ];

    public function activity()
    {
        return $this->belongsTo(BpjsKeliling::class, 'bpjs_keliling_id');
    }
}
