<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model {
    protected $fillable = ['classroom_id','user_id','title','message','posted_at'];
    protected $dates = ['posted_at'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function user() { return $this->belongsTo(User::class); }
}
