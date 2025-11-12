<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller {
    public function index(Request $r) {
        $q = Notice::query()->with('user');
        if ($r->has('classroom_id')) $q->where('classroom_id',$r->classroom_id);
        return response()->json($q->orderBy('posted_at','desc')->paginate(20));
    }

    public function store(Request $r) {
        $data = $r->validate(['classroom_id'=>'nullable|exists:classrooms,id','title'=>'required','message'=>'required']);
        $data['user_id'] = $r->user()->id;
        $data['posted_at'] = now();
        $n = Notice::create($data);
        return response()->json($n,201);
    }
}
