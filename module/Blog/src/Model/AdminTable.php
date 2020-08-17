<?php
namespace Blog\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AdminTable
{

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        return $this->tableGateway->select();
    }

    public function getAdmin($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function getAdminByUsernameAndPass($username, $password){
        $row = $this->tableGateway->select(['username' => $username, 'password' => $password]);
        $admin = $row-> current();

        if (! $admin) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $username
            ));
        }

        return $admin;

    }

}