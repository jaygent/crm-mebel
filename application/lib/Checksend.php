<?php


namespace application\lib;


class Checksend
{
 public function swichsend($send){
     switch ($send){
         case '0':
             return 'Не отправлено';
             break;
         case 'bad contact':
             return 'Номер нет вацап';
             break;
         default :
             return 'Отправлено';
             break;
     }
 }
}
