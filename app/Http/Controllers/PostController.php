<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::where('user_id', $request->user()->id)->get();
        return response()->json($posts);
    }

    public function getPublishedPosts(Request $request)
    {
        // Get query parameters for search and filter
        $searchTitle = $request->query('title');   // Search by title
        $filterStatus = $request->query('status'); // Filter by status (published, draft, etc.)

        // Build the query
        $query = Post::query()
            ->with([
                'author:id,name',  // Load author with id and name fields only
                'comments' => function ($query) {
                    $query->select('id', 'post_id', 'user_id', 'body', 'created_at')
                          ->with('commenter:id,name'); // Load commenter with id and name fields
                }
            ]);

        // Apply title search if the 'title' query parameter exists
        if ($searchTitle) {
            $query->where('title', 'like', '%' . $searchTitle . '%');
        }

        // Apply status filter if the 'status' query parameter exists
        if ($filterStatus) {
            $query->where('status', $filterStatus); // e.g., published, draft
        }

        // Paginate the results
        $posts = $query->paginate(5);

        // Return the paginated results as a JSON response
        return response()->json($posts, 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'visibility' => 'required|in:public,followers',
        'status' => 'required|in:published,draft',
        'title' => 'required|string|max:255',
        'body' => 'required|string',
    ]);

    // Create a new post instance
    $post = new Post();

    // Set post properties
    $post->user_id = $request->user()->id;
    $post->title = $request->title;
    $post->visibility = $request->visibility;
    $post->status = $request->status;
    $post->body = $request->body;

    // Save the post and return response
    if($post->save()){
        return response()->json($post, 200);
    } else {
        return response()->json([
            'message' => 'Some error occurred. Please try again.'
        ], 500);
    }
}

    public function show(Post $post)
    {
        if ($post->user_id != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }



    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        // Validate the request data
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:published,draft',
        ]);

        // Update post properties if they are present in the request
        $post->title = $request->has('title') ? $request->title : $post->title;
        $post->body = $request->has('body') ? $request->body : $post->body;
        $post->status = $request->has('status') ? $request->status : $post->status;

        // Save the updated post and return response
        if ($post->save()) {
            return response()->json($post);
        } else {
            return response()->json(['message' => 'Some error occurred. Please try again.'], 500);
        }
    }


    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->delete()) {
            return response()->json(['message' => 'Post deleted successfully']);
        } else {
            return response()->json(['message' => 'Some error occurred. Please try again.'], 500);
        }
    }
}
