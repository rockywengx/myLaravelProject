<?php

namespace App\Http\Controllers;

use App\Repository\PostRepository;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseField;
use Knuckles\Scribe\Attributes\ResponseFromFile;

/**
 * @group Post 部落格
 */

class PostController extends Controller
{

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * 顯示所有文章
     * 能自訂每頁顯示的筆數
     * 初始每頁10筆的方式顯示
     * 能增加條件查詢
     * 能自訂欄位降冪或升冪排序
     * 初始為id降冪排序
     */
    #[QueryParam(name: 'filter', description: '條件查詢', type: 'string', example: 'hello')]
    #[QueryParam(name: 'orderby', description: '欄位排序', type: 'object', example: '{"id": "desc"}')]
    #[QueryParam(name: 'perpage', description: '每頁顯示的筆數', type: 'int', example: 10)]
    #[QueryParam(name: 'page', description:'頁數', type:'int', example:1)]
    #[Response( status: 404, description: 'No Content' )]
    #[Response( status: 302, description:'Redirect' )]
    #[ResponseFromFile(status: 200, path: './posts/index.json')]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: 'Hello World')]
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
     * 新增文章
     */
    #[QueryParam(name: 'title', description: '文章標題', type: 'string', example: 'Hello World')]
    #[QueryParam(name: 'content', description: '文章內容', type: 'string', example: '早安')]
    #[Response( status: 200, description: '新增失敗' )]
    #[ResponseFromFile(status: 201, path: './posts/store.json')]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: '早上好')]
    public function store(Request  $request)
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
     * 回傳指定文章
     */
    #[QueryParam(name: 'id', description: '文章ID', type: 'int', example: 1)]
    #[Response( status: 200, description: 'No Content' )]
    #[ResponseFromFile(status: 200, path: './posts/show.json')]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: '早上好')]
    public function show(string $id)
    {
        //
        $post = $this->postRepository->findOrFail($id);

        // if (empty($post)) {
        //     return response()->json(['message' => 'Post not found'], 404);
        // }

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
     * 修改指定內容
     */
    #[QueryParam(name: 'id', description: '文章ID', type: 'int', example: 1)]
    #[QueryParam(name: 'title', description: '文章標題', type: 'string', example: 'Hello World')]
    #[QueryParam(name: 'content', description: '文章內容', type: 'string', example: '早上好')]
    #[Response( status: 200, description: '修改失敗' )]
    #[ResponseFromFile(status: 200, path: './posts/update.json')]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: '早上好')]
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
     * 刪除指定文章
     */
    #[QueryParam(name: 'id', description: '文章ID', type: 'int', example: 1)]
    #[Response( status: 200, description: '刪除失敗' )]
    #[ResponseFromFile(status: 200, path: './posts/destroy.json')]
    public function destroy(string $id)
    {
        //
        $destroyresult = $this->postRepository->delete($id);

        // if (!$destroyresult) {
        //     return response()->json(['message' => 'Post not deleted'], 500);
        // }

        
        return response()->json(['message' => 'Post deleted'], 200);
    }


}
