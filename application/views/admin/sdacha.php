<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Заказы</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                       <?php
                        foreach($data as $dat){
                       echo 'Номер заказа : '.$dat['nomer_zakaza'].'<hr> Дата взятия заказа '.$dat['dates'].'<hr> Примечание '.$dat['prim'].'<hr>';
                       $pro_ob=(real)$dat['pro_ob']*100; $pro_sh=(real)$dat['pro_sh']*100;
                       echo 'Процент обившика '.$pro_ob.'% Процент швеи с заказа '.$pro_sh."%";
                        echo '</br> Мебель : '.$dat['mebel'].';  Ткань : '.$dat['tkan'].'</br></hr>';
                        switch ($dat['type_oplata']) {
                            case '1':
                                $type_oplata='<option selected value="1">Наличными</option><option value="2">Без Нал</option><option value="3">На карту</option>';
                                break;
                             case '2':
                                $type_oplata='<option value="1">Наличными</option><option selected value="2">Без Нал</option><option value="3">На карту</option>';
                                break;
                             case '3':
                                $type_oplata='<option value="1">Наличными</option><option  value="2">Без Нал</option><option selected value="3">На карту</option>';
                                break;

                        }
                       }
                       ?>
                       <?php $maste='';$disp=''; $ob='';$sh='';
                       foreach($master as $m){
                                switch($m['proff']){
                        case 'Диспетчер':
                             $disp.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                             break;
                                    }
                                }  ?>
                     <h1>Кто сдавал заказ</h1>
                     <form action="/admin/sdacha/<? echo $idmaster ?>/<?php echo $data[0]['id_zakaza']; ?>" method="post">
                         <div class="form-group">
                                <label>Дата сдачи заказа</label>
                                <input type="date" class="form-control" name="date_sd" value="<?php echo date('Y-m-d'); ?>"  style="width: 300px;" required/>
                            </div>
                         <div class="form-group">
                                <label>Мастер сдавший заказ</label>
                                <select name="master" ><?php if($idmaster!==1){foreach($master as $m){if($m['id_master']==$idmaster){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";}}}else{foreach($master as $m){if($m['proff']==='мастер'){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";}}} ?></select>
                        </div>
                         <div class="form-group">
                                <label>Диспетчер сдавший заказ</label>
                                <select name="dispetcher" ><?php echo $disp; ?></select>
                        </div>
                         <div class="form-group form-check" style="margin-left: 5%; font-size: 25px; color: red;">
                             <input type="checkbox" style="transform: scale(3.3); opacity: 0.9; cursor: pointer;" class="form-check-input" id="exampleCheck1" name="sendotziv">
                             <label class="form-check-label" for="exampleCheck1">Отправить Отзыв?</label>
                         </div>
                         <div class="form-group">
                                <label>Форма оплаты</label><select name="type_oplata" required><? echo $type_oplata?></select>
                           <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Сдать заказ</button>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
