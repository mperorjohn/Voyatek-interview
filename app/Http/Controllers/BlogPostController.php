<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogPostRequest;
use App\Services\BlogPostService;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{ 
    function __construct(private BlogPostService $service) {}

    function create($blog_id, BlogPostRequest $request) {
        $validated = $request->validated();
        $this->service->create($blog_id, $validated);

        return response()->json([
            'status' => true,
            'message' => "Created."
        ]);

    }

    function getAllByBlogId($blog_id) {
        $posts = $this->service->getAllByBlogId($blog_id);
        return response()->json([
            'status' => true,
            'message' => "Ok.",
            'data' => $posts
        ]);
    }

    // Create an endpoint to fetch details of a specific post and its likes and comments.
    function getById($id) {
        $post = $this->service->getById($id);
        return response()->json([
            'status' => true,
            'message' => "Ok.",
            'data' => $post
        ]);
    }

    //Create an endpoint to update an existing post
    function update($id, BlogPostRequest $request) {
        $post = $this->service->update($id, $request);
        return response()->json([
            'status' => true,
            'message' => "Ok.",
            'data' => $post
        ]);
    }

    function deleteById($id) {
        $this->service->deleteById($id);
        return response()->json([
            'status' => true,
            'message' => "Ok."
        ]);
    }

    function like($id, $user_id) {
        $this->service->like($id, $user_id);
        return response()->json([
            'status' => true,
            'message' => "Liked."
        ], 201);
    }

    function comment($id) {

    }
}
