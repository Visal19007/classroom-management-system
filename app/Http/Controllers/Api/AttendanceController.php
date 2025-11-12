<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller {
    // Bulk save attendance
    public function storeBulk(Request $r) {
        $data = $r->validate([
            'classroom_id'=>'required|exists:classrooms,id',
            'date'=>'required|date',
            'records'=>'required|array',
            'records.*.student_id'=>'required|exists:students,id',
            'records.*.status'=>['required', Rule::in(['present','absent','late','excused'])],
            'records.*.remark'=>'nullable|string'
        ]);

        foreach ($data['records'] as $rec) {
            Attendance::updateOrCreate(
                ['classroom_id'=>$data['classroom_id'],'student_id'=>$rec['student_id'],'date'=>$data['date']],
                ['status'=>$rec['status'],'remark'=>$rec['remark'] ?? null]
            );
        }
        return response()->json(['message'=>'saved']);
    }

    public function getByClassAndDate($classroomId, $date = null) {
        $date = $date ? $date : now()->toDateString();
        $items = Attendance::where('classroom_id',$classroomId)->where('date',$date)->with('student')->get();
        return response()->json($items);
    }

    public function report(Request $r, $classroomId) {
        $start = $r->query('start', now()->startOfMonth()->toDateString());
        $end = $r->query('end', now()->endOfMonth()->toDateString());

        $summary = Attendance::where('classroom_id',$classroomId)
            ->whereBetween('date', [$start, $end])
            ->selectRaw('student_id, status, COUNT(*) as total')
            ->groupBy('student_id','status')
            ->get()
            ->groupBy('student_id')
            ->map(function($rows){
                $out = ['present'=>0,'absent'=>0,'late'=>0,'excused'=>0];
                foreach ($rows as $r) $out[$r->status] = $r->total;
                return $out;
            });

        return response()->json($summary);
    }
}
