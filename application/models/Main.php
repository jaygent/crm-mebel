<?php

namespace application\models;

use application\core\Model;

class Main extends Model {

	public $error;

	public function contactValidate($post) {
		$nameLen = iconv_strlen($post['name']);
		$textLen = iconv_strlen($post['text']);
		if ($nameLen < 3 or $nameLen > 20) {
			$this->error = 'Имя должно содержать от 3 до 20 символов';
			return false;
		} elseif (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error = 'E-mail указан неверно';
			return false;
		} elseif ($textLen < 10 or $textLen > 500) {
			$this->error = 'Сообщение должно содержать от 10 до 500 символов';
			return false;
		}
		return true;
	}

	public function postsCount($route,$type) {
        if(empty($route['year'])){
            $route['year']=date('Y');
        }
		$params = [
			'id_master'=>$route['idmaster'],
			'year'=>$route['year'],
		];
		return $this->db->column('SELECT COUNT(id_zakaza) FROM zakaz WHERE idmaster=:id_master AND year=:year AND statuss IN'.$type,$params);
	}
	public function postsList($route,$type) {
		$max = 5;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
			'id_master'=>$route['idmaster'],
			'year'=>date('Y'),
		];
      //  $data=$this->db->row('SELECT * FROM zakaz as z JOIN mebel as me ON z.id_zakaza=me.id_zakaza JOIN tkani as t On z.id_zakaza=t.id_zakaza JOIN master as m ON z.idmaster=m.id_master JOIN master_pro as mp On m.id_master=mp.id_master WHERE z.idmaster=:id_master AND z.year=:year ORDER BY z.nomer_zakaza DESC LIMIT :start, :max', $params);
		$zakaz=$this->db->row('SELECT * FROM zakaz WHERE idmaster=:id_master AND year=:year AND statuss IN '.$type.' ORDER BY nomer_zakaza DESC LIMIT :start, :max', $params);
		foreach($zakaz as $keyss=>$valss){
		    $data[$keyss]=$valss;
		$params = ['id_zakaza' => $valss['id_zakaza'],];
		$mebel=$this->db->row('SELECT * FROM mebel WHERE id_zakaza=:id_zakaza', $params);
		 $data[$keyss]['mebel']=$mebel[0]['name_mebel'];
		    $parmeb = ['id_zakaza' => $valss['id_zakaza'],];
		   $tkani=$this->db->row('SELECT * FROM tkani WHERE id_zakaza=:id_zakaza', $parmeb);
		 $data[$keyss]['tkan']=$tkani[0]['name_tkani'];
		$params = ['id_zakaza' => $valss['id_zakaza'],];
		$master=$this->db->row('SELECT id,master.id_master,pro,name,proff FROM master_pro,master WHERE master.id_master=master_pro.id_master and id_zakaza=:id_zakaza ORDER BY id', $params);
		$data[$keyss]['master']=$master;
            if($valss['splitup']==1){
                $params = ['id_zakaza' => $valss['id_zakaza'],];
                $mastersplitup=$this->db->row('SELECT id_split,master.id_master,pro,name,proff,prices FROM splitup_zakaz,master WHERE master.id_master=splitup_zakaz.id_master and id_zakaza=:id_zakaza ORDER BY id_split', $params);
                $data[$keyss]['mastersplitup'] =$mastersplitup;
            }
	}
        if(empty($data)){ return $data='';}else{return $data;}
}
public function st(){
			$params="('мастер','Выездной мастер')";
$master=$this->db->row('SELECT id_master,name FROM master WHERE proff IN'.$params);
if(!empty($master)){
 foreach ($master as $val) {
 	$params=['idmaster'=>$val['id_master'],'dates'=>date("d-m-Y", strtotime("-1 month")), 'data'=>date('d-m-Y'),];
$zakaz=$this->db->row('SELECT price FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
$sumzakaz=0;
if(!empty($zakaz)){
foreach ($zakaz as $valzakaz) {
	$sumzakaz+=str_replace(' ', '', $valzakaz['price']);
}
$sumzakaz=number_format($sumzakaz, 0, '', ' ' );
$params=[
	'id'=>'',
	'id_master'=>$val['id_master'],
	'suma'=>$sumzakaz,
	'm_year'=>date('m/Y'),
];
$this->db->query('INSERT INTO static_money VALUES (:id, :id_master, :suma, :m_year)', $params);}else{echo 'false1';}
 }}else{echo 'false2';}
 echo 'true';
}

public function ischekyear($year){
	$params=['year'=>$year,];
	$year=$this->db->column('SELECT DISTINCT year FROM zakaz WHERE year=:year',$params);
	if(!empty($year)){return true;}
	return false;
}

 public function yeardata(){
	$v='';
	$params=['year'=>date('Y')];
	$year=$this->db->row('SELECT DISTINCT year FROM zakaz WHERE year<:year',$params);
	foreach ($year as $val) {
		$v.='<a href="year/'.$val['year'].'/yearposts" class="btn btn-primary">'.$val['year'].'</a>';
	}
	return $v;
}
public function postsCountyear($route,$type) {
		$params = [
			'id_master'=>$route['idmaster'],
		];
		return $this->db->column('SELECT COUNT(id_zakaza) FROM zakaz WHERE idmaster=:id_master AND statuss IN'.$type,$params);
	}
	public function postsListyear($route,$type) {
		$max = 5;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
			'id_master'=>$route['idmaster'],
			'year'=>$route['year'],
		];
		$zakaz=$this->db->row('SELECT * FROM zakaz WHERE idmaster=:id_master AND year=:year AND statuss IN '.$type.' ORDER BY nomer_zakaza DESC LIMIT :start, :max', $params);
		foreach($zakaz as $keyss=>$valss){
		    $data[$keyss]=$valss;
		$params = ['id_zakaza' => $valss['id_zakaza'],];
		$mebel=$this->db->row('SELECT * FROM mebel WHERE id_zakaza=:id_zakaza', $params);
		 $data[$keyss]['mebel']=$mebel[0]['name_mebel'];
		    $parmeb = ['id_zakaza' => $valss['id_zakaza'],];
		   $tkani=$this->db->row('SELECT * FROM tkani WHERE id_zakaza=:id_zakaza', $parmeb);
		 $data[$keyss]['tkan']=$tkani[0]['name_tkani'];
		$params = ['id_zakaza' => $valss['id_zakaza'],];
		$master=$this->db->row('SELECT id,master.id_master,pro,name,proff FROM master_pro,master WHERE master.id_master=master_pro.id_master and id_zakaza=:id_zakaza ORDER BY id', $params);
		$data[$keyss]['master']=$master;
            if($valss['splitup']==1){
                $params = ['id_zakaza' => $valss['id_zakaza'],];
                $mastersplitup=$this->db->row('SELECT id_split,master.id_master,pro,name,proff,prices FROM splitup_zakaz,master WHERE master.id_master=splitup_zakaz.id_master and id_zakaza=:id_zakaza ORDER BY id_split', $params);
                $data[$keyss]['mastersplitup'] =$mastersplitup;
            }
	}if(empty($data)){ return $data='';}else{return $data;}
}
public function masterdataarxiv(){
       return $this->db->row('SELECT id_master,name,proff,work FROM master WHERE proff NOT IN("Руководитель")');
    }
public function soket($body){
	    $phone=substr_replace($body['source'],'8',0,1);
        $params=['phone'=>$phone,
            'phoneklient'=>"%".$phone."%"];
    $zakaz=$this->db->row('SELECT * FROM zakaz WHERE phone1=:phone or phone2=:phone or klient LIKE :phoneklient', $params);
    if(!empty($zakaz)){
        $params=['idzakaza'=>$zakaz[0]['id_zakaza']];
        $mebel=$this->db->row('SELECT * FROM mebel WHERE id_zakaza=:idzakaza',$params);
        $tkani=$this->db->row('SELECT * FROM tkani WHERE id_zakaza=:idzakaza',$params);
switch ($zakaz[0]['statuss']){
    case -1:
        $statuss='В ракушке';
        break;
    case 0:
        $statuss='В работе';
        break;
    case 1:
        $statuss='Готов - не оплачен';
        break;
    case 2:
        $statuss='Сдан - оплачен';
        break;
}
    $zaka='<p>Номер заказа: '.$zakaz[0]['nomer_zakaza'].' </p>
<p>Дата взятия заказ: '.$zakaz[0]['dates'].' </p>
<p>Данные клиента: '.$zakaz[0]['klient'].'</br>'.$zakaz[0]['phone1'].'</br>'.$zakaz[0]['phone2'].'</p>
<p>Мебель: '.$mebel[0]['name_mebel'].' </p>
<p>Ткань: '.$tkani[0]['name_tkani'].' </p>
<p>Цена: '.$zakaz[0]['price'].' </p>
<p>Примечание: '.$zakaz[0]['prim'].' </p>
<p>Статус: '.$statuss.' </p>';
    $bodys='<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="$(\'#staticBackdrop\').remove();$(\'.modal-backdrop\').remove();" data-dismiss="modal"  aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
'.$zaka.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$(\'#staticBackdrop\').remove();$(\'.modal-backdrop\').remove();" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';
    return $bodys;}else{return false;}
}
}
