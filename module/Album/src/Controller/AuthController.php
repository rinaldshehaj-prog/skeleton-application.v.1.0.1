<?php


namespace Album\Controller;




use Album\Form\LoginForm;
use Album\Form\RegisterForm;
use Album\Model\User;
use Zend\Authentication\Result;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Uri;
use Zend\View\Model\ViewModel;


class AuthController extends AbstractActionController
{
    private $userTable;
    private $authManager;


    public function __construct($userTable, $authManager)
    {
        $this->userTable = $userTable;
        $this->authManager = $authManager;
    }

    public function loginAction(){

        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');

        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }



        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = false;


        if($this->getRequest()->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()) {

                $data = $form->getData();

                $result = $this->authManager->login($data['username'],
                    $data['password']);

                if ($result->getCode()==Result::SUCCESS) {

                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {

                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    if(empty($redirectUrl)) {
                        return $this->redirect()->toRoute('');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }

                }
            }
            else {
                $isLoginError = true;
            }
        } else {
            $isLoginError = false;
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function registerAction(){

        $form = new RegisterForm();
        $form->get('submit')->setValue('Register');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            $viewData = ['form' => $form];
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariables($viewData);
            return $viewModel;
        }

        $user = new User();
        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return new ViewModel(['form' => $form]);
        }

        $user->exchangeArray($form->getData());
        $this->userTable->saveUser($user);
        return $this->redirect()->toRoute('auth');

    }

    public function logoutAction()
    {
        $this->authManager->logout();

        return $this->redirect()->toRoute('auth', ['action'=>'login']);
    }

}