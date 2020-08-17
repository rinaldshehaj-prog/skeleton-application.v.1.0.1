<?php


namespace Blog\Model;


use RuntimeException;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGatewayInterface;

class PostTable
{

    private $tableGateway;
    private $imageGateway;
    private $translatorGateway;

    public function __construct(TableGatewayInterface $tableGateway, TableGatewayInterface $imageGateway,
                                TableGatewayInterface $translatorGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->imageGateway = $imageGateway;
        $this->translatorGateway = $translatorGateway;
    }

    public function fetchAll(){

        $select = new Select("Post");
        $select->columns(array('id', 'create_time'));
        $select->join('Translater', 'Post.id = Translater.post_id', array( 'title', 'content'));
        $select->join('Images', 'Post.id = Images.post_id', array( 'image_content', 'image_type'));


        $resultSet = $this->tableGateway->selectWith($select)->toArray();

        return [
            'post' => $resultSet,
            'content'=> $resultSet = $this->translatorGateway->selectWith($select)->toArray(),
            'images'=>$resultSet = $this->imageGateway->selectWith($select)->toArray(),
        ];

    }

    public function getPost($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if(! $row){
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePost(Post $post){

        $data = [
            'create_time'=>$post->create_time,
            'update_time'=>$post->update_time,
            'admin_id'=>$post->admin_id,
        ];

        $id = (int) $post->id;

        if($id == 0){
            $this->tableGateway->insert($data);
            return;
        }

        try{
            $this->getPost($id);
        }catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update image with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function getPostByCreation($date){

        $row = $this->tableGateway->select(['create_time'=>$date]);
        $post = $row->current();

        if(! $post){
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $date
            ));
        }

        return $post;
    }

}