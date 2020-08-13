<?php


namespace Blog\Model;


class Post
{
    public $id;
    public $update_time;
    public $create_time;
    public $admin_id;

    public function exchangeArray(array $data)
    {

        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->update_time = !empty($data['update_time']) ? $data['update_time'] : null;
        $this->create_time = !empty($data['create_time']) ? $data['create_time'] : null;
        $this->admin_id = !empty($data['admin_id']) ? $data['admin_id'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'update_time' => $this->update_time,
            'create_time'  => $this->create_time,
            'admin_id' => $this->admin_id
        ];
    }


    public function getCreatedTime(){
        return $this->create_time;
    }

    public function getUpdatetTime(){
        return $this->update_time;
    }

    public function getAdminId(){
        return $this->admin_id;
    }


}