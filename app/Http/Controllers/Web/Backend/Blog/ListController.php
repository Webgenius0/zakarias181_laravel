<?php

namespace App\Http\Controllers\Web\Backend\Blog;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ListController extends Controller
{
    public function dashboard(Request $request)
    {
       
        if ($request->ajax()) {
            $data = Blog::where('status', 'active')->orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    $url = asset($data->image);
                    return '<img src="' . $url . '" border="0" width="40" class="img-rounded" align="center" />';
                })
               
                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">

                                <a href="#" type="button" onclick="goToOpen(' . $data->id . ')" class="btn btn-success fs-14 text-white delete-icn" title="View">
                                    <i class="fe fe-eye"></i>
                                </a>

                            </div>';
                })
                ->rawColumns(['image'])
                ->make();
        }
        return view("backend.layouts.dashboard");
    }

    public function show(int $id)
    {
        $blog = Blog::where('status', 'active')->where('id', $id)->first();
        return view('backend.layouts.show', compact('blog'));
    }

    public function status(int $id): JsonResponse
    {
        $data = Blog::findOrFail($id);
        if (!$data) {
            return response()->json([
                'status' => 't-error',
                'message' => 'Item not found.',
            ]);
        }
        $data->status = $data->status === 'accept' ? 'reject' : 'accept';
        $data->save();
        return response()->json([
            'status' => 't-success',
            'message' => 'Your action was successful!',
        ]);
    }
}
