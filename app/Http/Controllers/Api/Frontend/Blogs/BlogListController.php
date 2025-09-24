<?php

namespace App\Http\Controllers\Api\Frontend\Blogs;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CMS;

class BlogListController extends Controller
{
  public function index()
{
    // Fetch the blog banner
    $banner = CMS::where('section', 'blog-banner')->first();

    // Fetch paginated blog list
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
    $blogList = $blogs->getCollection()->transform(function ($blog) {
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

    // Prepare the full response with banner and blogs
    return response()->json([
        'status'  => 'true',
        'message' => 'Blogs retrieved successfully',
        'code'    => 200,
        'data'    => [
            'banner' => $banner ? [
                'id'        => $banner->id,
                'title'     => $banner->title,
                'sub_title' => $banner->sub_title,
                'image'     => $banner->image ? asset($banner->image) : null,
                'status'    => $banner->status,
            ] : null,
            'blogs'  => $blogList,
        ],
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
            return response()->json([
                'status' => 'false',
                'message' => 'Blog not found',
                'code' => 404,
                'data' => []
            ]);
        }

        // Format blog response
        $data = [
            'id'          => $blog->id,
            'title'       => $blog->title,
            'slug'        => $blog->slug,
            'content'     => $blog->content,
            'status'      => $blog->status,
            'image'       => $blog->image ? asset($blog->image) : url('default/logo.png'),
            'created_at'  => $blog->created_at ? $blog->created_at->format('j F Y') : null, // e.g. 3 September 2025
        ];

        return response()->json([
            'status' => 'true',
            'message' => 'Blog retrieved successfully',
            'code' => 200,
            'data' => $data
        ]);
    }
}
