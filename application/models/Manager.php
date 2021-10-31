<?php

namespace application\models;

use application\core\Model;
use application\core\Admin;
use application\lib\Checksend;
class Manager extends Model {

	public $error;

	public function loginValidate($post) {
		$params = [
			'login' => $post['login'],
		];
		$password=$post['password'];
		$hash = $this->db->column('SELECT password FROM master WHERE login=:login', $params);
		if (!$hash or !password_verify($password, $hash)) {
			$this->error = 'Логин или пароль указан неверно';
			return false;
		}
		return true;
	}
	public function loginValidateprof($post) {
		$params = [
			'login' => $post['login'],
		];
		$prof=$this->db->row('SELECT proff FROM master WHERE login=:login', $params);
		return $prof;
	}

    public function zakazValidate($post, $type,$idmaster) {
        switch ($type) {
            case 'add':
                $params = [
                    'nomer_zakaza' => $post['nomer_zakaza'],
                    'idmaster'=>$idmaster,
                    'year'=>date('Y'),
                ];
                $nomer=$this->db->column('SELECT * FROM zakaz WHERE nomer_zakaza=:nomer_zakaza AND idmaster=:idmaster AND year=:year and statuss<4', $params);
                if(!empty($nomer)){$this->error = 'Заказ '.$post['nomer_zakaza'].' у данного мастера уже существует'; return false;}
                foreach($post as $s=>$val){
                    if(empty($val) && $val= ""){$this->error = 'Не заполнено поле'; return false;}
                }
                return true;
                break;

            default:

                $params=['id_zakaza'=>$idmaster['id']];
                $status=$this->db->row('SELECT statuss FROM zakaz WHERE id_zakaza=:id_zakaza', $params);
                $status=$status[0]['statuss'];
                if(!isset($post['splitup'])){
                    if(!empty($status)) {
                        $sum = (int)$post['pro_ob'] + (int)$post['pro_sh'];
                        if ($sum > 17) {
                            $this->error = 'Сумма процентов от заказа не больше 17%';
                            return false;
                        } elseif ($sum < 16) {
                            $this->error = 'Сумма процентов от заказа не меньше 16%';
                            return false;
                        }
                    }if(!empty($nomer)){$this->error = 'Заказ '.$post['nomer_zakaza'].' у данного мастера уже существует'; return false;}
                    foreach($post as $s=>$val){
                        if(empty($val) && $val= ""){$this->error = 'Не заполнено поле'; return false;}
                    }
                    return true;}else{
                    if(!empty($status)){
                        $sum=(int)$post['pro_ob1']+(int)$post['pro_sh1'];
                        if($sum>17){$this->error = 'Сумма процентов в первом разделении от заказа не больше 17%'; return false;}elseif($sum<16){$this->error = 'Сумма процентов во втором разделении от заказа не меньше 16%'; return false;}
                        $sum=(int)$post['pro_ob2']+(int)$post['pro_sh2'];
                        if($sum>17){$this->error = 'Сумма процентов во втором разделении от заказа не больше 17%'; return false;}elseif($sum<16){$this->error = 'Сумма процентов во втором разделении от заказа не меньше 16%'; return false;}
                        $sum=(real)str_replace(' ', '', $post['price1'])+(real)str_replace(' ', '', $post['price2']);
                        $price=(real)str_replace(' ', '', $post['price']);
                        if($price!==$sum){$this->error = 'Cумма разделенных исполнителей больше чем цена заказа'; return false;}}
                    if(!empty($nomer)){$this->error = 'Заказ '.$post['nomer_zakaza'].' у данного мастера уже существует'; return false;}
                    foreach($post as $s=>$val) {
                        if (empty($val) && $val = "") {
                            $this->error = 'Не заполнено поле';
                            return false;
                        }
                    }return true;}
                break;
        }
    }

	public function checkRefExists($login) {
		$params = [
			'login' => $login,
		];
		return $this->db->column('SELECT login FROM master WHERE login=:login', $params);
	}
	public function isPostExists($id) {
		$params = [
			'id_zakaza' => $id,
		];
		return $this->db->column('SELECT * FROM zakaz  WHERE id_zakaza = :id_zakaza AND statuss<2', $params);
	}
public function iszhurnalExists($id) {
    if($id==1){return true;}
    $params = [
        'id_master' => $id,
    ];
    return $this->db->column('SELECT * FROM master  WHERE id_master =:id_master and proff IN ("мастер","Выездной мастер")', $params);
}
	public function isSdachaExists($route) {
		$params = [
			'id_zakaza' => $route['id'],
			'idmaster'=>$route['idmaster'],
		];
		return $this->db->column('SELECT * FROM zakaz  WHERE statuss<2 and id_zakaza = :id_zakaza and idmaster=:idmaster', $params);
	}
	public function isGotovExists($route) {
		$params = [
			'id_zakaza' => $route['id'],
			'idmaster'=>$route['idmaster'],
		];
		return $this->db->column('SELECT * FROM zakaz  WHERE statuss=0 and id_zakaza = :id_zakaza and idmaster=:idmaster', $params);
	}
	public function postData($id) {
		$params = [
			'id' => $id,
		];
		$zakaz=$this->db->row('SELECT * FROM zakaz WHERE id_zakaza=:id', $params);
		foreach($zakaz as $keyss=>$valss){
		    $data[$keyss]=$valss;
		$params = ['id_zakaza' => $valss['id_zakaza'],];
		$mebel=$this->db->row('SELECT * FROM mebel WHERE id_zakaza=:id_zakaza', $params);
		 $data[$keyss]['mebel']=$mebel[0]['name_mebel'];
		$parmeb = ['id_zakaza' => $valss['id_zakaza'],];
		   $tkani=$this->db->row('SELECT * FROM tkani WHERE id_zakaza=:id_zakaza', $parmeb);
		$data[$keyss]['tkan']=$tkani[0]['name_tkani'];
		$params = ['id_zakaza' => $valss['id_zakaza'],];
		$master=$this->db->row('SELECT id,master.id_master,pro,name,proff FROM master_pro,master WHERE master.id_master=master_pro.id_master and id_zakaza=:id_zakaza ORDER by id', $params);
		foreach($master as $k=>$cv){
		    $data[$keyss]['master'][$cv['id']]=$cv;
		}

	}return $data;

	}
    public function masterdata(){
       return $this->db->row('SELECT id_master,name,proff FROM master WHERE work=1 AND proff NOT IN("Руководитель") ');
    }
    public function zarplata($post){
    	$params=[
    		'dates'=>$post['data_first'],
    		'data'=>$post['data_last'],
    		'id_master'=>$post['master'],

    	];
    	$zp=$this->db->row('SELECT * FROM zakaz,master_pro WHERE zakaz.id_zakaza=master_pro.id_zakaza AND id_master=:id_master AND statuss<=2 AND dates BETWEEN :dates AND :data', $params);
    	 $zptext='';
    	 return $zp;
    	foreach ($zp as $key => $value) {
    		$zptext.='<div class="zakaz">';
        			$zptext.='<p>Номер заказа '.$value['nomer_zakaza'].'; </p>';
    			$params = [
			'id_zakaza' => $value['id_zakaza'],
		];
                $mebel=$this->db->row('SELECT * FROM mebel WHERE id_zakaza = :id_zakaza', $params);
                $mtext='';
                foreach ($mebel as $val) {
                $mtext.='Наименование мебели : '.$val['name_mebel'];
                }
                $zptext.='<p> Мебель : '.$mtext.'; </p>';
                $pro_ob=(real)$value['pro_ob']*100;
                $pro_sh=(real)$value['pro_sh']*100;
                $zptext.='<p> Процент обивщика : '.$pro_ob.'%; Процент швеи : '.$pro_sh.'% </p>';
                $zptext.='<p>Стоимость заказа '.$value['price'].'RUB</p>';
                $zptext.='<p>Зарплата с заказа : '.(real)$value['pro']*$value['price'].'RUB</p>';
				$zptext.='</div><hr>';
    			}
						 $sum=0;
                        foreach ($zp as $value) {
                            $sum+=(real)$value['pro']*$value['price'];                        }

                  $zptext.='<p> Итого за период работы с '.$post['data_first'].' по '.$post['data_last'].' составила : '.round($sum).'RUB; <p>';
                  return $zptext;
                      }


	public function adminupdate($post){
		if($post['password']===$post['password1']){
                      $params=['login'=>'admin',
                      'password'=>password_hash($post['password'], PASSWORD_BCRYPT),];
                       $this->db->row('UPDATE master SET password =:password  WHERE login=:login', $params); return true;}else{return false;}
                  }

      public function searchid($post){
    switch ($post['type']) {
      case '0':
      $params=['search'=>"%".$post['ref']."%"];
        $dat=$this->db->row('SELECT id_zakaza FROM zakaz WHERE statuss<4 AND  nomer_zakaza LIKE :search OR nomer_lena LIKE :search', $params);
        $d='(';
                        foreach($dat as $val){
                         $d.=$val['id_zakaza'].',';
                        }  $dat=substr($d,0,-1);
                         $dat.=')';
        break;
      case '1':
        $params=['search'=>"%".$post['ref']."%"];
        $dat=$this->db->row('SELECT id_zakaza FROM zakaz WHERE statuss<4 AND  klient LIKE :search or phone1 LIKE :search OR phone2 LIKE :search ', $params);
        $d='(';
                        foreach($dat as $val){
                         $d.=$val['id_zakaza'].',';
                        }  $dat=substr($d,0,-1);
                        $dat.=')';
        break;
    }
    if(!empty($dat)){
      $par=['year'=>$post['data']];
    $zakaz=$this->db->row('SELECT * FROM zakaz WHERE statuss<4 AND year=:year AND id_zakaza IN'.$dat,$par);
    foreach($zakaz as $keyss=>$valss){
        $data[$keyss]=$valss;
    $params = ['id_zakaza' => $valss['id_zakaza'],];
    $mebel=$this->db->row('SELECT * FROM mebel WHERE id_zakaza=:id_zakaza', $params);
    $data[$keyss]['mebel']=$mebel[0]['name_mebel'];
    $tkani=$this->db->row('SELECT * FROM tkani WHERE id_zakaza=:id_zakaza', $params);
     $data[$keyss]['tkan']=$tkani[0]['name_tkani'];
    $params = ['id_zakaza' => $valss['id_zakaza'],];
    $master=$this->db->row('SELECT id,master.id_master,pro,name,proff FROM master_pro,master WHERE master.id_master=master_pro.id_master and id_zakaza=:id_zakaza', $params);
    $data[$keyss]['master']=$master;
     if($valss['splitup']==1){
                $params = ['id_zakaza' => $valss['id_zakaza'],];
                $mastersplitup=$this->db->row('SELECT id_split,master.id_master,pro,name,proff,prices FROM splitup_zakaz,master WHERE master.id_master=splitup_zakaz.id_master and id_zakaza=:id_zakaza ORDER BY id_split', $params);
                $data[$keyss]['mastersplitup'] =$mastersplitup;
            }
    }if(empty($data)){ return 'Ничего не найдено';}
    $searchdata='';
    $searchdata.='<table class="table delivery"><thead><tr><th>№</th><th>Дата</th><th>Данные Клиента</th><th>Дата сдачи заказа</th><th>Мебель</th><th>Мастера</th><th>Обивщик</th><th>Швея</th><th>Диспетчер</th><th>Цена</th><th>Примечание</th><th>Ред</th></tr></thead>';
    foreach ($data as $value) {
        $classs='';
        $lena_staatus='';
       switch ($value['statuss']) {
                    case -1:
                        if($value['lena_status']==1){$lena_staatus='hatching-green';}
                        $classs='class="bg-danger '.$lena_staatus.'"';
                    $bt='<div class="btn bg-info" style="border: 2px solid #fff; color: #fff;">В ракушке</div>';
                    break;
                    case 0:
                         if($value['lena_status']==1){$lena_staatus='hatching-green';}
                    //echo 'class="bg-danger"';
                    $bt='<div class="btn bg-danger" style="border: 2px solid #fff; color: #fff;">В работе</div>';
                    break;
                    case 1:
                         if($value['lena_status']==1){$lena_staatus='hatching-green';}
                    $classs='class="bg-warning '.$lena_staatus.'"';;
                    $bt='<div class="btn bg-warning" style="border: 2px solid #fff; color: #fff;">Готов-Не оплачен</div>';
                    break;
                    case 2:
                         if($value['lena_status']==1){$lena_staatus='hatching-green';}
                    $classs='class="bg-success '.$lena_staatus.'"';;
                     $bt='<div class="btn bg-success" style="border: 2px solid #fff; color: #fff;">Оплачен и сдан</div>';
                    break;}
      $searchdata.='<tr '.$classs.'>';
        $swichsend=new Checksend();
        $value['send_m1_2']=$swichsend->swichsend($value['send_m1_2']);
        $send_m1=$swichsend->swichsend($value['send_m1_1']);
        if(!empty($value['phone2'])){
            $value['send_m2_1']=$swichsend->swichsend($value['send_m2_1']);
            $value['send_m2_2']=$swichsend->swichsend($value['send_m2_2']);
            $value['phone2']=$value['phone2'].' -Заказ '.$value['send_m2_1'].'/ Отзыв - '.$value['send_m2_2'];}else{
            $value['phone2']='';
        }
               $searchdata.='<td aria-label="№">'.$value['nomer_zakaza'].'<hr><p style="color:red;">'.$value['nomer_lena'].'</p></td>';
                $searchdata.='<td aria-label="Дата">'.date("d-m-Y", strtotime($value['dates'])).'</td>';
                $searchdata.= '<td aria-label="Данные Клиента">'.$value['klient'].'<br>'.$value['phone1'].' - Заказ '.$send_m1.'/ Отзыв - '.$value['send_m1_2'].'<br>'.$value['phone2'].'</td>';
                $searchdata.='<td aria-label="Дата сдачи заказа">'.date("d-m-Y", strtotime($value['date_sd'])).'</td>';
                        $pro_ob=(real)$value['pro_ob']*100;
                        $pro_sh=(real)$value['pro_sh']*100;
                        $mst='';
                       if(isset($value['mebel'])){
                    $mst.='Мебель : '.$value['mebel'];
                    $mst.='</br> Ткань : '.$value['tkan'];
                    $searchdata.='<td aria-label="Мебель">'.$mst.'</td>';}else{$searchdata.='<td>Графа пуста</td>';}
                $mast='<td aria-label="Мастер">';$obivsh='<td aria-label="Обивщик">';$shvei='<td aria-label="Швея">';$disp='<td aria-label="Диспетчер">';
                if(!empty($value['master'])){
                    $chetmaster=0;
                    $chetdisp=0;
                  foreach ($value['master'] as $ke => $master){
                      switch ($master['proff']) {
                          case "мастер":
                              $p=100;
                              if(empty($chetmaster)){$labelmaster='Взял : ';}else{$labelmaster='Сдал : ';}
                              $chetmaster++;
                              $pro=(real)$master['pro']*(int)$p;
                              $mast.=$labelmaster.'</br>';
                              $mast.=$master['name'].' '.$pro.'%</br>';
                              break;
                              case "Выездной мастер":
                              $p=100;
                              if(empty($chetmaster)){$labelmaster='Взял : ';}else{$labelmaster='Сдал : ';}
                              $chetmaster++;
                              $pro=(real)$master['pro']*(int)$p;
                              $mast.=$labelmaster.'</br>';
                              $mast.=$master['name'].' '.$pro.'%</br>';
                              break;
                    case "Обивщик":
                      $p=100;
                      $pro=(real)$master['pro']*(int)$p;
                      $obivsh.= $master['name'].'</br>'.$pro.'%;</br>';
                    break;
                    case "швея":
                     $p=100;
                      $pro=(real)$master['pro']*(int)$p;
                      $shvei.=$master['name'].'</br>'.$pro.'%;</br>';
                    break;
                          case "Диспетчер":
                              $p=100;
                              if(!empty($chetdisp)){$labeldips='Сдал : ';}else{$labeldips='Взял : ';}
                              $pro=(real)$master['pro']*(int)$p;
                              $chetdisp++;
                              $disp.=$labeldips.'</br>';
                              $disp.=$master['name'].' '.$pro.'%</br>';
                              break;
                      }
                  }
                  if(isset($value['mastersplitup'])){
                        $iob=1;$ish=1;
                        foreach ($value['mastersplitup'] as $v){
                            switch ($v['proff']){
                                case "Обивщик":
                                    $p=100;
                                    $pro=(real)$v['pro']*(int)$p;
                                    $obivsh.=$v['prices'].'</br>';
                                    $obivsh.= $v['name'].'</br>'.$pro.'%;</br><hr>';
                                    $iob++;
                                    break;
                                case "швея":
                                    $p=100;
                                    $pro=(real)$v['pro']*(int)$p;
                                    $shvei.=$v['prices'].'</br>';
                                    $shvei.=$v['name'].'</br>'.$pro.'%;</br><hr>';
                                    $ish++;
                                    break;
                            }
                        }
                    }
                  $mast.='</td>';$obivsh.='</td>';$shvei.='</td>';$disp.='</td>';
                  $searchdata.= $mast;$searchdata.=$obivsh;$searchdata.=$shvei;$searchdata.=$disp;}else{$searchdata.= '<td aria-label="Мастер">Графа пуста</td><td aria-label="Обивщик">Графа пуста</td><td aria-label="Швея">Графа пуста</td><td aria-label="Диспетчер">Графа пуста</td>';}
                  if($value['type_oplata']<=1){$type_oplata="Оплата наличными";}elseif($value['type_oplata']>=2){$type_oplata="Оплата без/нал";}
                  $searchdata.='<td aria-label="Цена">'.htmlspecialchars($value['price'], ENT_QUOTES).'</br>'.$type_oplata.'</td>';
                  $searchdata.='<td aria-label="Примечание">'.htmlspecialchars($value['prim'], ENT_QUOTES).'</td>';
                 $sdacha='';
                  switch ($value['statuss']) {
                      case -1:
                     $sdacha='<a href="/manager/inwork/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-secondary">Взять в работу</a>';
                      break;
                    case '0':
                     $sdacha='<a href="/manager/gotov/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-secondary">Заказ Готов</a>';
                      break;
                    case '1':
                     $sdacha='<a href="/manager/sdacha/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-primary">Сдать заказ</a>';
                      break;
                     case '2':
                     $sdacha='';
                      break;
                  }
                  switch ($value['statuss']) {
                    case '2':
                     $edit='';
                      break;
                    default:
                     $edit='<a href="/manager/edit/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-primary">Ред</a>';
                      break;
                  }
                 $searchdata.='<td aria-label="Ред.">'.$bt.''.$sdacha.''.$edit.'</td></tr>';
            }
                            $searchdata.='</table>';
              return $searchdata;}else {return 'Ничего не найдено';}
    }






 }
