<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'website_id',
        'url',
        'is_up',
        'last_checked_at',
        'last_status_code',
        'last_response_time_ms',
    ];

    protected $casts = [
        'is_up' => 'boolean',
        'last_checked_at' => 'datetime',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function checks()
    {
        return $this->hasMany(PageCheck::class);
    }
}
