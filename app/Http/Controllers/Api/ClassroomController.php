<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClassroomController extends Controller {
    use AuthorizesRequests;

    public function index(Request $r) {
        $user = $r->user();
        $q = Classroom::where('teacher_id', $user->id)
                      ->withCount('students')
                      ->with('students');
        return response()->json($q->paginate(15));
    }

    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|string',
            'code'=>'nullable|string|unique:classrooms,code',
            'academic_year'=>'nullable|string',
            'description'=>'nullable|string'
        ]);
        $data['teacher_id'] = $r->user()->id;
        $classroom = Classroom::create($data);
        return response()->json($classroom,201);
    }

    public function show(Classroom $classroom, Request $r) {
        $this->authorize('view',$classroom);
        $classroom->load('students','attendances','grades','notices');
        return response()->json($classroom);
    }

    public function update(Request $r, Classroom $classroom) {
        $this->authorize('update',$classroom);
        $data = $r->validate([
            'name'=>'sometimes|required',
            'code'=>"sometimes|nullable|unique:classrooms,code,{$classroom->id}",
            'academic_year'=>'nullable',
            'description'=>'nullable'
        ]);
        $classroom->update($data);
        return response()->json($classroom);
    }

    public function destroy(Classroom $classroom) {
        $this->authorize('delete',$classroom);
        $classroom->delete();
        return response()->json(null,204);
    }
}
