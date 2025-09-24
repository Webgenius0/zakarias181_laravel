<?php

namespace App\Http\Controllers\Api\Frontend\Blogs;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogListController extends Controller
{
    public function index()
{
    // Paginate blogs, 6 per page
    $blogs = Blog::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->paginate(6);

    if ($blogs->isEmpty()) {
        return response()->json([
            'status' => 'false',
            'message' => 'No blogs found',
            'code' => 404,
            'data' => [],
            'pagination' => null
        ]);
    }

    // Transform each blog item
    $blogs->getCollection()->transform(function ($blog) {
        return [
            'id'         => $blog->id,
            'title'      => $blog->title,
            'slug'       => $blog->slug,
            'content'    => $blog->content,
            'image'      => $blog->image ? asset($blog->image) : url('default/logo.png'),
            'status'     => $blog->status,
            'created_at' => $blog->created_at->format('j M Y'),
        ];
    });

    return response()->json([
        'status'    => 'true',
        'message'   => 'Blogs retrieved successfully',
        'code'      => 200,
        'data'      => $blogs->items(),
        'pagination' => [
            'total'         => $blogs->total(),
            'per_page'      => $blogs->perPage(),
            'current_page'  => $blogs->currentPage(),
            'last_page'     => $blogs->lastPage(),
            'next_page_url' => $blogs->nextPageUrl(),
            'prev_page_url' => $blogs->previousPageUrl(),
        ]
    ]);
}



    // blog details
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['status' => 'false', 'message' => 'Blog not found', 'code' => 404, 'data' => []]);
        }

        // Map image to full URL
        $blog->image = $blog->image ? asset($blog->image) : url('default/logo.png');

        return response()->json(['status' => 'true', 'message' => 'Blog retrieved successfully', 'code' => 200, 'data' => $blog]);
    }
}
