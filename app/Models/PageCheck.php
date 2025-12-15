<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageCheck extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'status',
        'status_code',
        'response_time_ms',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
