<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title','content'];
    
    public function user(){

		return $this->belongsTo(user::class);
    }
    
    public function tags()
    {
		return $this->belongstomany(Tag::class);    
    }
}