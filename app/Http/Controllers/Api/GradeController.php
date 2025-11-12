<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller {
    public function storeOrUpdate(Request $r) {
        $data = $r->validate([
            'classroom_id'=>'required|exists:classrooms,id',
            'student_id'=>'required|exists:students,id',
            'subject'=>'nullable|string',
            'score'=>'nullable|numeric',
            'grade'=>'nullable|string',
            'remark'=>'nullable|string'
        ]);
        $grade = Grade::updateOrCreate(
            ['classroom_id'=>$data['classroom_id'],'student_id'=>$data['student_id'],'subject'=>$data['subject'] ?? null],
            ['score'=>$data['score'] ?? null,'grade'=>$data['grade'] ?? null,'remark'=>$data['remark'] ?? null]
        );
        return response()->json($grade);
    }

    public function getClassGradebook($classroomId) {
        $grades = Grade::where('classroom_id',$classroomId)->with('student')->get();
        return response()->json($grades);
    }
}
