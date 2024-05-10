<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\LogRepository;
use App\Http\Resources\LogResource;

class LogController extends Controller
{

    protected $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index($id, Request $request)
    {
        $perpage = $request->input('perpage', 10);

        $logs = $this->logRepository->index($perpage, $id);

        if(empty($logs)){
            return response()->json(['message' => 'No Content'], 204);
        };
        return LogResource::collection($logs);
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
        //
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
