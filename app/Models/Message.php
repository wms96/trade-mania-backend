<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id','to_user_id', 'content', 'seen', 'created_at', 'updated_at'];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
