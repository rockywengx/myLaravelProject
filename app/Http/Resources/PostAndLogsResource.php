<?php
    namespace App\Http\Resources;

    use Illuminate\Http\Resources\Json\JsonResource;

    class PostAndLogsResource extends JsonResource
    {
        private $post;
        private $logs;

        public function __construct($post, $logs){
            $this->post = $post;
            $this->logs = $logs;
        }

        /**
        * Transform the resource into an array.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return array
         */
        public function toArray($request)
        {
            return [
                'id' => $this->post->id,
                'title' => $this->post->title,
                'content' => $this->post->content,
                'logs' => $this->logs->map(function ($logs) {
                    return [
                        'user_id' => $this->post->user_id,
                        'logid' => $logs->id,
                        'content' => $logs->content,
                        'ation' => $logs->ation,
                    ];
                }),
            ];
        }
    }
