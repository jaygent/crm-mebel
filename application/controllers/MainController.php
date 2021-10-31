<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;
use application\models\Admin;

class MainController extends Controller {
 protected $localsocket='tcp://127.0.0.1:1234';
 protected $apiKey='20af732a6d00074220af732a6d000743';
	public function indexAction() {
		$adminModel = new Admin;
		if(!isset($_COOKIE["er"])){setcookie("er", 1);}
		if($_COOKIE["er"]<3){
		$i=$_COOKIE["er"];
		if (isset($_SESSION['admin'])) {
			$this->view->redirect('admin/posts');
		}
		if (isset($_SESSION['manager'])) {
			$this->view->redirect('manager/posts');
		}
            if (isset($_SESSION['master'])) {
                $this->view->redirect('master/posts');
            }
		if (!empty($_POST)) {
			if(!$adminModel->checkRefExists($_POST['login'])){
				$i++;
				setcookie("er", $i);
				$this->view->message('error', 'Логин или пароль указан неверно');
			}elseif(!$adminModel->loginValidate($_POST)){
				$i++;
				setcookie("er", $i);
				$this->view->message('error', 'Логин или пароль указан неверно');
			}
			$proff=$adminModel->loginValidateprof($_POST);
			$id=$proff[0]['id_master'];
			switch ($proff[0]['proff']) {
				case 'Диспетчер':
					$_SESSION['manager'] = true;
					$_SESSION['id']=$id;
					$this->view->location('manager/posts');
					break;
				case "Руководитель":
					$_SESSION['admin'] = true;
					$this->view->location('admin/posts');
					break;
                case "мастер":
                    $_SESSION['master'] = true;
                    $this->view->location('master/posts');
                    break;
				default:
				$this->view->message('error', 'Логин или пароль указан неверно');
				break;
			}
		}
		$this->view->render('Вход в панель управления');
	}else{echo 'Вы заблокированны';}
	}
	public function staticmoneyAction() {
		if(date('d')==8){
				$this->model->st();
		}else{echo 'false';}
	}
	public function postAction() {
		$adminModel = new Admin;
		if (!$adminModel->isPostExists($this->route['id'])) {
			$this->view->errorCode(404);
		}
		$vars = [
			'data' => $adminModel->postData($this->route['id'])[0],
		];
		$this->view->render('Пост', $vars);
	}
    public function phoneAction(){
        $body =file_get_contents("php://input");
        $bodys=json_decode($body,true);
        if($bodys['apiKey']===$this->apiKey && $bodys['direction']==='INBOUND' && $bodys['state']==='NEW'){
            $instance = stream_socket_client($this->localsocket);
        $bodys=$this->model->soket($bodys);
        if($bodys){fwrite($instance, json_encode($bodys). "\n");}
        $e='{"result":"success","errors": []}';
        echo json_encode($e);
        }
    }
}
