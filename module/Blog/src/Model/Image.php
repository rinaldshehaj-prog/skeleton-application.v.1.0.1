<?php


namespace Blog\Model;


class Image
{
    public $id;
    public $image_content;
    public $image_type;
    public $post_id;



    public function exchangeArray(array $data){
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->image_content = !empty($data['image_content']) ? $data['image_content'] : null;
        $this->image_type  = !empty($data['image_type']) ? $data['image_type'] : null;
        $this->post_id  = !empty($data['post_id']) ? $data['post_id'] : null;
    }

    public function getArrayCopy(){
        return[
            'id' => $this->id,
            'image_content'=>$this->image_content,
            'image_type'=>$this->image_type,
            'post_id'=>$this->post_id
        ];
    }

    /**
     * @param mixed $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

}