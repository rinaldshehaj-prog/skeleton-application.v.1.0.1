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

        $data = $this->postTable->fetchAll();

        return new ViewModel([
            'data' => $data
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


        return $this->redirect()->toRoute('admin', ['action'=>'addImage', 'post_id'=> $post_id]);


    }

    public function addContentAction(){

        $id = (int) $this->params()->fromRoute('post_id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin', ['action' => 'addPost']);
        }

        try {
            $content = $this->translaterTable->getContentByPostId($id);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('admin', ['action' => 'addPost']);
        }

        $form = new AddContentForm();
        $request = $this->getRequest();
        $form->bind($content);
        $viewData = ['form'=>$form, 'id'=>$content->id, 'post_id'=>$id];

        if ($request->isPost()) {
            $form->setData($data = $request->getPost()->toArray());

            if ($form->isValid()) {

                $content->exchangeArray((array) $form->getData());
                $this->translaterTable->saveContent($content);

                return $this->redirect()->toRoute('blog');
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariables($viewData);
        return $viewModel;
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
        $request = $this->getRequest();
        $form->bind($image);
        $viewData = ['form'=>$form, 'id'=>$image->id, 'post_id'=>$id];


        if ($request->isPost()) {

            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);
            if ($form->isValid()) {
                $data = (array) $form->getData();
                $fileInfo = [
                    'id' => $data['id'],
                    'image_content' => $data['image_content']['name'],
                    'image_type'=> $data['image_content']['type'],
                    'post_id'=>$data['post_id']
                ];

                $image->exchangeArray($fileInfo);
                $this->imageTable->saveImage($image);

                return $this->redirect()->toRoute('admin', ['action' => 'addContent', 'post_id' => $image->post_id]);
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setVariables($viewData);
        return $viewModel;
    }


}