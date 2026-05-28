<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStory extends Model
{
    protected $table = 'customer_stories';
    public $timestamps = true;
    protected $fillable = [
        'title', 'description', 'visitor_name', 'image'
    ];
}
