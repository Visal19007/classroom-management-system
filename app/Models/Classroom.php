<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model {
    protected $fillable = ['teacher_id','name','code','academic_year','description'];

    public function teacher(): BelongsTo {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany {
        return $this->belongsToMany(Student::class, 'class_student')
                    ->withPivot('enrolled_date','status')
                    ->withTimestamps();
    }

    public function attendances(): HasMany {
        return $this->hasMany(Attendance::class);
    }

    public function grades(): HasMany {
        return $this->hasMany(Grade::class);
    }

    public function notices(): HasMany {
        return $this->hasMany(Notice::class);
    }
}
