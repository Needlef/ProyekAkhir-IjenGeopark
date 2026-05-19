<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $table = 'artikel';
    public $timestamps = false;
    protected $fillable = [
        'judul', 'label', 'ringkasan', 'konten', 'gambar', 
        'css_width', 'css_height', 'css_left', 'css_top'
    ];
}
