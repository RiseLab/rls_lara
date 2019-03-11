<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
	    'category_id', 'brand', 'title', 'alias', 'source_link', 'info', 'description', 'photos', 'price', 'price_old', 'stock', 'available'
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }
}
