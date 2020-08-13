<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller;

use Zend\Db\Sql\Select;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    public $postTable;

    public function __construct($postTable)
    {
        $this->postTable = $postTable;
    }

    public function indexAction()
    {

        return new ViewModel(
            [
                'posts' => $this->postTable-> fetchAll(),
            ]);
    }
}
