<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostAndLogsResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\LogResource;
use App\Http\Resources\LogCollectionResource;
use App\Repository\PostRepository;
use App\Repository\LogRepository;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseField;
use Knuckles\Scribe\Attributes\UrlParam;

/**
 * @group Post 部落格
 */

class PostController extends Controller
{

    protected $postRepository;
    protected $logRepository;

    public function __construct(PostRepository $postRepository, LogRepository $logRepository)
    {
        $this->postRepository = $postRepository;
        $this->logRepository = $logRepository;
    }

    /**
     * 顯示所有文章
     * 能自訂每頁顯示的筆數
     * 初始每頁10筆的方式顯示
     * 能增加條件查詢
     * 能自訂欄位降冪或升冪排序
     * 初始為id降冪排序
     */
    #[QueryParam(name: 'filter', description: '條件查詢', type: 'object')]
    #[QueryParam(name: 'orderby', description: '欄位排序', type: 'string')]
    #[QueryParam(name: 'presage', description: '每頁顯示的筆數', type: 'int', example: 10)]
    #[QueryParam(name: 'page', description:'頁數', type:'int', example:1)]
    #[Response( status: 200, description: 'No Content' )]
    #[Response( status: 302, description:'Redirect' )]
    #[Response( status: 200, description: 'OK', content: '{"id":1,"title":"Hello World","content":"Hello World"}')]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: 'Hello World')]
    public function index(Request $request)
    {
        $prepare = $request->input('prepare', 10);
        $filter = $request->input('filter', []);
        $order = $request->input('orderby', [ 'id' => 'desc' ]);

        //除了 prepare 以外的所有條件
        $condition = $request->except(['prepare', 'filter', 'oderby']);

        // $condition = $request->only(['title', 'content']);

        $posts = $this->postRepository->index($prepare, $condition, $order);

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
     * 新增文章
     */
    #[BodyParam(name: 'title', description: '文章標題', type: 'string', example: 'Hello World')]
    #[BodyParam(name: 'content', description: '文章內容', type: 'string', example: '早安')]
    #[Response( status: 200, description: '新增失敗' )]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    public function store(PostRequest  $request)
    {

        //對要求進行驗證
        // $validatedData  = $request->validate([
        //     'title' => 'required|max:255|unique:posts',
        //     'content' => 'required'
        // ]);

        // //手動增加'user_id' => 1'的數據
        // $request['user_id'] = $request->user()->id;

        //從驗證過的要求中取出所有數據並存儲
        $post = $this->postRepository->store(
            $request->all()
        );


        // $request->validated();
        if (empty($post)) {
            return response()->json(['message' => 'created is Not Succese'], 500);
        }

        $this->logRepository->store([
            'content' => $post->content,
            'Post_id' => $post->id,
            'ation' => 'create'
        ]);
        // return response()->json(['message' => 'Post not created'], 500);
        //成功回傳成功代碼
        // return response()->json(["id" => $post->id]);
        return PostResource::make($post);

    }

    /**
     * 回傳指定文章
     */
    #[UrlParam(name: 'id', description: '文章ID', type: 'string', example: 1)]
    #[Response( status: 200, description: 'No Content' )]
    #[Response( status: 200, description: 'OK', content: '{"id":1,"title":"Hello World","content":"早上好"}')]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: '早上好')]
    public function show(string $id)
    {
        //用post取相關的log
        $post = $this->postRepository->findOrFail($id);
        $post->load('logs');
        $logs = $post->logs;
        $logResource = LogResource::collection($logs);


        $logs = $this->logRepository->index(10,$id);

        $postAndLogs = new PostAndLogsResource($post, $logs);

        // if (empty($post)) {
        //     return response()->json(['message' => 'Post not found'], 404);
        // }

        return $postAndLogs;
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
    #[UrlParam(name: 'id', description: '文章ID', type: 'int', example: 1)]
    #[BodyParam(name: 'title', description: '文章標題', type: 'string', example: 'Hello World')]
    #[BodyParam(name: 'content', description: '文章內容', type: 'string', example: '早上好')]
    #[Response( status: 200, description: '修改失敗' )]
    #[ResponseField(name: 'id', description: '文章ID', example: 1)]
    #[ResponseField(name: 'title', description: '文章標題', example: 'Hello World')]
    #[ResponseField(name: 'content', description: '文章內容', example: '早上好')]
    public function update( int $id, PostRequest $request)
    {

        // $validata = $request->validate([
        //     'title' => 'bail|required|max:255',
        //     'content' => 'required'
        // ]);

        //回傳執行別為boolean
        $updateresult = $this->postRepository->update($request->all(), $id);

        //在更新文章後，將更新的內容記錄到log中
        $this->logRepository->store([
            'content' => $request->content,
            'Post_id' => $request->id,
            'user_id' => $request->user()->id,
            'ation' => 'update'
        ]);

        // if (!$updateresult){
        //     return response()->json(['message' => 'Post not updated'], 500);
        // }

        return PostResource::make($updateresult);

    }

    /**
     * 刪除指定文章
     */
    #[UrlParam(name: 'id', description: '文章ID', type: 'int', example: 1)]
    #[Response( status: 200, description: '刪除失敗' )]
    public function destroy(string $id)
    {
        //
        $log = ['content' => 'delete post', 'Post_id' => $id, 'ation' => 'delete'];

        $delect = $this->postRepository->delete($id);

        if($delect){
            $this->logRepository->store($log);
        };

        // if (!$destroyresult) {
        //     return response()->json(['message' => 'Post not deleted'], 500);
        // }



        return response()->json(['message' => 'Post deleted'], 200);
    }


}
