<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

class BlogService {

    function __construct(private Blog $blog){}

    function create($blog) {
        try {
            $this->blog->create($blog);
        } catch (PDOException $e) {
            Log::error($e->getMessage());
            $this->databaseException();
        }
    }

    function getAll() {
        try {
            return $this->blog->all();
        } catch(PDOException $e) {
            Log::error($e->getMessage());
            $this->databaseException();
        }
    }

    function getById($id) {
        try {
            $blog = $this->blog->with('posts')->find($id);
            if ($blog == null) {
                $this->invalidException();
            }
            return $blog;

        } catch(PDOException $e) {
            Log::error($e->getMessage());
            $this->databaseException();
        }
    }

    function update($id, $updatedBlog) {
        try {
            $blog = $this->validateBlog($id);
            $blog->update($updatedBlog);
            return $blog;

        } catch (PDOException $e) {
            Log::error($e->getMessage());
            $this->databaseException();
        }
    } 

    function deleteById($id) {
        try {
            $blog = $this->validateBlog($id);
            $blog->delete();
        }catch(PDOException $e) {
            Log::error($e->getMessage());
            $this->databaseException();
        }
    }

    /**
     * Response during database downtimes
     * 
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return never
     */
    function databaseException() {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Internal server error. Kindly try again.'
            ], 500));
    }

    function invalidException() {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Invalid Blog.'
            ], 400));
    }

    function validateBlog($id) {
        $blog = $this->blog->find($id);
        if ($blog == null) {
            $this->invalidException();
        }
        return $blog;
    }
    
}