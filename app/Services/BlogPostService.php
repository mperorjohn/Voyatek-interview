<?php
namespace App\Services;

use App\Models\Blog;
use App\Models\BlogPost;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

class BlogPostService {

    function __construct(
        private BlogPost $blogPost,
        private BlogService $blogService){}

    function create($blog_id, $blog_post) {
        try {
            $blog = $this->blogService->validateBlog($blog_id);
            $this->blogPost->create(
                ["content" => $blog_post["content"],
                "blog_id" => $blog->id
                ]
            );
        } catch(PDOException $e) {
            $this->databaseException($e);
        }
    }

    function getAllByBlogId($blog_id) {
        try {
            $blog = $this->blogService->validateBlog($blog_id);
            return $blog->posts;
        } catch(PDOException $e) {
            $this->databaseException($e);
        }
    }

    function getById($id) {
        try {
            $blogPost = $this->blogPost->with("likes", "comments")->find($id);
            if ($blogPost == null) {
                $this->invalidException();
            }
            return $blogPost;
        } catch (PDOException $e) {
            $this->databaseException($e);
        }
    }

    function update($id, $updatedPost) {
        try {
            $blogPost = $this->validateBlogPost($id);
            $blogPost->update(
                ["content" => $updatedPost["content"]]
            );
            return $blogPost;
        } catch(PDOException $e) {
            $this->databaseException($e);
        }   
    }

    function deleteById($id) {
        try {
            $blogPost = $this->validateBlogPost($id);
            $blogPost->delete();
        } catch (PDOException $e) {
            $this->databaseException($e);
        }
    }

    function like($id, $user_id) {
        try {
            $blogPost = $this->validateBlogPost($id);
            //validate user
            $user = User::find($user_id);
            if($user == null) {
                throw new HttpResponseException(
                    response()->json([
                        'status' => false,
                        'message' => 'Invalid User.'
                    ], 400)); 
            }

            //save Like
            Like::create([
                "blog_post_id" => $blogPost->id,
                "user_id" => $user->id
            ]);
        } catch (PDOException $e) {
            $this->databaseException($e);
        }
    }

    /**
     * Response during database downtimes
     * 
     * @param mixed $e
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    function databaseException($e) {
        Log::error($e->getMessage());
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Internal server error. Kindly try again.'
            ], 500));
    }

    function validateBlogPost($id) {
        $blogPost = $this->blogPost->find($id);
        if ($blogPost == null) {
            $this->invalidException();
        }
        return $blogPost;
    }

    function invalidException() {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Invalid Blog Post.'
            ], 400));
    }
}