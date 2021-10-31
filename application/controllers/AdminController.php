<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;
use application\models\Main;
use application\lib\Chatapi;

class AdminController extends Controller {

	public function __construct($route) {
		parent::__construct($route);
		$this->view->layout = 'admin';
	}

	public function loginAction() {
		if (isset($_SESSION['admin'])) {
			$this->view->redirect('admin/posts');
		}
		if (!empty($_POST)) {
			if(!$this->model->checkRefExists($_POST['login'])){
				$this->view->message('error', 'Логин или пароль указан неверно');
			}elseif(!$this->model->loginValidate($_POST)){
				$this->view->message('error', $this->model->error);
			}
			$_SESSION['admin'] = true;
			$this->view->location('admin/posts');
		}
		$this->view->render('Вход');
	}

	public function addAction() {
		if (!empty($_POST)) {
			if (!$this->model->zakazValidate($_POST, 'add',$this->route['idmaster'])) {
				$this->view->message('error', $this->model->error);
			}
			$id = $this->model->postAdd($_POST,$this->route['idmaster']);
			$idmaster=$this->route['idmaster'];
			if (!$id) {
				$this->view->message('success', 'Ошибка обработки запроса');
			}
			//$this->view->message('success', 'Заказ добавлен');
			$this->view->location('admin/posts/'.$idmaster);


		}
		$vars=['master'=>$this->model->masterdata(),'idmaster'=>$this->route['idmaster'],];
		$this->view->render('Добавить заказ', $vars);
	}

	public function editAction() {
		if (!$this->model->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {
			if (!$this->model->zakazValidate($_POST,'edit',$this->route)){
				$this->view->message('error', $this->model->error);}

			if(!$this->model->postEdit($_POST, $this->route['id']))
            {
                $this->view->message('error', $this->model->error);}
			$this->view->location('admin/posts/'.$this->route['idmaster']);
	}

		$master=$this->model->masterdata();
		$vars = [
			'data' => $this->model->postData($this->route['id']),
			'master'=>$master,
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Редактировать пост', $vars);
	}

	public function deleteAction() {
		if (!$this->model->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		$this->model->postDelete($this->route['id']);
		$this->view->redirect('admin/posts');
	}
    public function otpuskAction() {
		$work=$this->model->otpusk($this->route['id']);
		//$this->view->message('error',$work);
		$this->view->redirect('admin/sot');
	}

	public function logoutAction() {
		unset($_SESSION['admin']);
		$this->view->redirect('');
	}

	public function postsAction() {
        $chat= new Chatapi;
		if(isset($this->route['idmaster'])){
			if (!$this->model->iszhurnalExists($this->route['idmaster'])) {
			$this->view->errorCode(404);
		}
			$mainModel = new Main;
		$pagination = new Pagination($this->route, $mainModel->postsCount($this->route,'(-1,0,1,2)'));
		$vars = [
			'pagination' => $pagination->get(),
			'list' => $mainModel->postsList($this->route,'(-1,0,1,2)'),
			'master' =>$this->model->masterdata(),
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
	    if (!$this->model->isSdachaExists($this->route)) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {

			if(!$this->model->sdachaEdit($_POST, $this->route['id'],$this->route['idmaster'])){
				$this->view->message('error', $this->model->error);
			}
			$this->view->location('admin/posts/'.$this->route['idmaster']);
			}
			$vars = [
			'data' => $this->model->postData($this->route['id']),
			'master'=>$this->model->masterdata(),
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Сдача заказа', $vars);
	}
	public function gotovAction(){
	    if (!$this->model->isGotovExists($this->route)) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {

			if(!$this->model->gotovEdit($_POST, $this->route['id'],$this->route['idmaster'])){
				$this->view->message('error', $this->model->error);
			}
			$this->view->location('admin/posts/'.$this->route['idmaster']);
			}
			$vars = [
			'data' => $this->model->postData($this->route['id']),
			'master'=>$this->model->masterdata(),
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Сдача заказа', $vars);
	}
	public function zarplataAction(){
		if(!empty($_POST)){
			$zp=$this->model->zarplata($_POST);
			exit(json_encode($zp));
		}
        $home = $_SERVER['DOCUMENT_ROOT'];
        $dir = $home . '/application/file';
        $handle = opendir($dir);
        $files='';
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $filesname=rtrim($file,'.pdf');
                $files.='<p><a href="/application/file/'.$file.'">'.$file.'</a><a href="/admin/delfile/'.$filesname.'" class="btn btn-danger">Удалить</a></p>';
            }
        }
            $vars=['master'=>$this->model->masterdata(),'home'=>$files,];
		$this->view->render('Расчет заплаты',$vars);
	}
	public function sotAction(){
		if(!empty($_POST)){
		$this->model->mastereditadd($_POST);
		//$this->view->message('success', 'Сохранено');
		$this->view->location('admin/sot');
		}
		$vars=['master'=>$this->model->masterdatasot(),];
		$this->view->render('Сотрудники',$vars);
	}
		public function sotidAction(){
		if(!empty($_POST)){
			$this->model->masteredit($_POST,$this->route['id']);
			$this->view->location('admin/sot');
			$this->view->message('success', 'Сохранено');
		}
		$vars=[
			'data'=> $this->model->mastereditdata($this->route['id']),
		];
		$this->view->render('Редактирования сотрудника',$vars);
	}
	public function sotdelAction() {
		if (!$this->model->issotExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		$this->model->sotDelete($this->route['id']);
		$this->view->redirect('admin/sot');
	}
	public function profAction() {
	if(!empty($_POST)){
			if($this->model->adminupdate($_POST)){$this->view->message('success', 'Сохранено');}else{$this->view->message('error', 'Пароли не совпадают');}
		}

	$vars=[
			'data'=> $this->model->zakazcheksum(),
		];
		$this->view->render('Редактирования администратора',$vars);
}
public function searchAction() {
    if(!empty($_POST)){
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
       public function staticAction(){
           if(!empty($_POST)){
               $static=$this->model->staticdatapost($_POST);
               exit(json_encode($static));
           }
           $vars = [
               'static' =>$this->model->staticdata(),
               'year'=>$this->model->staticyear(),
           ];
           $this->view->render('Статистика за период', $vars);
    }

    public function delfileAction(){
        $this->view->render('Статистика за период');
	    $namefile=$this->route['namefile'];
        $upOne = realpath(__DIR__ . '/..');
        unlink($upOne.'/file/'.$namefile.'.pdf');
        header('Location: /admin/zarplata');
    }
    public function lenaAction(){
	   if(!empty($_POST)){
	        if(!$this->model->lenachek($_POST)){
                $this->view->message('error', $this->model->error);
            }
            $this->view->location('admin/lena');
        }
        $vars = [
            'lena' =>$this->model->lenadata(),
        ];
        $this->view->render('Отметки расчета лены по заказам', $vars);
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
        if (!$this->model->isinworkExists($this->route)) {
			$this->view->errorCode(404);
		}
		if (!empty($_POST)) {
			if(!$this->model->inworkEdit($this->route['id'],$this->route['idmaster'])){
				$this->view->message('error', $this->model->error);
			}
			$this->view->location('admin/posts/'.$this->route['idmaster']);
			}
			$vars = [
			'data' => $this->model->postData($this->route['id']),
			'idmaster'=>$this->route['idmaster'],
		];
		$this->view->render('Взять заказ в работу', $vars);
    }
    public function rasxodAction(){
	    if(!empty($_POST)){
	       if(!empty($_POST['date'])){
	           $tab=$this->model->rasxod($_POST['date']);
	          exit(json_encode($tab));
           }
            if(!empty($_POST['shet'])){
                $tab=$this->model->rasxodshet($_POST);
                exit(json_encode($tab));
            }
            if(!empty($_POST['del'])){
                $this->model->rasxoddel($_POST['del']);
                $this->view->location('admin/rasxod');
            }
        }
        $vars=['master'=>$this->model->masterdata()];
        $this->view->render('Расходы сотрудников',$vars);
    }
    public function rasxodaddAction(){
	    if(!empty($_POST)){
            if(!$this->model->rasxodadd($_POST)){
                $this->view->message('error', 'Ошибка');
            }
            $this->view->location('admin/rasxod');
        }
        $vars=['master'=>$this->model->masterdata()];
        $this->view->render('Добавить расход сотрудника',$vars);
    }
       public function sendAction(){
        if(!empty($_POST)){
          $result=$this->model->sendsms($_POST);
          if($result){
              $this->view->location('/');
          }else{ $this->view->message('error', 'Вы отправляли уже на этот номер');}
        }else{
            $vars=['senddate'=>json_encode($this->model->senddate())];
            $this->view->render('Отправка сообщения',$vars);}
    }
    public function settingAction(){
        if(!empty($_POST)){
            $this->model->settingsave($_POST);
            $this->view->location('admin/setting');
        }
        $var=['data'=>$this->model->setting()];
        $this->view->render('Настройка сообщений',$var);
    }
}
