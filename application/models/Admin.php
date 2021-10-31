<?php

namespace application\models;

use application\core\Model;
use Dompdf\Dompdf;
use application\lib\Chatapi;
use application\lib\Checksend;
include_once  'application/lib/dompdf/autoload.inc.php';
class Admin extends Model {

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
		$prof=$this->db->row('SELECT proff,id_master FROM master WHERE login=:login', $params);
		return $prof;
	}

	public function checkRefExists($login) {
		$params = [
			'login' => $login,
		];
		return $this->db->column('SELECT login,work FROM master WHERE work=1 AND login=:login', $params);
	}
	public function zakazValidate($post, $type,$idmaster) {
		switch ($type) {
			case 'add':
			    preg_match('/(?P<year>\d+)-(?P<month>\d+)-(?P<day>\d+)/', $post['dates'], $matches);
				$params = [
			'nomer_zakaza' => $post['nomer_zakaza'],
			'idmaster'=>$idmaster,
			'year'=>$matches['year'],
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



	public function postAdd($post,$idmaster) {
	    preg_match('/(?P<year>\d+)-(?P<month>\d+)-(?P<day>\d+)/', $post['dates'], $matches);
		$params = [
			'id_zakaza' => '',
			'nomer_zakaza' => $post['nomer_zakaza'],
		    'dates' => $post['dates'],
		    'klient' => $post['klient'],
            'phone1' => $post['phone1'],
            'send_m1_1'=>0,
            'send_m1_2'=>0,
            'phone2' => $post['phone2'],
            'send_m2_1'=>0,
            'send_m2_2'=>0,
		    'price' => $post['price'],
		    'statuss' => '-1',
		    'date_sd' => '',
		    'type_oplata'=>$post['type_oplata'],
		    'prim'=>$post['prim'],
		    'pro_ob'=>'0',
		    'pro_sh'=>'0',
		    'year'=>$matches['year'],
		    'idmaster'=>$idmaster,
		    'nomer_lena'=>$post['nomer_lena'],
            'splitup'=>'0',
            'lena_status'=>'0',
		];
		$this->db->query('INSERT INTO zakaz VALUES (:id_zakaza, :nomer_zakaza, :nomer_lena, :dates, :year, :klient,:phone1, :send_m1_1,:send_m1_2, :phone2, :send_m2_1,:send_m2_2, :price, :statuss, :date_sd, :type_oplata, :prim, :pro_ob, :pro_sh, :idmaster, :splitup, :lena_status)', $params);
		$id_zakaz=$this->db->lastInsertId();
		if($id_zakaz){
		   $id_status=$this->mebeltkaniAdd($post,$id_zakaz);}
		if($id_zakaz){
			$master='';$dispetcher='';
		    if(!empty($idmaster)){$master=$this->masterAdd($post['master'],$id_zakaz,'0.03',$post['dates']);}
		   	 if(!empty($post['dispetcher'])){$dispetcher=$this->masterAdd($post['dispetcher'],$id_zakaz,'0.0125',$post['dates']);}else{$dispetcher=1;}
		   	if( empty($master) || empty($dispetcher)){
		   	    $params = ['id' => $master,'id_zakaza'=>$id_zakaz,]; $this->db->query('DELETE FROM master_pro WHERE id_zakaza=:id_zakaza and id = :id', $params);
		   	      $param = ['id' => $dispetcher,'id_zakaza'=>$id_zakaz,]; $this->db->query('DELETE FROM master_pro WHERE id_zakaza=:id_zakaza and id = :id', $param);
		   	    $param = ['id_zakaza'=>$id_zakaz,]; $this->db->query('DELETE FROM zakaz WHERE id_zakaza=:id_zakaza', $param);
		    return 0;
		}else{$chat = new Chatapi();
                $status=$chat->getStatus();
                if(!$status){
                    return $id_zakaz;
                }
                $phonet=substr_replace($post['phone1'],'7',0,1);
                if(!$chat->checkPhone($phonet)){
                    $params=[
                        'status'=>'bad contact',
                        'id_zakaza'=>$id_zakaz,
                    ];
                    $this->db->query('UPDATE zakaz SET send_m1_1=:status,send_m1_2=:status WHERE id_zakaza=:id_zakaza',$params);
                    if(empty($post['phone2'])){return $id_zakaz;}
                    $phonet=substr_replace($post['phone2'],'7',0,1);
                    if(!$chat->checkPhone($phonet)){
                        $params=[
                            'status'=>'bad contact',
                            'id_zakaza'=>$id_zakaz,
                        ];
                        $this->db->query('UPDATE zakaz SET send_m2_1=:status,send_m2_2=:status WHERE id_zakaza=:id_zakaza',$params);
                        return $id_zakaz;
                    }
                }
                $bodytext=$this->db->row('Select messagezakaz From setting');
                $bodytext=json_decode($bodytext[0]['messagezakaz']);
                $bodytext=str_replace(['{zakazid}','{syma}'],[$post['nomer_zakaza'],$post['price']],$bodytext);
                $result=$chat->sendPhoneMessage($phonet,$bodytext);
                $params=[
                    'id'=>$result->id,
                    'id_zakaza'=>$id_zakaz,
                ];
                $this->db->query('UPDATE zakaz SET send_m1_1=:id WHERE id_zakaza=:id_zakaza',$params);
                return $id_zakaz;}
	}else{return 0;} }
     public function masterAdd($id_master,$id_zakaz,$pro,$date_m){
     	$params = [
			'id' => '',
			 'id_zakaza' => $id_zakaz,
		    'id_master' => $id_master,
		    'pro' => $pro,
		    'date_m'=>$date_m,
		];
		if(!empty($id_master)){
		$this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);
		return $this->db->lastInsertId();}else{return true;}
     }

     public function mebeltkaniAdd($post,$idzakaz){
     	if(!empty($post['mebel'])){
        $params = [
			'id_mebel' => '',
			 'id_zakaza' => $idzakaz,
		    'name_mebel' => $post['mebel'],
		    'col' => '',
		];
        $this->db->query('INSERT INTO mebel VALUES (:id_mebel, :id_zakaza, :name_mebel, :col)', $params);
           $id_mebel=$this->db->lastInsertId();
           if(!empty($post['tkan'])){
         $params = [
			 'id_tkani' => '',
		    'id_zakaza' => $idzakaz,
		    'name_tkani' => $post['tkan'],
		   ];
            $this->db->query('INSERT INTO tkani VALUES (:id_tkani, :id_zakaza, :name_tkani)', $params);
          return true;
          }
           }
            else{return true;}
     }

	public function postEdit($post, $id) {
        $params=['id_zakaza'=>$id];
        $data=$this->db->row('SELECT * FROM zakaz WHERE id_zakaza=:id_zakaza', $params);
        if(isset($post['splitup'])){$splitup=1;}else{$splitup=0;}
        if($post['statuss']>0){
        if(isset($post['splitup'])){$splitup=1;}else{$splitup=0;}
            if($splitup==$data[0]['splitup']){
                switch ($splitup){
                    case 0:
                        if(!empty($post['shzakaz'])){ foreach ($post['shzakaz'] as $key=>$v) {
                            $shpar=[
                                'id'=>$key,
                                'id_master'=>$v,
                                'pro'=>$post['pro_sh']/100,
                                'date_m'=>$post['date_m'],
                            ];
                            $this->db->query('UPDATE master_pro SET id_master=:id_master,pro=:pro, date_m=:date_m  WHERE id = :id', $shpar);
                        }}
                        if(!empty($post['obzakaz'])){ foreach ($post['obzakaz'] as $key=>$v) {
                            $obpar=[
                                'id'=>$key,
                                'id_master'=>$v,
                                'pro'=>$post['pro_ob']/100,
                                'date_m'=>$post['date_m'],
                            ];
                            $this->db->query('UPDATE master_pro SET id_master=:id_master,pro=:pro, date_m=:date_m  WHERE id = :id', $obpar);
                        }}
                        break;
                    case 1:
                        if(!empty($post['ob1'])){ foreach ($post['ob1'] as $key=>$v) {
                            $shpar=[
                                'id'=>$key,
                                'id_master'=>$v,
                                'pro'=>$post['pro_ob1']/100,
                                'prices'=>$post['price1'],
                                'date_m'=>$post['date_m'],
                            ];
                            $this->db->query('UPDATE splitup_zakaz SET id_master=:id_master,pro=:pro,prices=:prices, date_m=:date_m  WHERE id_split = :id', $shpar);
                        }}
                        if(!empty($post['ob2'])){ foreach ($post['ob2'] as $key=>$v) {
                            $shpar=[
                                'id'=>$key,
                                'id_master'=>$v,
                                'pro'=>$post['pro_ob2']/100,
                                'prices'=>$post['price2'],
                                'date_m'=>$post['date_m'],
                            ];
                            $this->db->query('UPDATE splitup_zakaz SET id_master=:id_master,pro=:pro,prices=:prices, date_m=:date_m  WHERE id_split = :id', $shpar);
                        }}
                        if(!empty($post['sh1'])){ foreach ($post['sh1'] as $key=>$v) {
                            $shpar=[
                                'id'=>$key,
                                'id_master'=>$v,
                                'pro'=>$post['pro_sh1']/100,
                                'prices'=>$post['price1'],
                                'date_m'=>$post['date_m'],
                            ];
                            $this->db->query('UPDATE splitup_zakaz SET id_master=:id_master,pro=:pro,prices=:prices, date_m=:date_m  WHERE id_split = :id', $shpar);
                        }}
                        if(!empty($post['sh2'])){ foreach ($post['sh2'] as $key=>$v) {
                            $shpar=[
                                'id'=>$key,
                                'id_master'=>$v,
                                'pro'=>$post['pro_sh2']/100,
                                'prices'=>$post['price2'],
                                'date_m'=>$post['date_m'],
                            ];
                            $this->db->query('UPDATE splitup_zakaz SET id_master=:id_master,pro=:pro,prices=:prices, date_m=:date_m  WHERE id_split = :id', $shpar);
                        }}
                        break;
                }
            }else{
                switch ($splitup){
                    case 0:
                        $params=['id_zakaza'=>$id];
                        $this->db->query('DELETE FROM splitup_zakaz WHERE id_zakaza=:id_zakaza',$params);
                        $params = [
                            'id' => $id,
                        ];
                        $this->db->query('UPDATE zakaz SET splitup=0  WHERE id_zakaza = :id', $params);
                        $params = [
                            'id'=>'',
                            'id_zakaza'=>$id,
                            'id_master' => $post['obzakaz'][0],
                            'pro'=>$post['pro_ob']/100,
                            'date_m'=>$post['date_m'],
                        ];
                        $this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);
                        $params = [
                            'id'=>'',
                            'id_zakaza'=>$id,
                            'id_master' => $post['shzakaz'][0],
                            'pro'=>$post['pro_sh']/100,
                            'date_m'=>$post['date_m'],
                        ];
                        $this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);
                        break;
                    case 1:
                        $params = [
                            'id' => $id,
                        ];
                        $this->db->query('UPDATE zakaz SET splitup=1  WHERE id_zakaza = :id', $params);
                        $params = [
                            'id_zakaza' => $id,
                        ];
                        $masterid=$this->db->row('SELECT id FROM master_pro,master WHERE master.id_master=master_pro.id_master AND id_zakaza=:id_zakaza AND proff IN("швея","Обивщик")',$params);
                        foreach ($masterid as $vl){
                            $params=['id'=>$vl['id']];

                            $this->db->query('DELETE FROM master_pro  WHERE id=:id', $params);
                        }
                        $this->splitgotovmasteredit($id,$post['ob1'][0],$post['pro_ob1'],$post['price1'],1,$post['date_m']);
                        $this->splitgotovmasteredit($id,$post['ob2'][0],$post['pro_ob2'],$post['price2'],2,$post['date_m']);
                        $this->splitgotovmasteredit($id,$post['sh1'][0],$post['pro_sh1'],$post['price1'],1,$post['date_m']);
                        $this->splitgotovmasteredit($id,$post['sh2'][0],$post['pro_sh2'],$post['price2'],2,$post['date_m']);
                        break;
                }
            }}
        if(!isset($post['pro_ob'])){$post['pro_sh']=0;$post['pro_ob']=0;}
	   $params = [
			'id_zakaza' => $id,
			'nomer_zakaza'=>$post['nomer_zakaza'],
			'dates'=>$post['dates'],
			'klient'=>$post['klient'],
           'phone1'=>$post['phone1'],
           'phone2'=>$post['phone2'],
			'price'=>$post['price'],
			'date_sd'=>$post['date_sd'],
			'type_oplata'=>$post['type_oplata'],
			'prim'=>$post['prim'],
			'pro_ob'=>$post['pro_ob']/100,
			'pro_sh'=>$post['pro_sh']/100,
			'nomer_lena'=>$post['nomer_lena'],
           'splitup'=>$splitup,
           'lena_status'=>$post['lena_status'],

		];
		$zakaz=$this->db->query('UPDATE zakaz SET  nomer_zakaza=:nomer_zakaza, nomer_lena=:nomer_lena, dates=:dates, klient=:klient, phone1=:phone1,phone2=:phone2,  price=:price, date_sd=:date_sd, type_oplata=:type_oplata, prim=:prim, pro_ob=:pro_ob, pro_sh=:pro_sh, splitup=:splitup, lena_status=:lena_status WHERE id_zakaza = :id_zakaza', $params);
			$param=[
				'id_zakaza'=>$id,
				'name_mebel'=>$post['mebel'],
			];
			$this->db->query('UPDATE mebel SET name_mebel=:name_mebel  WHERE id_zakaza = :id_zakaza', $param);
			$par=[
				'id_zakaza'=>$id,
				'name_tkani'=>$post['tkan'],
			];
			 $this->db->query('UPDATE tkani SET name_tkani=:name_tkani  WHERE id_zakaza = :id_zakaza', $par);

			if(!empty($post['mastzakaz'])){ foreach ($post['mastzakaz'] as $key=>$v) {
				$masterpar=[
					'id'=>$key,
					'id_master'=>$v,
				];
				$this->db->query('UPDATE master_pro SET id_master=:id_master WHERE id = :id', $masterpar);
			}}
			/*if(!empty($post['shzakaz'])){ foreach ($post['shzakaz'] as $key=>$v) {
				$shpar=[
					'id'=>$key,
					'id_master'=>$v,
					'pro'=>$post['pro_sh']/100,
				];
				$this->db->query('UPDATE master_pro SET id_master=:id_master,pro=:pro  WHERE id = :id', $shpar);
			}}
			if(!empty($post['obzakaz'])){ foreach ($post['obzakaz'] as $key=>$v) {
				$obpar=[
					'id'=>$key,
					'id_master'=>$v,
					'pro'=>$post['pro_ob']/100,
				];
				$this->db->query('UPDATE master_pro SET id_master=:id_master,pro=:pro  WHERE id = :id', $obpar);
			}}*/
			if(!empty($post['dispzakaz'])){ foreach ($post['dispzakaz'] as $key=>$v) {
				$disppar=[
					'id'=>$key,
					'id_master'=>$v,
				];
				$this->db->query('UPDATE master_pro SET id_master=:id_master  WHERE id = :id', $disppar);
			}}
			return  true;
			}
     public function sdachaEdit($post,$id,$idmaster){
         $date=explode('-',$post['date_sd']);
         if($date[0]!==date('Y')){$this->error = 'Год сдачи заказа должен быть равен году взятия заказ'; return false;}

         $params = [
			'id_master' => $idmaster,
		];
		$mof=$this->db->column('SELECT * FROM master  WHERE id_master =:id_master and proff="Выездной мастер"', $params);
		if(!empty($mof)){$params = [
			'id' => $id,
			'date_sd' => $post['date_sd'],
			 'type_oplata'=>$post['type_oplata'],
		];
        $this->db->query('UPDATE zakaz SET statuss = 2, date_sd = :date_sd, type_oplata=:type_oplata  WHERE id_zakaza = :id', $params);
            $params = [
			'id_master' => $idmaster,
		];
		$mof=$this->db->row('SELECT pro_max FROM master  WHERE id_master =:id_master and proff="Выездной мастер"', $params);
		$pro_masterviesd=(real)$mof[0]['pro_max']/100;
		$params = [
			'id' => $id,
			 'id_master' => $idmaster,
			 'pro'=>$pro_masterviesd,
		];
        $this->db->query('UPDATE master_pro SET pro= :pro   WHERE id_zakaza = :id and id_master=:id_master', $params);
            $params = [
                'id'=>'',
                'id_zakaza'=>$id,
                'id_master' => $post['dispetcher'],
                'pro'=>'0.0125',
                'date_m'=>$post['date_sd'],
            ];
            $this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);

            return true;}
        $params = [
			'id' => $id,
			 'date_sd' => $post['date_sd'],
			 'type_oplata'=>$post['type_oplata'],
		];
        $this->db->query('UPDATE zakaz SET statuss = 2, date_sd = :date_sd, type_oplata=:type_oplata  WHERE id_zakaza = :id', $params);
  $params = [
         	'id'=>'',
         	'id_zakaza'=>$id,
			'id_master' => $post['master'],
			'pro'=>'0.03',
			'date_m'=>$post['date_sd'],
		];
		$this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);

	 $params = [
         	'id'=>'',
         	'id_zakaza'=>$id,
			'id_master' => $post['dispetcher'],
			'pro'=>'0.015',
			'date_m'=>$post['date_sd'],
		];
		$this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);
         if($post['sendotziv']){
             $chat= new Chatapi();
             $params = [
                 'id' => $id,
             ];
             $zakaz=$this->db->row('SELECT * FROM zakaz WHERE id_zakaza=:id ',$params);
             if($zakaz[0]['send_m1_1']!=='bad contact'){
                 $phonet=substr_replace($zakaz[0]['phone1'],'7',0,1);
                 $send='send_m1_2';
             }elseif($zakaz[0]['send_m2_1']!=='bad contact'){
                 if(empty($zakaz[0]['phone2'])){return true;}
                 $phonet=substr_replace($zakaz[0]['phone2'],'7',0,1);
                 $send='send_m2_2';
             }else{
                 return true;
             }
             $bodydata=$this->db->row('Select * From setting');
                 $bodytext=json_decode($bodydata[0]['messageotziv']);
             $result=$chat->sendPhoneMessage($phonet,$bodytext);
             if(!empty($result->id)){
                 $params=[
                     'id'=>$result->id,
                     'id_zakaza'=>$zakaz[0]['id_zakaza'],
                 ];
                 $this->db->query("UPDATE zakaz SET $send=:id WHERE id_zakaza=:id_zakaza",$params);
             }else{
                 $params=[
                     'id'=>print_r($result,true),
                     'id_zakaza'=>$zakaz[0]['id_zakaza'],
                 ];
                 $this->db->query("UPDATE zakaz SET $send=:id WHERE id_zakaza=:id_zakaza",$params);
             }
         }
		return true;
     }
     public function inworkEdit($id,$idmaster){
     $params = [
			'id' => $id,
		];
        $this->db->query('UPDATE zakaz SET statuss = 0 WHERE id_zakaza = :id', $params);
		return true;
     }
    public function gotovEdit($post,$id){
        $date=explode('-',$post['date_m']);
        if($date[0]!==date('Y')){$this->error = 'Год выполнения заказа должен быть равен году взятия заказ'; return false;}
        if(!isset($post['splitup'])){
            $sum=(int)$post['pro_ob']+(int)$post['pro_sh'];
            if($sum>17){$this->error = 'Сумма процентов от заказа не больше 17%'; return false;}elseif($sum<16){$this->error = 'Сумма процентов от заказа не меньше 16%'; return false;}
            $params = [
                'id' => $id,
                'pro_ob'=>$post['pro_ob']/100,
                'pro_sh'=>$post['pro_sh']/100,
            ];
            $this->db->query('UPDATE zakaz SET statuss = 1, pro_sh=:pro_sh, pro_ob=:pro_ob  WHERE id_zakaza = :id', $params);
            $params = [
                'id'=>'',
                'id_zakaza'=>$id,
                'id_master' => $post['ob'],
                'pro'=>$post['pro_ob']/100,
                'date_m'=>$post['date_m'],
            ];
            $this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);
            $params = [
                'id'=>'',
                'id_zakaza'=>$id,
                'id_master' => $post['sh'],
                'pro'=>$post['pro_sh']/100,
                'date_m'=>$post['date_m'],
            ];
            $this->db->query('INSERT INTO master_pro VALUES (:id, :id_zakaza, :id_master, :pro, :date_m)', $params);
            return true;}else{
            $sum=(int)$post['pro_ob1']+(int)$post['pro_sh1'];
            if($sum>17){$this->error = 'Сумма процентов во втором разделении от заказа не больше 17%'; return false;}elseif($sum<16){$this->error = 'Сумма процентов во втором разделении от заказа не меньше 16%'; return false;}
            $sum=(int)$post['pro_ob2']+(int)$post['pro_sh2'];
            if($sum>17){$this->error = 'Сумма процентов во втором разделении от заказа не больше 17%'; return false;}elseif($sum<16){$this->error = 'Сумма процентов во втором разделении от заказа не меньше 16%'; return false;}
            $sum=(real)str_replace(' ', '', $post['price1'])+(real)str_replace(' ', '', $post['price2']);
            $pricezakaz=$this->db->row('SELECT price FROM zakaz WHERE id_zakaza='.$id);
            $pricezakaz=(real)str_replace(' ', '', $pricezakaz[0]['price']);
            if($sum!==$pricezakaz){
                $this->error = 'Сумма двух разделенных заказов больше чем общая стоимость заказа'; return false;
            }
            $params = [
                'id' => $id,
                'pro_ob'=>$post['pro_ob1']/100,
                'pro_sh'=>$post['pro_sh2']/100,
                'splitup'=>1,
            ];
            $this->db->query('UPDATE zakaz SET statuss = 1, pro_sh=:pro_sh, pro_ob=:pro_ob, splitup=:splitup  WHERE id_zakaza = :id', $params);

            $this->splitgotovmasteredit($id,$post['ob1'],$post['pro_ob1'],$post['price1'],1,$post['date_m']);
            $this->splitgotovmasteredit($id,$post['ob2'],$post['pro_ob2'],$post['price2'],2,$post['date_m']);
            $this->splitgotovmasteredit($id,$post['sh1'],$post['pro_sh1'],$post['price1'],1,$post['date_m']);
            $this->splitgotovmasteredit($id,$post['sh2'],$post['pro_sh2'],$post['price2'],2,$post['date_m']);
            return true;
        }
    }
    public function splitgotovmasteredit($id,$id_master,$pro,$price,$part,$date){
        $params = [
            'id_split'=>'',
            'id_zakaza'=>$id,
            'id_master' => $id_master,
            'pro'=>$pro/100,
            'prices'=>$price,
            'date_m'=>$date,
            'part'=>$part,
        ];
        $this->db->query('INSERT INTO splitup_zakaz VALUES (:id_split, :id_zakaza, :id_master, :pro, :prices, :date_m, :part)', $params);

    }

	public function isPostExists($id) {
		$params = [
			'id_zakaza' => $id,
		];
		return $this->db->column('SELECT * FROM zakaz  WHERE id_zakaza = :id_zakaza', $params);
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
		public function isinworkExists($route) {
		$params = [
			'id_zakaza' => $route['id'],
			'idmaster'=>$route['idmaster'],
		];
		return $this->db->column('SELECT * FROM zakaz  WHERE statuss=-1 and id_zakaza = :id_zakaza and idmaster=:idmaster', $params);
	}
	public function isGotovExists($route) {
		$params = [
			'id_zakaza' => $route['id'],
			'idmaster'=>$route['idmaster'],
		];
		return $this->db->column('SELECT * FROM zakaz  WHERE statuss=0 and id_zakaza = :id_zakaza and idmaster=:idmaster', $params);
	}
	public function issotExists($id) {
		$params = [
			'id_master' => $id,
		];
		return $this->db->column('SELECT * FROM master  WHERE id_master = :id_master', $params);
	}

	public function postDelete($id) {
		$params = [
			'id' => $id,
		];
		$this->db->query('UPDATE zakaz SET statuss = 4  WHERE id_zakaza = :id', $params);
	}
	public function otpusk($id) {
	    $params = [
			'id' => $id,
		];
		$work=$this->db->row('SELECT work FROM master WHERE id_master = :id', $params);
		switch ($work[0]['work']) {
			case '0':
				$params = [
			'id' => $id,
		];
		$this->db->query('UPDATE master SET work = 1  WHERE id_master = :id', $params);
				break;
			case '1':
			$params = [
			'id' => $id,
		];
		$this->db->query('UPDATE master SET work = 0  WHERE id_master = :id', $params);
				break;
		}
	}
	public function sotDelete($id) {
		$params = [
			'id_master' => $id,
		];
		$this->db->query('UPDATE master SET del_status = 1  WHERE id_master = :id_master', $params);
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
		$master=$this->db->row('SELECT id,master.id_master,pro,name,proff,date_m FROM master_pro,master WHERE master.id_master=master_pro.id_master and id_zakaza=:id_zakaza ORDER by id', $params);
		foreach($master as $k=>$cv){
		    $data[$keyss]['master'][$cv['id']]=$cv;
		}
            if(!empty($valss['splitup'])){
                $params = ['id_zakaza' => $valss['id_zakaza'],];
                $mastersplitup=$this->db->row('SELECT id_split,master.id_master,pro,name,proff,prices,part,date_m FROM splitup_zakaz,master WHERE master.id_master=splitup_zakaz.id_master and id_zakaza=:id_zakaza ORDER BY id_split', $params);
                $data[$keyss]['mastersplitup'] =$mastersplitup;
            }

	}return $data;

	}
	public function masterdata(){
       return $this->db->row('SELECT id_master,name,proff,work FROM master WHERE work=1 and del_status=0 and proff NOT IN("Руководитель") ');
    }
    public function masterdatasot(){
       return $this->db->row('SELECT id_master,name,proff,work FROM master WHERE del_status=0 and proff NOT IN("Руководитель") ');
    }
    public function zarplata($post){
    	$params="('Выездной мастер')";
    	$master=$this->db->row('SELECT id_master,name,proff,work FROM master WHERE proff IN'.$params);
    	$churna=['id_master'=>'1','name'=>'Общий журнал','work'=>'1',];
    	array_push($master,$churna);
     	$zptext='<div class="tab-content" id="pills-tabContent">';
    	$params=[
    		'dates'=>$post['data_first'],
    		'data'=>$post['data_last'],
            'id_master'=>$post['master'],
    	];
        $zp=$this->db->row('SELECT * FROM zakaz,master_pro WHERE zakaz.id_zakaza=master_pro.id_zakaza AND id_master=:id_master AND statuss<=2 AND date_m BETWEEN :dates AND :data ORDER BY zakaz.nomer_zakaza', $params);
        $zpsplit=$this->db->row('SELECT * FROM zakaz,splitup_zakaz WHERE zakaz.id_zakaza=splitup_zakaz.id_zakaza AND id_master=:id_master AND statuss<=2 AND date_m BETWEEN :dates AND :data ORDER BY zakaz.nomer_zakaza', $params);
       $mas=array();
    	if(count($zp)>2){
    		for ($s=0; $s<count($zp); $s++) {
    			$i=$s+1;
    			if($i>=count($zp)){$i='0';}
    			if($zp[$s]['id_zakaza']==$zp[$i]['id_zakaza']){
    			$zp[$s]['pro']=(real)$zp[$s]['pro']+(real)$zp[$i]['pro'];
    			$mas[]=$i;
    		}
    		}
    		foreach ($mas as $v) {
    			unset($zp[$v]);
    		}}
    		$zp=array_merge($zp,$zpsplit);
        if(empty($zp)){return '<p>Ничего не найдено</p>';}
        $chetsplit=1;
    	foreach ($zp as $value) {
    		if(empty($zpsplit)){$value['splitup']=0;}
            if($value['splitup']==1){
            		switch ($chetsplit) {
            			case 3:
            				$chetsplit=1;
            				break;

            		}
                    $zakaztext='';
                    $zakaztext.='<tr><th scope="row">'.$value['nomer_zakaza'].'/'.$chetsplit.' </th>';
                    $zakaztext.='<td>'.$value['prices'].' </td>';
                    $pro=(real)$value['pro']*100;
                    $zakaztext.='<td>'.$pro.'%</td>';
                    $zpr=(real)$value['pro']*(real)str_replace(' ', '', $value['prices']);
                    $zakaztext.='<td>'.$zpr.' RUB</td></tr>';
                    for($i=0;$i<count($master);$i++){
                        if($master[$i]['id_master']==$value['idmaster']){
                            $s=$value['idmaster'];
                            $id=$value['id_zakaza'].'/'.$chetsplit;
                            if(!isset($churnal[$s]['pricetotal'])){$churnal[$s]['pricetotal']=0;$churnal[$s]['pricetototlazakaza']=0;}
                            $churnalmaster[$s]['id_master']=$s;
                            $churnal[$s][$id]['text']="$zakaztext";
                            $churnal[$s]['pricetotal']+=$zpr;
                            $churnal[$s]['pricetototlazakaza']+=(real)str_replace(' ', '', $value['prices']);
                        }
                    }
                    $chetsplit++;
            }else{
                $zakaztext='';
                $zakaztext.='<tr><th scope="row">'.$value['nomer_zakaza'].' </th>';
                $zakaztext.='<td>'.$value['price'].' </td>';
        		$pro=(real)$value['pro']*100;
                $zakaztext.='<td>'.$pro.'%</td>';
                $zpr=(real)$value['pro']*(real)str_replace(' ', '', $value['price']);
                $zakaztext.='<td>'.$zpr.' RUB</td></tr>';
                for($i=0;$i<count($master);$i++){
    			if($master[$i]['id_master']==$value['idmaster']){
    				$s=$value['idmaster'];
    				$id=$value['id_zakaza'];
    				if(!isset($churnal[$s]['pricetotal'])){$churnal[$s]['pricetotal']=0;$churnal[$s]['pricetototlazakaza']=0;}
    				$churnalmaster[$s]['id_master']=$s;
    				$churnal[$s][$id]['text']="$zakaztext";
    				$churnal[$s]['pricetotal']+=$zpr;
    				$churnal[$s]['pricetototlazakaza']+=(real)str_replace(' ', '', $value['price']);
    			}
    		}
    			}
    		}
    			$m='<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">';
    	$chekm=0;
    	foreach ($master as $val) {
    		foreach ($churnalmaster as $key => $value) {
    		if($val['id_master']==$churnalmaster[$key]['id_master']){
    			if(empty($chekm)){$activem='active';$chekm++;}else{$activem='';}
    		$m.='<li class="nav-item"><a class="nav-link '.$activem.'" data-toggle="pill" href="#master'.$val['id_master'].'" role="tab" aria-controls="pills-home" aria-selected="true">Журнал мастера '.$val['name'].'</a></li>';
    	}
	    	}
    	}
    	$m.='</ul>';
    	$zptext.=$m;
    	$zptext.='<div class="tab-content" id="pills-tabContent">';
    			$chek=0;
    			$ototal=0;
    			foreach ($churnal as $key => $value) {
    				if(empty($chek)){$active='active show';$chek++;}else{$active='';}
    				$zptext.='<div class="tab-pane fade '.$active.'" id="master'.$key.'" role="tabpanel" aria-labelledby="pills-master'.$key.'-tab"><table class="table"><thead><tr><th scope="col">№ Заказа</th><th scope="col">Сумма заказа</th><th scope="col">Процент с заказа</th><th scope="col">Сумма</th></tr></thead><tbody>';
    				foreach ($value as $va) {
    					$zptext.=$va['text'];
    				}
    				$params=['id_master'=>$key];
    				$namemaster=$this->db->row('SELECT name FROM master WHERE id_master=:id_master',$params);
    				if(empty($namemaster)){$namemaster[0]=['name'=>'Общий',];}
    				$zptext.='<tr><td>Итого:</td><td>'.$value['pricetototlazakaza'].'</td><td>Итого по журналу '.$namemaster[0]['name'].' :</td><td>'.$value['pricetotal'].' RUB</td></tr>';
    				$ototal+=$value['pricetotal'];
    				$zptext.='</tbody></table></div>';
    			}
    			$zptext.='</div>';
    			$params=['id_master'=>$post['master']];
    			$namezp=$this->db->row('SELECT name FROM master WHERE id_master=:id_master',$params);
    			$zptext.='Общая сумма зарплаты для <i style="font-size:25px;color:green;">'.$namezp[0]['name'].'</i> за переиод времени c '.$post['data_first'].' по '.$post['data_last'].' составитла '.$ototal.' RUB';
                $html='<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><style type="text/css">body { font-family: DejaVu Sans } * { font-family: DejaVu Sans;font-size: 14px;line-height: 14px;}table{margin:0 0 15px 0;width:100%;border-collapse:collapse;border-spacing:0}table td{padding:5px;border:1px solid black}table tr{padding:5px;border:2px solid black;}table th{padding:5px;font-weight:bold;border:2px solid black;}.header{margin:0;padding:0 0 15px 0;font-size:12px;line-height:12px;text-align:center}</style></head><body>';
        if(isset($post['savefile'])){
                $ototalpdf=0;
                foreach ($churnal as $key => $value) {
            $params=['id_master'=>$key];
            $namemaster=$this->db->row('SELECT name FROM master WHERE id_master=:id_master',$params);
            if(empty($namemaster)){$namemaster[0]=['name'=>'Общий',];}
            $html.='<h1> Журнал мастера '.$namemaster[0]['name'].'</h1>';
            $html.='<table class="table"><thead><tr><th scope="col">№ Заказа</th><th scope="col">Сумма заказа</th><th scope="col">Процент с заказа</th><th scope="col">Сумма</th></tr></thead><tbody>';
            foreach ($value as $va) {
                $html.=$va['text'];
            }
            $html.='<tr><td>Итого:</td><td>'.$value['pricetototlazakaza'].'</td><td>Итого по журналу '.$namemaster[0]['name'].' :</td><td>'.$value['pricetotal'].' RUB</td></tr>';
            $ototalpdf+=$value['pricetotal'];
            $html.='</tbody></table>';
        }
            $params=['id_master'=>$post['master']];
            $namemaster=$this->db->row('SELECT login FROM master WHERE id_master=:id_master',$params);
        $html.='Общая сумма зарплаты для <i style="font-size:25px;color:green;">'.$namemaster[0]['name'].'</i> за переиод времени c '.$post['data_first'].' по '.$post['data_last'].' составитла '.$ototal.' RUB';
        $html.='</body></html>';
                $rand=rand();
                $name=$this->translit($namemaster[0]['login']);
                $filename=date('Ymd').''.$rand.''.$name;
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdf = $dompdf->output();
        file_put_contents('application/file/'.$filename.'.pdf', $pdf);
        $zptext='<a href="/application/file/'.$filename.'.pdf">'.$filename.'.pdf</a>'.$zptext;
        }
        return $zptext;
                      }
          public function translit($value)
{
	$converter = array(
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

		'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
		'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
		'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
		'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
		'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
		'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
		'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
	);

	$value = strtr($value, $converter);
	return $value;
}
     public function masteredit($post,$id_master){
                      $params=['id_master'=>$id_master,
                      'name'=>$post['name'],'proff'=>$post['proff'],'password'=>password_hash($post['password'], PASSWORD_BCRYPT)];
                       $this->db->row('UPDATE master SET name =:name, proff=:proff,password=:password  WHERE id_master =:id_master', $params);
                  }
    public function mastereditadd($post){
                      $params=['id_master'=>'',
                      'name'=>$post['name'],'proff'=>$post['proff'],'login'=>$post['login'],'password'=>password_hash($post['password'], PASSWORD_BCRYPT),'master_churnal'=>'','work'=>'0','pro_max'=>$post['max_pro'],'del_status'=>'0',];
                       $this->db->row('INSERT INTO master VALUES (:id_master, :name,:login, :password, :master_churnal, :proff,:work, :pro_max, :del_status)', $params);
                  }
    public function mastereditdata($id_master){
                      $params=['id_master'=>$id_master];
                       return $this->db->row('SELECT * FROM `master` WHERE id_master=:id_master', $params);
                  }

	public function adminupdate($post){
		if($post['password']===$post['password1']){
                      $params=['login'=>'admin',
                      'password'=>password_hash($post['password'], PASSWORD_BCRYPT),];
                       $this->db->row('UPDATE master SET password =:password  WHERE login=:login', $params); return true;}else{return false;}
                  }
   public function zakazcheksum(){
				$params=[
					'dates'=>mktime(0, 0, 0, date("Y")  , date("m"), date("d")-30),
					'data'=>date('Y-m-d'),
				];
				return $this->db->row('SELECT price,statuss FROM zakaz WHERE statuss<=2 AND dates BETWEEN :dates AND :data', $params);
                  }

   public function searchid($post){
   	switch ($post['type']) {
   		case '0':
   		$params=['search'=>"%".$post['ref']."%", ];
   			$dat=$this->db->row('SELECT id_zakaza FROM zakaz WHERE statuss<4 AND nomer_zakaza LIKE :search OR nomer_lena LIKE :search', $params);
   			$d='(';
                        foreach($dat as $val){
                         $d.=$val['id_zakaza'].',';
                        }  $dat=substr($d,0,-1);
                         $dat.=')';
   			break;
   		case '1':
   			$params=['search'=>"%".$post['ref']."%",];
   			$dat=$this->db->row('SELECT id_zakaza FROM zakaz WHERE statuss<4 AND klient LIKE :search or phone1 LIKE :search OR phone2 LIKE :search', $params);
   			$d='(';
                        foreach($dat as $val){
                         $d.=$val['id_zakaza'].',';
                        }  $dat=substr($d,0,-1);
                        $dat.=')';
   			break;
   	}
   	if(!empty($dat)){
   		$par=['year'=>$post['data'],];
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
                     $sdacha='<a href="/admin/inwork/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-secondary">Взять в работу</a>';
                      break;
                    case '0':
                     $sdacha='<a href="/admin/gotov/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-secondary">Заказ Готов</a>';
                      break;
                    case '1':
                     $sdacha='<a href="/admin/sdacha/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-primary">Сдать заказ</a>';
                      break;
                     case '2':
                     $sdacha='';
                      break;
                  }
                 $searchdata.='<td aria-label="Ред.">'.$bt.''.$sdacha.'<a href="/admin/edit/'.$value['idmaster'].'/'.$value["id_zakaza"].'" class="btn btn-primary">Ред</a></td></tr>';
            }
                            $searchdata.='</table>';


              return $searchdata;}else {return 'Ничего не найдено';}
    }
    public function staticdatapost($post){
        $params="('Выездной мастер')";
        $master=$this->db->row('SELECT id_master,name,proff FROM master WHERE proff IN'.$params);
        $fin='';
        if(!empty($master)){

                $totalsum=0;
                $totalsumrak=0;
                $totalsumrab=0;
                $totalsumgotov=0;
                $totalsumsdan=0;
                foreach ($master as $val) {
                    $params=['idmaster'=>$val['id_master'],'dates'=>$post['data_first'], 'data'=>$post['data_last'],];
                    $zakaz=$this->db->row('SELECT price,statuss FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
                    $sumzakaz=0;
                    $sumzakazrab=0;
                    $sumzakazrak=0;
                    $sumzakazgotov=0;
                    $sumzakazsdan=0;
                     $fin .= '<div><label> По журналу мастера ' . $val['name'] . ' за период ' . $post['data_first'] . ' по ' . $post['data_last'] . '</label>';
                    if(!empty($zakaz)){
                        foreach ($zakaz as $valzakaz) {
                            $sumzakaz+=str_replace(' ', '', $valzakaz['price']);
                            switch ($valzakaz['statuss']){
                                case -1:
                                    $sumzakazrak+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                                case 0:
                                    $sumzakazrab+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                                case 1:
                                    $sumzakazgotov+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                                case 2:
                                    $sumzakazsdan+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                            }
                        }
                        $totalsum+=$sumzakaz;
                        $totalsumrak+=$sumzakazrak;
                        $totalsumrab+=$sumzakazrab;
                        $totalsumgotov+=$sumzakazgotov;
                        $totalsumsdan+=$sumzakazsdan;
                        $sumzakaz=number_format($sumzakaz, 0, '', ' ' );
                    }
                    $fin.='<span style="font-size:20px;color:purple; margin:0 10px;">'.$sumzakaz.' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$sumzakazsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$sumzakazgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$sumzakazrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке '.$sumzakazrak.' RUB</span></div>';
                }
                $params=['idmaster'=>'1','dates'=>$post['data_first'], 'data'=>$post['data_last'],];
                $zakaz=$this->db->row('SELECT price,statuss FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
                $sumzakaz=0;
                $sumzakazrab=0;
                $sumzakazrak=0;
                $sumzakazgotov=0;
                $sumzakazsdan=0;
                $fin.='<div><label> По общему журналу за период '.$post['data_first'].' по '.$post['data_last'].'</label>';
                if(!empty($zakaz)){
                    foreach ($zakaz as $valzakaz) {
                        $sumzakaz+=str_replace(' ', '', $valzakaz['price']);
                        switch ($valzakaz['statuss']){
                            case -1:
                                $sumzakazrak+=str_replace(' ', '', $valzakaz['price']);
                                break;
                            case 0:
                                $sumzakazrab+=str_replace(' ', '', $valzakaz['price']);
                                break;
                            case 1:
                                $sumzakazgotov+=str_replace(' ', '', $valzakaz['price']);
                                break;
                            case 2:
                                $sumzakazsdan+=str_replace(' ', '', $valzakaz['price']);
                                break;
                        }
                    }
                    $totalsum+=$sumzakaz;
                    $totalsumrab+=$sumzakazrab;
                    $totalsumrak+=$sumzakazrak;
                    $totalsumgotov+=$sumzakazgotov;$totalsumsdan+=$sumzakazsdan;
                    $sumzakaz=number_format($sumzakaz, 0, '', ' ' );
                }
                $fin.='<span style="font-size:20px;color:purple; margin:0 10px;">'.$sumzakaz.' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$sumzakazsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$sumzakazgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$sumzakazrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке '.$sumzakazrak.' RUB</span></div>';
                $totalsum=number_format($totalsum, 0, '', ' ' );
                    $fin .= '<p>Итого за период ' . $post['data_first'] . ' по ' . $post['data_last'] . ' составила <span style="font-size:20px;color:purple; margin:0 10px;">' . $totalsum . ' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$totalsumsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$totalsumgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$totalsumrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке  '.$totalsumrak.' RUB</span></p><hr>';

        }
        return $fin;
    }
    public function staticyear(){
        $year=$this->db->row('SELECT year FROM zakaz GROUP By year');
        foreach ($year as $years){
            $run[$years['year']]=$this->staticyearstat($years['year']);
        }
        return $run;
    }
    public function staticyearstat($year){
        $params="('Выездной мастер')";
        $master=$this->db->row('SELECT id_master,name,proff FROM master WHERE proff IN'.$params);
        $fin='';
        if(!empty($master)){
            $mounth=12;
            for($i=$mounth;$i>1;$i--){
                $m=$i-1;
                $totalsum=0;
                $totalsumrak=0;
                $totalsumrab=0;
                $totalsumgotov=0;
                $totalsumsdan=0;
                foreach ($master as $val) {
                    $params=['idmaster'=>$val['id_master'],'dates'=>date("$year-$m-15"), 'data'=>date("$year-$i-14"),];
                    $zakaz=$this->db->row('SELECT price,statuss FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
                    $sumzakaz=0;
                    $sumzakazrab=0;
                    $sumzakazrak=0;
                    $sumzakazgotov=0;
                    $sumzakazsdan=0;

                        $fin .= '<div><label> По журналу мастера ' . $val['name'] . ' за период ' . date("15-$m-$year") . ' по ' . date("14-$i-$year") . '</label>';
                    if(!empty($zakaz)){
                        foreach ($zakaz as $valzakaz) {
                            $sumzakaz+=str_replace(' ', '', $valzakaz['price']);
                            switch ($valzakaz['statuss']){
                                case -1:
                                    $sumzakazrak+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                                case 0:
                                    $sumzakazrab+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                                case 1:
                                    $sumzakazgotov+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                                case 2:
                                    $sumzakazsdan+=str_replace(' ', '', $valzakaz['price']);
                                    break;
                            }
                        }
                        $totalsum+=$sumzakaz;
                        $totalsumrak+=$sumzakazrak;
                        $totalsumrab+=$sumzakazrab;
                        $totalsumgotov+=$sumzakazgotov;
                        $totalsumsdan+=$sumzakazsdan;
                        $sumzakaz=number_format($sumzakaz, 0, '', ' ' );
                    }
                    $fin.='<span style="font-size:20px;color:purple; margin:0 10px;">'.$sumzakaz.' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$sumzakazsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$sumzakazgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$sumzakazrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке '.$sumzakazrak.' RUB</span></div>';
                }
                $params=['idmaster'=>'1','dates'=>date("$year-$m-15"), 'data'=>date("$year-$i-14"),];
                $zakaz=$this->db->row('SELECT price,statuss FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
                $sumzakaz=0;
                $sumzakazrab=0;
                $sumzakazrak=0;
                $sumzakazgotov=0;
                $sumzakazsdan=0;
                $fin.='<div><label> По общему журналу за период '.date("15-$m-$year").' по '.date("14-$i-$year").'</label>';
                if(!empty($zakaz)){
                    foreach ($zakaz as $valzakaz) {
                        $sumzakaz+=str_replace(' ', '', $valzakaz['price']);
                        switch ($valzakaz['statuss']){
                            case -1:
                                $sumzakazrak+=str_replace(' ', '', $valzakaz['price']);
                                break;
                            case 0:
                                $sumzakazrab+=str_replace(' ', '', $valzakaz['price']);
                                break;
                            case 1:
                                $sumzakazgotov+=str_replace(' ', '', $valzakaz['price']);
                                break;
                            case 2:
                                $sumzakazsdan+=str_replace(' ', '', $valzakaz['price']);
                                break;
                        }
                    }
                    $totalsum+=$sumzakaz;
                    $totalsumrab+=$sumzakazrab;
                    $totalsumrak+=$sumzakazrak;
                    $totalsumgotov+=$sumzakazgotov;$totalsumsdan+=$sumzakazsdan;
                    $sumzakaz=number_format($sumzakaz, 0, '', ' ' );
                }
                $fin.='<span style="font-size:20px;color:purple; margin:0 10px;">'.$sumzakaz.' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$sumzakazsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$sumzakazgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$sumzakazrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке '.$sumzakazrak.' RUB</span></div>';
                $totalsum=number_format($totalsum, 0, '', ' ' );
                                    $fin .= '<p>Итого за период ' . date("15-$m-$year", strtotime("-1 month")) . ' по ' . date("14-$i-$year") . ' составила <span style="font-size:20px;color:purple; margin:0 10px;">' . $totalsum . ' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$totalsumsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$totalsumgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$totalsumrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке  '.$totalsumrak.' RUB</span></p><hr>';
                }

        }
        return $fin;
    }
 public function staticdata(){
        	$params="('Выездной мастер')";
$master=$this->db->row('SELECT id_master,name,proff FROM master WHERE proff IN'.$params);
$fin='';
if(!empty($master)){
$mounth=date('m');
$mounth++;
if($mounth>13){$mounth=1;}
 for($i=$mounth;$i>1;$i--){
 	$m=$i-1;
 $totalsum=0;
  $totalsumrak=0;
 $totalsumrab=0;
 $totalsumgotov=0;
 $totalsumsdan=0;
foreach ($master as $val) {
    if($mounth==12){$params=['idmaster'=>$val['id_master'],'dates'=>date("Y-$m-15"), 'data'=>date("Y-$i-30"),];}else{
        $params=['idmaster'=>$val['id_master'],'dates'=>date("Y-$m-14"), 'data'=>date("Y-$i-13"),];
    }
$zakaz=$this->db->row('SELECT price,statuss FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
$sumzakaz=0;
    $sumzakazrab=0;
    $sumzakazrak=0;
    $sumzakazgotov=0;
    $sumzakazsdan=0;
    if($mounth!==12) {
        $fin .= '<div><label> По журналу мастера ' . $val['name'] . ' за период ' . date("14-$m-Y") . ' по ' . date("13-$i-Y") . '</label>';
    }else{  $fin .= '<div><label> По журналу мастера ' . $val['name'] . ' за период ' . date("15-$m-Y") . ' по ' . date("30-$i-Y") . '</label>';
    }
    if(!empty($zakaz)){
foreach ($zakaz as $valzakaz) {
	$sumzakaz+=str_replace(' ', '', $valzakaz['price']);
    switch ($valzakaz['statuss']){
         case -1:
            $sumzakazrak+=str_replace(' ', '', $valzakaz['price']);
            break;
        case 0:
            $sumzakazrab+=str_replace(' ', '', $valzakaz['price']);
            break;
        case 1:
            $sumzakazgotov+=str_replace(' ', '', $valzakaz['price']);
            break;
        case 2:
            $sumzakazsdan+=str_replace(' ', '', $valzakaz['price']);
            break;
    }
}
$totalsum+=$sumzakaz;
$totalsumrak+=$sumzakazrak;
$totalsumrab+=$sumzakazrab;
$totalsumgotov+=$sumzakazgotov;
$totalsumsdan+=$sumzakazsdan;
$sumzakaz=number_format($sumzakaz, 0, '', ' ' );
}
    $fin.='<span style="font-size:20px;color:purple; margin:0 10px;">'.$sumzakaz.' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$sumzakazsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$sumzakazgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$sumzakazrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке '.$sumzakazrak.' RUB</span></div>';
}
     if($mounth==12){$params=['idmaster'=>'1','dates'=>date("Y-$m-15"), 'data'=>date("Y-$i-30"),];}else{
         $params=['idmaster'=>'1','dates'=>date("Y-$m-14"), 'data'=>date("Y-$i-13"),];
     }
$zakaz=$this->db->row('SELECT price,statuss FROM zakaz WHERE idmaster=:idmaster and dates BETWEEN :dates AND :data',$params);
$sumzakaz=0;
$sumzakazrab=0;
$sumzakazrak=0;
$sumzakazgotov=0;
$sumzakazsdan=0;
$fin.='<div><label> По общему журналу за период '.date("14-$m-Y").' по '.date("13-$i-Y").'</label>';
if(!empty($zakaz)){
foreach ($zakaz as $valzakaz) {
    $sumzakaz+=str_replace(' ', '', $valzakaz['price']);
	switch ($valzakaz['statuss']){
	    case -1:
            $sumzakazrak+=str_replace(' ', '', $valzakaz['price']);
            break;
        case 0:
            $sumzakazrab+=str_replace(' ', '', $valzakaz['price']);
            break;
        case 1:
            $sumzakazgotov+=str_replace(' ', '', $valzakaz['price']);
            break;
        case 2:
            $sumzakazsdan+=str_replace(' ', '', $valzakaz['price']);
            break;
    }
}
$totalsum+=$sumzakaz;
$totalsumrab+=$sumzakazrab;
$totalsumrak+=$sumzakazrak;
$totalsumgotov+=$sumzakazgotov;$totalsumsdan+=$sumzakazsdan;
$sumzakaz=number_format($sumzakaz, 0, '', ' ' );
}
$fin.='<span style="font-size:20px;color:purple; margin:0 10px;">'.$sumzakaz.' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$sumzakazsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$sumzakazgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$sumzakazrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке '.$sumzakazrak.' RUB</span></div>';
$totalsum=number_format($totalsum, 0, '', ' ' );
if($mounth!==12) {
    $fin .= '<p>Итого за период ' . date("14-$m-Y", strtotime("-1 month")) . ' по ' . date("13-$i-Y") . ' составила <span style="font-size:20px;color:purple; margin:0 10px;">' . $totalsum . ' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$totalsumsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$totalsumgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$totalsumrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке  '.$totalsumrak.' RUB</span></p><hr>';
}else{    $fin .= '<p>Итого за период ' . date("15-$m-Y", strtotime("-1 month")) . ' по ' . date("30-$i-Y") . ' составила <span style="font-size:20px;color:purple; margin:0 10px;">' . $totalsum . ' RUB</span><span style="font-size:20px;color:green; margin:0 10px;">'.$totalsumsdan.' RUB</span><span style="font-size:20px;color:yellowgreen; margin:0 10px;">'.$totalsumgotov.' RUB</span><span style="font-size:20px;color:red; margin:0 10px;">'.$totalsumrab.' RUB</span><span style="font-size:20px;color:#FF3366; margin:0 10px;"> В ракушке  '.$totalsumrak.' RUB</span></p><hr>';
}}

    }
return $fin;
 }
 public function mastereditdatae(){
        $params='("мастер","Выездной мастре")';
        return $this->db->row('SELECT id_master,name,master_churnal FROM `master` WHERE proff IN'.$params);
    }

public function lenadata(){
	    $zakaz=$this->db->row('SELECT id_zakaza,nomer_zakaza,nomer_lena,dates,price FROM zakaz WHERE nomer_lena != 0 and lena_status=0 ORDER BY nomer_lena');
	    $zp='';
	    $i=1;
	    foreach ($zakaz as $item){
	        $text=' № '.$item['nomer_zakaza'].' / № Заказа Лены-'.$item['nomer_lena'].' Дата взятия заказа: '.$item['dates'].' Сумма заказа: '.$item['price'];
            $zp.='<div class="custom-control custom-switch">
  <input type="checkbox"  class="custom-control-input" data-status="0" data-price="'.str_replace(' ', '', $item['price']).'" id="customSwitch'.$i.'" name="lena['.$item['id_zakaza'].']">
<label class="custom-control-label" for="customSwitch'.$i.'">'.$text.'</label>
</div>';
            $i++;
        }
	    if(empty($zp)){$zp='Нету готовых заказов для сдачи %</br>';}
	    return $zp;
 }
    public function lenachek($post){
        foreach ($post['lena'] as $key=>$val) {
        $params=['id'=>$key];
            $this->db->query('UPDATE zakaz SET lena_status=1 WHERE id_zakaza=:id',$params);
        }
        return true;
    }
    public function masterrasxod()
    {
        return $this->db->row('SELECT id_master,name,proff,work FROM master WHERE del_status=0 and proff NOT IN("Руководитель") and name NOT IN("Руководитель Дима")');
    }
    public function rasxod($date)
    {
        $master=$this->masterrasxod();
        $params=['date'=>$date,];
        $tab=$this->db->row('SELECT id,id_master,date,money,com FROM rasxod WHERE date=:date',$params);
        $tabname='';
        $tabarxiv=array();
        $x=0;
        foreach ($master as $m){
            $x++;
           $idmaster=$m['id_master'];
            foreach ($tab as $t){
                if($idmaster==$t['id_master']){
                $date=$t['date'];
                $id=$t['id'];
                $tabarxiv[$date][$idmaster][$x][$id]['money']=$t['money'];
                $tabarxiv[$date][$idmaster][$x][$id]['com']=$t['com'];
                }
            }
        }
        $tab='<div class="table-responsive"><table class="table"><thead><tr><td>Дата</td>';
        foreach ($master as $m){
            $tab.="<td>".$m['name']."</td>";
        }
        $tab.='</tr></thead><tbody>';
        static $l=1;
        foreach ($tabarxiv as $d=>$val){
            $tab.='<tr><td>'.date('d-m-Y', strtotime($d)).'</td>';
            foreach ($val as $k=>$va){
                foreach ($va as $x=>$v){
                    if($l==$x){
                        //$l++;
                    }else{
                        for($i=$l;$i<$x;$i++){
                            $tab.='<td></td>';
                            $l++;
                        }
                    }
                    $tab.='<td>';
                    foreach ($v as $id=>$s){
                        $tab.='<p>'.$s['money'].' / '.$s['com'].'<i class="del btn btn-primary" data-id="'.$id.'">X</i>';
                    }
                    $tab.='</td>';
                    $l++;
                }
            }$tab.='</tr>';
        }
        $tab.='</tbody></table></div>';
        return $tab;
    }
    public function rasxodshet($post){
        $params=[
            'dates'=>$post['data_first'],
            'data'=>$post['data_last'],
            'id_master'=>$post['master'],
        ];
        $rd=$this->db->row('SELECT * FROM rasxod WHERE id_master=:id_master AND  date BETWEEN :dates AND :data', $params);
        $money=0;
        $shet='<a href="#" class="btn btn-danger btn-block" style="margin-top: 20px;" onClick="window.print()"> Распечатать </a>';
        $shet.='<div class="table-responsive"><table class="table"><thead><tr><td>Дата</td><td>Сумма</td><td>Цель</td></tr></thead><tbody>';
        foreach ($rd as $val){
         $shet.='<tr><td>'.$val['date'].'</td><td>'.$val['money'].' RUB </td><td>'.$val['com'].'</td></tr>';
         $money+=$val['money'];
     }
        $shet.='<tr><td>Итого: за период '.$post['data_first'].' по '.$post['data_last'].'</td><td>'.$money.' RUB</td><td></td></tr></tbody></table>';
        return $shet;
	}
    public function rasxoddel($post){
        $params=[
            'id'=>$post,
        ];
        $this->db->query('DELETE FROM rasxod WHERE id=:id', $params);
    }
    public function rasxodadd($post){
        $params=[
            'id'=>'',
            'dates'=>$post['data'],
            'money'=>$post['price'],
            'id_master'=>$post['master'],
            'com'=>$post['com'],
        ];
        $this->db->row('INSERT INTO rasxod VALUES (:id,:id_master,:dates,:money,:com)', $params);
        return $this->db->lastInsertId();
    }
    public function setting(){
        return $this->db->row('Select * From setting');
    }
    public function settingsave($post){
        $params=[
            'messagezakaz'=>json_encode($post['messagezakaz']),
            'messageotziv'=>json_encode($post['messageotziv']),
            'messagezaivka'=>json_encode($post['messagezaivka']),
            'id'=>1,
        ];
        $this->db->query('UPDATE setting SET messagezakaz=:messagezakaz,messageotziv=:messageotziv,messagezaivka=:messagezaivka WHERE id=:id',$params);
        return $post['messageotziv'];
    }
    public function senddate(){
        $params=['dat'=>date('Y-m-d'),'datalast'=>date('Y-m-d',mktime(0,0,0, date('m'),date('d')-7,date('Y'))),];
        $data=$this->db->row('SELECT * FROM sms WHERE date BETWEEN :datalast and :dat ',$params);
        foreach ($data as $key=>$send){
            switch ($send['ack']){
                case 0:
                    $data[$key]['ack']='<div class="alert alert-danger" role="alert">'.$send['phone'].' - нет номера в whatsapp '.$send['date'].'</div>';
                    break;
                case 1:
                    $data[$key]['ack']='<div class="alert alert-success" role="alert">'.$send['phone'].' - отправлено '.$send['date'].'</div>';
                    break;
            }
        }
        return $data;
    }
    public function sendsms($post){
        set_time_limit(0);
        foreach ($post['number'] as $phone){
            $params=['phone'=>$phone, 'data'=>date('Y-m-d')];
            $rows=$this->db->column('Select id FROM sms WHERE phone=:phone and date=:data',$params);
            if(!$rows){
                $chat= new Chatapi();
                $phone=substr_replace($phone,'7',0,1);
                if(!$chat->checkPhone($phone)) {
                    $ack=0;
                }else{
                    $bodytext=$this->db->row('Select messagezaivka From setting');
                    $bodytext=json_decode($bodytext[0]['messagezaivka']);
                    $result=$chat->sendPhoneMessage($phone,$bodytext);
                    $ack=1;
                }
                $phone=substr_replace($phone,'8',0,1);
                $params=['id'=>'','phone'=>$phone,'ack'=>$ack,'date'=>date('Y-m-d')];
                $sen=$this->db->query('INSERT INTO sms VALUES (:id,:phone,:ack,:date)',$params);
                sleep(20);
            }else{return false;}}
        return true;
    }

 }
