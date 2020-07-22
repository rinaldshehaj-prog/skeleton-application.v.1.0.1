<?php


namespace Album\Form;


use Zend\Form\Form;

class LoginForm extends Form
{

    public function __construct()
    {
        // Define form name
        parent::__construct('login-form');

        // Set POST method for this form
        $this->setAttribute('method', 'POST');

        $this->addElements();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
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
            ],
        ]);


        // Add "redirect_url" field
        $this->add([
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);

//        $this->add([
//            'type' => 'csrf',
//            'name' => 'csrf',
//            'options' => [
//                'csrf_options' => [
//                    'timeout' => 600
//                ]
//            ],
//        ]);

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