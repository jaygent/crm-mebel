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
                            echo '</br> Общая Цена заказа : '.$dat['price'].' RUB </br></hr>';
                        }
                        ?>
                        <h1>Взять заказ в работу?</h1>
                        <form action="/manager/inwork/<? echo $idmaster ?>/<?php echo $data[0]['id_zakaza']; ?>" method="post">
                <input type="hidden" name="hiw">
                            <button type="submit" class="btn btn-primary btn-block">В работу</button>
                            <a onclick="history.back(); return false;" class="btn btn-primary btn-block">Вернуться назад</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>