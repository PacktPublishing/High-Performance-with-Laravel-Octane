<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * This is a simulation of a
     * complex query that is time consuming
     *
     * @param  mixed  $query
     * @param  string  $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type)
            //->where('description', 'LIKE', '%something%')
            ->whereFullText('description', 'something')
            ->orderBy('date')->limit(5);
    }
}
