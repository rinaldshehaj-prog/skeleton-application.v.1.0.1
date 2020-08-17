<?php


namespace Blog\Model;


use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class ImageTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        return $this->tableGateway->select();
    }

    public function getImages($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset -> current();

        if(! $row){
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function getImageByPostId($post_id){
        $post_id = (int) $post_id;
        $rowset = $this->tableGateway->select(['post_id' => $post_id]);
        $row = $rowset -> current();
        if(! $row){
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $post_id
            ));
        }

        return $row;
    }

    public function saveImage(Image $image){

        $data = [
            'image_content'=> $image ->image_content,
            'image_type'=> $image->image_type,
            'post_id'=> $image->post_id,
        ];

        $id = (int) $image->id;

        if($id == 0){
            $this->tableGateway->insert($data);
            return;
        }
        try{
            $this->getImages($id);
        }catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update image with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

}