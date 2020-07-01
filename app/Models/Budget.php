<?php

namespace App\Models;

use App\Repositories\BudgetRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    // Accessors
    public function getSpentAttribute()
    {
        return (new BudgetRepository())->getSpentById($this->id);
    }
}
