<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    public $postTable;
    public $translaterTable;
    public $imageTable;
    public $imageManager;

    public function __construct($postTable, $translaterTable, $imageTable,$imageManager)
    {
        $this->postTable = $postTable;
        $this->translaterTable = $translaterTable;
        $this->imageTable = $imageTable;
        $this->imageManager = $imageManager;
    }

    public function indexAction()
    {
        $data = $this->postTable->fetchAll();

        return new ViewModel(
        [
            'data'=> $data,
        ]);
    }


    public function viewItemAction($id){

        $post = $this->postTable->getPost($id);
        $images = $this->imageTable->getImageByPostId($id);
        $content = $this->translaterTable->getContentByPostId($id);

        return new ViewModel([
           'post' => $post,
           'images' => $images,
            'content' => $content

        ]);
    }
}
