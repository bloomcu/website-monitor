<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['user_id', 'name', 'base_url'];

    protected $appends = ['pages_up_count', 'pages_down_count', 'is_healthy', 'total_pages_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function recentChecks()
    {
        return $this->hasMany(PageCheck::class);
    }

    protected function pagesUpCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->pages()->where('is_up', true)->count()
        );
    }

    protected function pagesDownCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->pages()->where('is_up', false)->count()
        );
    }

    protected function totalPagesCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->pages()->count()
        );
    }

    protected function isHealthy(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->pages()->where('is_up', false)->count() === 0
        );
    }
}
