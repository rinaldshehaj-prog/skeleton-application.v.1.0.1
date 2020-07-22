<?php


namespace Album\Service;


use Album\Model\UserTable;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class AuthAdapter implements AdapterInterface
{
    private $username;
    private $password;
    private $userTable;

    public function __construct(UserTable $userTable)
    {
        $this-> userTable = $userTable;
    }


    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function authenticate()
    {
        $user = $this->userTable->getUserByUsernameAndPass($this->username, $this->password);

        if ($user==null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        $passwordOfUser = $user->getPassword();
        if($this->password == $passwordOfUser){
            return new Result(
                Result::SUCCESS,
                ['Authenticated successfully.']);
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);

    }
}