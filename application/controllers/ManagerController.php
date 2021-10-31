<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;
use application\models\Main;
use application\models\Admin;
use application\lib\Chatapi;

class ManagerController extends Controller {

	public function __construct($route) {
		parent::__construct($route);
		$this->view->layout = 'manager';
	}

	public function loginAction() {
		if (isset($_SESSION['manager'])) {
			$this->view->redirect('manager/posts');
		}
		if (!empty($_POST)) {
			if(!$this->model->checkRefExists($_POST['login'])){
				$this->view->message('error', 'Логин или пароль указан неверно');
			}elseif(!$this->model->loginValidate($_POST)){
				$this->view->message('error', $this->model->error);
			}
			$_SESSION['admin'] = true;
			$this->view->location('manager/posts');
		}
		$this->view->render('Вход');
	}

	public function addAction() {
        $adminModel = new Admin;
		if (!empty($_POST)) {
			if (!$this->model->zakazValidate($_POST, 'add',$this->route['idmaster'])) {
				$this->view->message('error', $adminModel->error);
			}
			$id = $adminModel->postAdd($_POST,$this->route['idmaster']);
			$idmaster=$this->route['idmaster'];
			if (!$id) {
				$this->view->message('success', 'Ошибка обработки запроса');
			}
			//$this->view->message('success', 'Заказ добавлен');
			$this->view->location('manager/posts/'.$idmaster);

		}
		$vars=['master'=>$this->model->masterdata(),'idmaster'=>$this->route['idmaster'],];
		$this->view->render('Добавить заказ', $vars);
	}

	public function editAction() {
        $adminModel = new Admin;
		if (!$this->model->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {
			if (!$this->model->zakazValidate($_POST,'edit',$this->route)){
				$this->view->message('error', $this->model->error);}
			$adminModel->postEdit($_POST, $this->route['id']);
			//$this->view->message('success',  'Сохранено');
			$this->view->location('manager/posts/'.$this->route['idmaster']);
	}

		$master=$adminModel->masterdata();
		$vars = [
			'data' => $adminModel->postData($this->route['id']),
			'master'=>$master,
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Редактировать пост', $vars);
	}

	public function logoutAction() {
		unset($_SESSION['manager']);
		$this->view->redirect('');
	}

	public function postsAction() {
        $chat= new Chatapi;
	    $adminModel = new Admin;
		if(isset($this->route['idmaster'])){
			if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
			$this->view->errorCode(404);
		}
			$mainModel = new Main;
		$pagination = new Pagination($this->route, $mainModel->postsCount($this->route,'(-1,0,1,2)'));
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $mainModel->postsList($this->route,'(-1,0,1,2)'),
			'master' =>$adminModel->masterdata(),
			'idmaster'=>$this->route['idmaster'],
            'status'=>$chat->getStatus(),
		];
	}else{
		$vars = [
			'maste' =>$this->model->masterdata(),
            'status'=>$chat->getStatus(),
		];}
		$this->view->render('Заказы', $vars);
	}
	public function sdachaAction(){
        $adminModel = new Admin;
	    if (!$this->model->isSdachaExists($this->route)) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {

			if(!$adminModel->sdachaEdit($_POST, $this->route['id'],$this->route['idmaster'])){
				$this->view->message('error', $adminModel->error);
			}
			$this->view->location('manager/posts/'.$this->route['idmaster']);
			}
			$vars = [
			'data' => $adminModel->postData($this->route['id']),
			'master'=>$adminModel->masterdata(),
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Сдача заказа', $vars);
	}
	public function gotovAction(){
        $adminModel = new Admin;
	    if (!$this->model->isGotovExists($this->route)) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {

			if(!$adminModel->gotovEdit($_POST, $this->route['id'])){
				$this->view->message('error', $adminModel->error);
			}
			$this->view->location('manager/posts/'.$this->route['idmaster']);
			}
			$vars = [
			'data' => $this->model->postData($this->route['id']),
			'master'=>$this->model->masterdata(),
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Сдача заказа', $vars);
	}
public function searchAction() {
    if(!empty($_POST)){
      //  $adminModel = new Admin;
    	$searchdata=$this->model->searchid($_POST);
	    exit($searchdata);
		}

		$this->view->render('Поиск');
}
    public function worksAction(){
        if(isset($this->route['idmaster'])){
            if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
                $this->view->errorCode(404);
            }
            $mainModel = new Main;
            $pagination = new Pagination($this->route, $mainModel->postsCount($this->route,'(0)'));
            $vars = [
                'pagination' => $pagination->get(),
                'list' => $mainModel->postsList($this->route,'(0)'),
                'master' =>$this->model->masterdata(),
                'idmaster'=>$this->route['idmaster'],
            ];
        }else{
            $vars = [
                'maste' =>$this->model->masterdata(),
            ];}
        $this->view->render('Заказы в работе', $vars);
    }
     public function rAction(){
        if(isset($this->route['idmaster'])){
            if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
                $this->view->errorCode(404);
            }
            $mainModel = new Main;
            $pagination = new Pagination($this->route, $mainModel->postsCount($this->route,'(-1)'));
            $vars = [
                'pagination' => $pagination->get(),
                'list' => $mainModel->postsList($this->route,'(-1)'),
                'master' =>$this->model->masterdata(),
                'idmaster'=>$this->route['idmaster'],
            ];
        }else{
            $vars = [
                'maste' =>$this->model->masterdata(),
            ];}
        $this->view->render('Заказы в работе', $vars);
    }
    public function nooplataAction(){
        if(isset($this->route['idmaster'])){
            if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
                $this->view->errorCode(404);
            }
            $mainModel = new Main;
            $pagination = new Pagination($this->route, $mainModel->postsCount($this->route,'(1)'));
            $vars = [
                'pagination' => $pagination->get(),
                'list' => $mainModel->postsList($this->route,'(1)'),
                'master' =>$this->model->masterdata(),
                'idmaster'=>$this->route['idmaster'],
            ];
        }else{
            $vars = [
                'maste' =>$this->model->masterdata(),
            ];}
        $this->view->render('Заказы готовые не оплачены', $vars);
    }
  public function yearAction() {
    	$mainModel = new Main;
			$vars = [
			'year' =>$mainModel->yeardata(),
		];
		$this->view->render('Архивы журналов прошлые года', $vars);
	}
	public function yearpostsAction() {
	    $mainModel = new Main;
	    if(!$mainModel->ischekyear($this->route['year'])){$this->view->errorCode(404);}
		if(isset($this->route['idmaster'])){
			if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
			$this->view->errorCode(404);
		}
		$pagination = new Pagination($this->route, $mainModel->postsCountyear($this->route,'(-1,0,1,2)'));
		$vars = [
			'pagination' => $pagination->getyear(),
			'list' => $mainModel->postsListyear($this->route,'(-1,0,1,2)'),
			'master' =>$mainModel->masterdataarxiv(),
			'idmaster'=>$this->route['idmaster'],
			'year'=>$this->route['year'],
		];
	}else{
		$vars = [
			'maste' =>$mainModel->masterdataarxiv(),
			'year'=>$this->route['year'],
		];}
		$this->view->render('Заказы за '.$this->route['year'], $vars);
	}
	public function yearworksAction(){
	    $mainModel = new Main;
	     if(!$mainModel->ischekyear($this->route['year'])){$this->view->errorCode(404);}
        if(isset($this->route['idmaster'])){
          	if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
			$this->view->errorCode(404);
		}
		$pagination = new Pagination($this->route, $mainModel->postsCountyear($this->route,'(0,1,2)'));
		$vars = [
			'pagination' => $pagination->getyear(),
			'list' => $mainModel->postsListyear($this->route,'(0,1,2)'),
			'master' =>$mainModel->masterdataarxiv(),
			'idmaster'=>$this->route['idmaster'],
			'year'=>$this->route['year'],
		];
        }else{
            $vars = [
                'maste' =>$mainModel->masterdataarxiv(),
                'year'=>$this->route['year'],
            ];}
        $this->view->render('Заказы в работе за '.$this->route['year'], $vars);
    }
    public function yearnooplataAction(){
        $mainModel = new Main;
         if(!$mainModel->ischekyear($this->route['year'])){$this->view->errorCode(404);}
        if(isset($this->route['idmaster'])){
           	if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
			$this->view->errorCode(404);
		}
		$pagination = new Pagination($this->route, $mainModel->postsCountyear($this->route,'(-1,0,1,2)'));
		$vars = [
			'pagination' => $pagination->getyear(),
			'list' => $mainModel->postsListyear($this->route,'(-1,0,1,2)'),
			'master' =>$mainModel->masterdataarxiv(),
			'idmaster'=>$this->route['idmaster'],
			'year'=>$this->route['year'],
		];
        }else{
            $vars = [
                'maste' =>$mainModel->masterdataarxiv(),
                'year'=>$this->route['year'],
            ];}
        $this->view->render('Заказы готовые не оплачены за ' .$this->route['year'], $vars);
    }
        public function inworkAction(){
             $adminModel = new Admin;
        if (!$adminModel->isinworkExists($this->route)) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {
			if(!$adminModel->inworkEdit($this->route['id'],$this->route['idmaster'])){
				$this->view->message('error', $adminModel->error);
			}
			$this->view->location('manager/posts/'.$this->route['idmaster']);
			}
			$vars = [
			'data' => $this->model->postData($this->route['id']),
			'master'=>$this->model->masterdata(),
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Взять заказ в работу', $vars);
    }
     public function rasxodAction(){
         $adminModel = new Admin;
	    if(!empty($_POST)){
	       if(!empty($_POST['date'])){
	           $tab=$adminModel->rasxod($_POST['date']);
	          exit(json_encode($tab));
           }
            if(!empty($_POST['shet'])){
                $tab=$adminModel->rasxodshet($_POST);
                exit(json_encode($tab));
            }
            if(!empty($_POST['del'])){
                $adminModel->rasxoddel($_POST['del']);
                $this->view->location('manager/rasxod');
            }
        }
        $vars=['master'=>$this->model->masterdata()];
        $this->view->render('Расходы сотрудников',$vars);
    }
    public function rasxodaddAction(){
         $adminModel = new Admin;
	    if(!empty($_POST)){
            if(!$adminModel->rasxodadd($_POST)){
                $this->view->message('error', 'Ошибка');
            }
            $this->view->location('manager/rasxod');
        }
        $vars=['master'=>$adminModel->masterdata()];
        $this->view->render('Добавить расход сотрудника',$vars);
    }
    public function sendAction(){
        $adminmodel=new Admin;
        if(!empty($_POST)){
            $result=$adminmodel->sendsms($_POST);
            if($result){
                $this->view->location('/');
            }
        }else{
            $vars=['senddate'=>json_encode($adminmodel->senddate())];
            $this->view->render('Отправка сообщения',$vars);}
    }
}
