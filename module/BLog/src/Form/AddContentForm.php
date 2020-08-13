<?php


namespace Blog\Form;


use Zend\Form\Form;

class AddContentForm extends Form
{
    public function __construct()
    {

        parent::__construct('addContent-form');

        $this->setAttribute('method', 'POST');

        $this->addElements();
    }

    protected function addElements(){

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name'=>'post_id',
            'type'=>'hidden'
        ]);

        $this->add([
            'name'=>'language_code',
            'type'=>'hidden'
        ]);



        $this->add([
            'type' => 'text',
            'name'=>'title',
            'options'=>[
                'label' => 'Title',
                'id'=>'title'
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name'=>'content',
            'options'=>[
                'label' => 'Content',
                'id'=>'content'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Finish',
                'id'    => 'submitbutton',
            ],
        ]);
    }




}