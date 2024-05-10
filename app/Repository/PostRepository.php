<?php
    namespace App\Repository;

    use App\Models\Entities\Post;
    use Exception;

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
            $model = Post::find($id);
            if(empty($model)){
                throw new Exception(get_class($model) . ': 找不到資料');
            }
            //用主鍵找資料
            return $model;
        }

        public function delete($id):bool
        {

            $model = Post::findOrFail($id);

            if(!$model->delete()){
                throw new Exception(get_class($model) . ': 刪除失敗');
            }
            //用主鍵刪除資料

            return true;
        }

        public function store(array $data)
        {
            //新增資料
            return Post::create($data);
        }

        public function update(array $data, $id)
        {
            $model = Post::findOrFail($id);

            if(!$model->update($data)){
                throw new Exception(get_class($model) . ': 更新失敗');
            }

            return $model->refresh();
        }
    }
