<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller {
    public function index(Request $r) {
        $q = Student::query()->with('classrooms');
        if ($r->has('q')) $q->whereRaw("concat(first_name,' ',last_name) like ?", ['%'.$r->q.'%']);
        return response()->json($q->paginate(25));
    }

    public function store(Request $r) {
        $data = $r->validate(['student_code'=>'nullable|unique:students,student_code','first_name'=>'required','last_name'=>'required','gender'=>'nullable','dob'=>'nullable|date','contact'=>'nullable','address'=>'nullable']);
        $student = Student::create($data);
        if ($r->has('classroom_id')) {
            $class = Classroom::find($r->classroom_id);
            if ($class) $student->classrooms()->attach($class->id,['enrolled_date'=>now()]);
        }
        return response()->json($student,201);
    }

    public function show(Student $student) {
        $student->load('classrooms','attendances','grades');
        return response()->json($student);
    }

    public function update(Request $r, Student $student) {
        $data = $r->validate(['student_code'=>"nullable|unique:students,student_code,{$student->id}",'first_name'=>'sometimes|required','last_name'=>'sometimes|required','gender'=>'nullable','dob'=>'nullable|date','contact'=>'nullable','address'=>'nullable']);
        $student->update($data);
        return response()->json($student);
    }

    public function destroy(Student $student) {
        $student->delete();
        return response()->json(null,204);
    }

    // enroll/unenroll endpoints:
    public function enroll(Request $r, Student $student) {
        $data = $r->validate(['classroom_id'=>'required|exists:classrooms,id','enrolled_date'=>'nullable|date']);
        $student->classrooms()->syncWithoutDetaching([$data['classroom_id'] => ['enrolled_date'=>$data['enrolled_date'] ?? now()]]);
        return response()->json(['message'=>'enrolled']);
    }

    public function unenroll(Request $r, Student $student) {
        $data = $r->validate(['classroom_id'=>'required|exists:classrooms,id']);
        $student->classrooms()->detach($data['classroom_id']);
        return response()->json(['message'=>'unenrolled']);
    }
}
