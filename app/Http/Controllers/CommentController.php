<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $request-> validate([
            'post_id' => 'required',
            'body' => 'required|string|max:255'
        ]);

        $comment = new Comment();

        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user()->id;
        $comment->body = $request->body;

        if($comment->save()){
            return response()->json([
                'message' => 'Comment successful',
                'comment' => $comment->load('user')
            ], 201);
        }else{
            return response()->json([
                'message' => 'Some error occurred, please try again'
            ], 500);
        }
    }


    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

    $request->validate([
        'body' => 'required|string|max:255',
    ]);

    $comment->body = $request->body;

    if ($comment->save()) {
        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment,
        ], 200);
    } else {
        return response()->json([
            'message' => 'Error occurred while updating the comment'
        ], 500);
    }
}

    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);

    if ($comment->delete()) {
        return response()->json([
            'message' => 'Comment deleted successfully',
        ], 200);
    } else {
        return response()->json([
            'message' => 'Error occurred while deleting the comment'
        ], 500);
    }
}

}
