<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

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

        $posts = $this->postRepository->index($perpage, $condition, $order);

        if($posts->isEmpty()){
            return response()->json(['message' => 'No Content'], 204);
        };
        return response()->json($posts);
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
    public function store(Request $request)
    {

        //對要求進行驗證
        $validatedData  = $request->validate([
            'title' => 'required|max:255|unique:posts',
            'content' => 'required'
        ]);

        //手動增加'user_id' => 1'的數據
        $validatedData['user_id'] = 1;
        //從驗證過的要求中取出所有數據並存儲
        $post = $this->postRepository->store($validatedData);


        // $request->validated();
        if (empty($post)) {
            return response()->json(['message' => 'created is Not Succese'], 500);
        }

        // return response()->json(['message' => 'Post not created'], 500);
        //成功回傳成功代碼
        return response()->json(["id" => $post->id]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post = $this->postRepository->findOrFail($id);

        if ($post->isEmpty()) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */

    //在後端不會返回視圖
    public function edit(string $id)
    {
        //
        // $post = $this->postRepository->findOrFail($id);

        // if (! $post) {
        //     return redirect()->route('posts.index');
        // }

        // return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        $validata = $request->validate([
            'title' => 'bail|required|max:255',
            'content' => 'required'
        ]);

        //回傳執行別為boolean
        $updateresult = $this->postRepository->update($validata, $id);

        if (!$updateresult){
            return response()->json(['message' => 'Post not updated'], 500);
        }

        return response()->json(['message' => 'Post updated'], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $destroyresult = $this->postRepository->delete($id);

        if ($destroyresult) {
            return response()->json(['message' => 'Post deleted'], 200);
        }

        return response()->json(['message' => 'Post not deleted'], 500);
    }


}
