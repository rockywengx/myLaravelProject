<?php
    namespace App\Repository;

    use App\Models\Entities\Post;

    class PostRepository
    {
        public function index($perpage, $condition = [], $order = [])
        {
            //建立查詢
            $query = Post::query();

            //條件查詢
            if(!empty($condition)){
                foreach($condition as $field => $value){
                    $query->where($field, $value);
                }
            };

            //利用 where 條件取出文章，並將結果 return 給 controller
            //不用Pagination
            // return Post::where($condition)->offset($perpage * $page)->limit($perpage)->get();
            //用Pagination
            if(!empty($order)){
                foreach($order as $field => $value){
                    $query->orderBy($field, $value);
                }
            };

            return $query->paginate($perpage);
        }

        public function findOrFail(string $id)
        {
            //用主鍵找資料
            return Post::find($id);
        }

        public function delete($id){
            //用主鍵刪除資料
            return Post::destroy($id);
        }

        public function store(array $data)
        {
            //新增資料
            return Post::create($data);
        }

        public function update(array $data, $id)
        {
            $post = Post::find($id);
            //更新資料
            return $post ? $post->updated($data) : false;
        }
    }
