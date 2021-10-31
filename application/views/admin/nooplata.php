<style type="text/css">
    @media screen and (max-width: 992px)  {
        table.delivery thead {
            display: none;
        }
        table.delivery tr {
            display: block;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e8e9eb;
        }
        table.delivery td {
            display: block;
            text-align: right;
        }
        table.delivery td:before {
            content: attr(aria-label);
            float: left;
            font-weight: bold;

        }}
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Готовые-неоплаченные заказы Журнал <?if(!empty($idmaster)){ foreach ($master as $val) {
                    if($val['id_master']==$idmaster){
                        echo $val['name'].'<a class="btn bg-danger" href="/admin/works/'.$val['id_master'].'" style="border: 2px solid #fff; color: #fff;">В работе</a><a class="btn bg-warning" href="/admin/nooplata/'.$val['id_master'].'" style="border: 2px solid #fff; color: #fff;">Готов-Не оплачен</a>';
                    }
                }if($idmaster==1){  echo 'Общий <a class="btn bg-danger" href="/admin/works/1" style="border: 2px solid #fff; color: #fff;">В работе</a><a class="btn bg-warning" href="/admin/nooplata/1" style="border: 2px solid #fff; color: #fff;">Готов-Не оплачен</a>';
                          }} ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php if(!empty($maste)){
                            foreach ($maste as $val) {
                                if($val['proff']==='мастер'){
                                    echo '<a href="/admin/posts/'.$val['id_master'].'" class="btn btn-primary" style="margin:0 20px;">Журнал мастера '.$val['name'].'</a>';
                                }
                            }}?>
                        <?php if(!empty($idmaster)){if (empty($list)): ?>
                            <p>Список заказов пуст</p>
                        <?php else: ?>
                            <table class="table delivery">
                                <thead><tr>
                                    <th>№</th>
                                    <th>Дата</th>
                                    <th>Данные Клиента</th>
                                    <th>Дата сдачи заказа</th>
                                    <th>Мебель</th>
                                    <th>Мастера</th>
                                    <th>Обивщик</th>
                                    <th>Швея</th>
                                    <th>Диспетчер</th>
                                    <th>Цена</th>
                                    <th>Примечание</th>
                                    <th>Ред</th>
                                </tr></thead>
                                <?php     foreach ($list as $value) {
                                ?>
                                <tr <?php
                                switch ($value['statuss']) {
                                    case 0:
                                        //echo 'class="bg-danger"';
                                        $bt='<div class="btn bg-danger" style="border: 2px solid #fff; color: #fff;">В работе</div>';
                                        break;
                                    case 1:
                                        echo 'class="bg-warning"';
                                        $bt='<div class="btn bg-warning" style="border: 2px solid #fff; color: #fff;">Готов-Не оплачен</div>';
                                        break;
                                    case 2:
                                        echo 'class="bg-success"';
                                        $bt='<div class="btn bg-success" style="border: 2px solid #fff; color: #fff;">Оплачен и сдан</div>';
                                        break;}
                                ?> >
                                    <?php
                                    echo '<td aria-label="№">'.$value['nomer_zakaza'].'</td>';
                                    echo '<td aria-label="Дата">'.date("d-m-Y", strtotime($value['dates'])).'</td>';
                                    echo '<td aria-label="Данные Клиента" style="word-break: break-word;">'.$value['klient'].'</td>';
                                    echo '<td aria-label="Дата сдачи заказа">'.date("d-m-Y", strtotime($value['date_sd'])).'</td>';
                                    $pro_ob=(real)$value['pro_ob']*100;
                                    $pro_sh=(real)$value['pro_sh']*100;
                                    $mst='';
                                    if(isset($value['mebel'])){
                                        $mst.='Мебель : '.$value['mebel'];
                                        $mst.='</br> Ткань : '.$value['tkan'];
                                        echo '<td aria-label="Мебель">'.$mst.'</td>';}else{echo '<td>Графа пуста</td>';}
                                    $mast='<td aria-label="Мастер">';$obivsh='<td aria-label="Обивщик">';$shvei='<td aria-label="Швея">';$disp='<td aria-label="Диспетчер">';
                                    if(!empty($value['master'])){
                                        $chetmaster=0; $chetdisp=0;
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
                                        $mast.='</td>';$obivsh.='</td>';$shvei.='</td>';$disp.='</td>';
                                        echo $mast,$obivsh,$shvei,$disp;}else{echo '<td aria-label="Мастер">Графа пуста</td><td aria-label="Обивщик">Графа пуста</td><td aria-label="Швея">Графа пуста</td><td aria-label="Диспетчер">Графа пуста</td>';}
                                    switch ($value['type_oplata']) {
                                        case '1':
                                            $type_oplata="Оплата наличными";
                                            break;
                                        case '2':
                                            $type_oplata="Оплата без/нал";
                                            break;
                                        case '3':
                                            $type_oplata="Оплата на карту";
                                            break;
                                    }
                                    echo '<td aria-label="Цена">'.htmlspecialchars($value['price'], ENT_QUOTES).'</br>'.$type_oplata.'</td>';
                                    echo '<td aria-label="Примечание">'.htmlspecialchars($value['prim'], ENT_QUOTES).'</td>';
                                    $sdacha='';
                                    switch ($value['statuss']) {
                                        case '0':
                                            $sdacha='<a href="/admin/gotov/'.$idmaster.'/'.$value["id_zakaza"].'" class="btn btn-secondary">Заказ Готов</a>';
                                            break;
                                        case '1':
                                            $sdacha='<a href="/admin/sdacha/'.$idmaster.'/'.$value["id_zakaza"].'" class="btn btn-primary">Сдать заказ</a>';
                                            break;
                                    }
                                    echo '<td aria-label="Ред.">'.$bt.'</br>'.$sdacha.'<a href="/admin/edit/'.$idmaster.'/'.$value["id_zakaza"].'" class="btn btn-primary">Ред</a></td></tr>';
                                    }?>
                            </table>
                            <?php echo $pagination; ?>

                        <?php endif;} ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>