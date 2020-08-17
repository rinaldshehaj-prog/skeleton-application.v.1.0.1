<?php


namespace Blog\Model;


use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class TranslaterTable
{

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        return $this->tableGateway->select();
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
    }

    public function saveContent(Translater $content)
    {
        $data = [
            'title' => $content->title,
            'content'  => $content->content,
            'language_code'=>$content->language_code,
            'post_id'=>$content->post_id,
        ];

        $id = (int) $content->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getPost($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function getContentByPostId($post_id){
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
}