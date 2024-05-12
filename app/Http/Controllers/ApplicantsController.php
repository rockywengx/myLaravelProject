<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ApplicantsRequest;
use App\Repository\ApplicantsRepository;
use App\Http\Resources\PostResource;
use Knuckles\Scribe\Attributes\BodyParam;

class ApplicantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $applicantsRepository;

    public function __construct(ApplicantsRepository $ApplicantsRepository)
    {
        $this->applicantsRepository = $ApplicantsRepository;
    }

    public function index(Request $request)
    {
        $perpage = $request->input('prepare', 10);
        $order = $request->input('orderby', [ 'id' => 'desc' ]);

        //除了 perpage 以外的所有條件
        $condition = $request->except(['prepare', 'filter']);

        // $condition = $request->only(['title', 'content']);

        $posts = $this->applicantsRepository->index($perpage, $condition, $order);

        if(empty($posts)){
            return response()->json(['message' => 'No Content'], 204);
        };
        return PostResource::collection($posts);
        // return view('posts.index', compact('posts'));
    }

    /**
     * 新增使用者
     */
    #[BodyParam(name: 'email', description: '電子郵件')]
    #[BodyParam(name: 'password', description: '密碼')]
    public function store(ApplicantsRequest  $request)
    {

        //對要求進行驗證
        // $validatedData  = $request->validate([
        //     'title' => 'required|max:255|unique:posts',
        //     'content' => 'required'
        // ]);

        //手動增加'user_id' => 1'的數據
        // $request['user_id'] = 1;
        //從驗證過的要求中取出所有數據並存儲
        $post = $this->applicantsRepository->store($request->all());


        // $request->validated();
        if (empty($post)) {
            return response()->json(['message' => 'created is Not Success'], 500);
        }

        // return response()->json(['message' => 'Post not created'], 500);
        //成功回傳成功代碼
        // return response()->json(["id" => $post->id]);
        return response()->json($post);

    }


}
