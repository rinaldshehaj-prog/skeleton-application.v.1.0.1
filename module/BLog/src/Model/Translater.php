<?php


namespace Blog\Model;


class Translater
{
    public $id;
    public $content;
    public $title;
    public $post_id;
    public $language_code;

    public function exchangeArray(array $data)
    {

        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->content = !empty($data['content']) ? $data['content'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
        $this->post_id = !empty($data['post_id']) ? $data['post_id'] : null;
        $this->language_code = !empty($data['language_code']) ? $data['language_code'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'content' => $this->content,
            'title'  => $this->title,
            'post_id' => $this->post_id,
            'language_code' => $this->language_code
        ];
    }


    public function getTitle(){
        return $this->title;
    }

    public function getContent(){
        return $this->content;
    }

    public function getPostId(){
        return $this->post_id;
    }

    public function getLanguageCode(){
        return $this->language_code;
    }

    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }



}