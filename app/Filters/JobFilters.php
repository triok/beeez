<?php

namespace App\Filters;

class JobFilters extends QueryFilter
{
    public function job()
    {
        $job = $this->request->job;

        return $this->builder->orWhere('id', $job)
            ->orWhere('name','like','%'.$job.'%')
            ->orWhere('desc','like','%'.$job.'%');
    }

    public function tag()
    {
        return $this->builder->whereHas('tag' , function($query) {
            $query->where('value', $this->request->tag);
        });
    }
}