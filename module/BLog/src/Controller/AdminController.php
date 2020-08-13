<?php


namespace Blog\Controller;


use Blog\Form\AddContentForm;
use Blog\Form\AddImageForm;
use Blog\Model\ImageTable;
use Blog\Model\Post;
use Blog\Model\PostTable;
use Blog\Model\Translater;
use Blog\Model\TranslaterTable;
use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Image;

class AdminController extends AbstractActionController
{
    private $translaterTable;
    private $imageTable;
    private $postTable;
    private $imageManager;


    public function __construct($translaterTable, $imageTable, $postTable, $imageManager)
    {
        $this->translaterTable = $translaterTable;
        $this->imageTable = $imageTable;
        $this->postTable = $postTable;
        $this->imageManager = $imageManager;
    }


    public function indexAction()
    {
        return new ViewModel([
            'posts'=>$this->postTable->fetchAll()
            ]);
    }

    public function addPostAction(){

        $post = new Post();
        $date = date('Y-m-d H-i-s');

        $data = [
            'create_time' => $date,
            'admin_id' => '1',
        ];

        $post->exchangeArray($data);
        $this->postTable->savePost($post);

        $post_id = $this->postTable->getPostByCreation($date)->id;

        $image = new Image();
        $image->setPostId($post_id);
        $this->imageTable->saveImage($image);

        $content = new Translater();
        $content->setPostId($post_id);
        $this->translaterTable->saveContent($content);


        return $this->redirect()->toRoute('admin', ['action'=>'addImage', 'post_id'=> $post_id], null, true);


    }

    public function addContentAction(){

        $form = new AddContentForm();
        $form->get('submit')->setValue('Next');
        $request = $this->getRequest();
        $post_id = $this->params()->fromRoute('post_id');

        if(! $request->isPost()) {
            $viewData = ['form' => $form];
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariables($viewData);
            return $viewModel;
        }

        $content = new Translater();
        $form->setData($request->getPost());


        $content->post_id = $post_id;
        $content->exchangeArray($form->getData());
        $this->translaterTable->saveContent($content);
        return $this->redirect()->toRoute('blog');

    }

    public function addImageAction(){
        $id = (int) $this->params()->fromRoute('post_id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin', ['action' => 'addPost']);
        }

        try {
            $image = $this->imageTable->getImageByPostId($id);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('admin', ['action' => 'addPost']);
        }
        $form = new AddImageForm();
        $form->bind($image);
        $form->get('submit')->setAttribute('value', 'Next');
        $request = $this->getRequest();
        $viewData = ['form'=>$form, 'post_id'=>$id, 'id'=>$image->id];

        if ($request->isPost()) {
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $image->exchangeArray((array) $data);
                $this->imageTable->saveImage($image);

                return $this->redirect()->toRoute('admin', ['action' => 'addContent', 'post_id' => $image->post_id]);
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariables($viewData);
        return $viewModel;
    }

    public function editAction(){}

    public function deleteAction(){

    }

}