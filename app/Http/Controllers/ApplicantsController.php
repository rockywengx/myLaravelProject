<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ApplicantsRequest;
use App\Repository\ApplicantsRepository;

class ApplicantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perpage = $request->input('perpage', 10);
        $order = $request->input('orderby', [ 'id' => 'desc' ]);

        //除了 perpage 以外的所有條件
        $condition = $request->except(['perpage', 'filter']);

        // $condition = $request->only(['title', 'content']);

        $posts = $this->ApplicantsRepository->index($perpage, $condition, $order);

        if(empty($posts)){
            return response()->json(['message' => 'No Content'], 204);
        };
        return PostResource::collection($posts);
        // return view('posts.index', compact('posts'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApplicantsRequest  $request)
    {

        //對要求進行驗證
        // $validatedData  = $request->validate([
        //     'title' => 'required|max:255|unique:posts',
        //     'content' => 'required'
        // ]);

        //手動增加'user_id' => 1'的數據
        $request['user_id'] = 1;
        //從驗證過的要求中取出所有數據並存儲
        $post = $this->ApplicantsRepository->store($request->all());
        

        // $request->validated();
        if (empty($post)) {
            return response()->json(['message' => 'created is Not Succese'], 500);
        }

        // return response()->json(['message' => 'Post not created'], 500);
        //成功回傳成功代碼
        // return response()->json(["id" => $post->id]);
        return response()->json($post);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
