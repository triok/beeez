<?php

namespace App\Models;

use App\Models\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use Favoritable;

    protected $fillable = ['organization_id', 'name', 'specialization', 'responsibilities', 'conditions', 'requirements'];

    protected $dates = ['published_at'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope a query to only include published vacancies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }
}