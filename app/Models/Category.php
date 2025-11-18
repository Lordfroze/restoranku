<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['cat_name', 'description', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    // One-to-Many relationship with Item model | 1 category bisa memiliki banyak item
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
