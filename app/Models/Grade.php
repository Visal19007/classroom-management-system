<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model {
    protected $fillable = ['classroom_id','student_id','subject','score','grade','remark'];

    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
