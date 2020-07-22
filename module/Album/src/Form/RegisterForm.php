<?php


namespace Album\Form;


use Zend\Form\Form;

class RegisterForm extends Form
{

    public function __construct()
    {
        // Define form name
        parent::__construct('register-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'type' => 'text',
            'name'=>'real_name',
            'options'=>[
                'label'=>'Your real name',
                'id'=>'real_name'
            ]
        ]);

        // Add "username" field
        $this->add([
            'type'  => 'text',
            'name' => 'username',
            'options' => [
                'label' => 'Your Username',
                'id'=>'username'
            ],
        ]);

        $this->add([
            'type'  => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Password',
                'id'=>'password',
                'constraint'=>'^(?=.*\d).{4,8}$'
            ],
        ]);


        // Add "redirect_url" field
        $this->add([
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Sign in',
                'id' => 'submit',
            ],
        ]);
    }
}