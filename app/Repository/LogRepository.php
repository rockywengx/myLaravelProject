<?php
    namespace App\Repository;

    use App\Models\Entities\Log;
    use Exception;

    class LogRepository
    {
        public function index($perpage, $id)
        {
            //建立查詢
            $query = Log::query();

            $query->where('Post_id', $id);

            //條件查詢
            // if(!empty($condition)){
            //     foreach($condition as $field => $value){
            //         $query->where($field, $value);
            //     }
            // };

            //利用 where 條件取出文章，並將結果 return 給 controller
            //不用Pagination
            // return Log::where($condition)->offset($perpage * $page)->limit($perpage)->get();
            //用Pagination
            if(!empty($order)){
                foreach($order as $field => $value){
                    $query->orderBy($field, $value);
                }
            };

            return $query->paginate($perpage);
        }

        // public function findOrFail(string $id)
        // {
        //     $model = Log::find($id);
        //     if(empty($model)){
        //         throw new Exception(get_class($model) . ': 找不到資料');
        //     }
        //     //用主鍵找資料
        //     return $model;
        // }

        // public function delete($id):bool
        // {

        //     $model = Log::findOrFail($id);

        //     if(!$model->delete()){
        //         throw new Exception(get_class($model) . ': 刪除失敗');
        //     }
        //     //用主鍵刪除資料

        //     return true;
        // }

        public function store(array $data)
        {
            //新增資料
            return Log::create($data);
        }
    }