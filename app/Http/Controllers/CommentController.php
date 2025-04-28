<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);
        /*if($request->ajax())
        {

        }*/
        return view('comment.index',['comments' => Comment::orderBy('id','desc')->limit(100)->get()]);
    }

    public function destroy(Comment $comment)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);
        $comment->delete();
        return redirect(route('comments'))->with('success', 'Registro eliminado');
    }
}
