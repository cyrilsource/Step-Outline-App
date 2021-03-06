<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'title', 'description', 'order', 'type', 'color', 'color_id',
    ];

    public function color() {

    	return $this->belongsTo('App\Color');
    }
}
