<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Item extends Model
{
    //
    use SoftDeletes, HasFactory; // menggunakan soft delete dan factory karena Item menggunakan factory ItemFactory.php

    protected $fillable = ['name', 'description', 'price', 'category_id', 'img', 'is_active', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class); // setiap item hanya memiliki 1 category
    }
}
