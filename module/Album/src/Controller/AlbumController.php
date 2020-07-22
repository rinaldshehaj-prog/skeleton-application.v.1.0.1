<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Album\Controller;


use Album\Form\AlbumForm;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Exception;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private $albumTable;

    /**
     * AlbumController constructor.
     * @param AlbumTable $albumTable
     */
    public function __construct(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
    }

    public function indexAction()
    {
        $form = new AlbumForm();
        return new ViewModel([
            'albums' => $this->albumTable -> fetchAll(),
            'form' => $form
        ]);
    }

    public function addAction(){

        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            $viewData = ['form' => $form];
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariables($viewData);
            return $viewModel;
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return new ViewModel(['form' => $form]);
        }

        $album->exchangeArray($form->getData());
        $this->albumTable->saveAlbum($album);
        return $this->redirect()->toRoute('album');

    }

    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->albumTable->getAlbum($id);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariables($viewData);
            return $viewModel;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->albumTable->saveAlbum($album);

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->albumTable->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }
        return [
            'id'    => $id,
            'album' => $this->albumTable->getAlbum($id),
        ];
    }

}
