<?php


namespace Blog\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;


class AddImageForm extends Form
{

    public function __construct($name = null, $options = [])
    {
        parent::__construct('addImage-form');

        $this->setAttribute('method', 'POST');

        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements();

        $this->addInputFilter();

    }

    private function addElements()
    {
        $this->add([
           'name'=>'id',
           'type'=>'hidden'
        ]);

        $this->add([
            'name'=>'post_id',
            'type'=>'hidden'
        ]);

        $this->add([
            'type'=>'file',
            'name'=>'image_content',
            'attributes' => [
                'id' => 'image_content',
            ],
            'options'=>[
                'label'=>'Image',
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Next',
                'id'    => 'submit',
            ],
        ]);

    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add validation rules for the "image_content" field.
        $inputFilter->add([
            'type'     => 'Zend\InputFilter\FileInput',
            'name'     => 'image_content',
            'required' => true,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/jpg','image/png']
                    ]
                ],
                ['name'    => 'FileIsImage'],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => $_SERVER['DOCUMENT_ROOT'] . '/img/blog/',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],
        ]);
    }

}