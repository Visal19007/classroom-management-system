<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model {
    use SoftDeletes;
    protected $fillable = ['student_code','first_name','last_name','gender','dob','contact','address'];

    public function classrooms() {
        return $this->belongsToMany(Classroom::class, 'class_student')->withPivot('enrolled_date','status')->withTimestamps();
    }

    public function attendances() { return $this->hasMany(Attendance::class); }
    public function grades() { return $this->hasMany(Grade::class); }

    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }
}
