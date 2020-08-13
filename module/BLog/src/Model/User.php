<?php


namespace Blog\Model;


use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class User
{
    public $id;
    public $username;
    public $password;
    public $email;


    private $inputFilter;


    public function exchangeArray(array $data){

        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->username = !empty($data['username']) ? $data['username'] : null;
        $this->password  = !empty($data['password']) ? $data['password'] : null;
        $this->email  = !empty($data['email']) ? $data['email'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'username' => $this->username,
            'password'  => $this->password,
            'email' => $this->email
        ];
    }

    public function getPassword(){
        return $this->password;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                        'constraint'=>'^(?=.*\d).{4,8}$'
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

}