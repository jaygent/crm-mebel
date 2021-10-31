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
                        <?php $maste='';$disp=''; $ob='';$sh='';
                        foreach($master as $m){
                            switch($m['proff']){
                                case 'мастер':
                                    $maste.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                    break;
                                case 'Диспетчер':
                                    $disp.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                    break;
                                case 'Обивщик':
                                    $ob.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                    break;
                                case 'швея':
                                    $sh.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                    break;
                            }
                        }
                        if($idmaster!==1){$ob='<option selected>0</option>';$sh.='<option selected>0</option>';}
                        ?>
                        <h1>Готовый заказ?</h1>
                        <h2>Кто выполнил?</h2>
                        <form action="/manager/gotov/<? echo $idmaster ?>/<?php echo $data[0]['id_zakaza']; ?>" method="post">
                            <div class="form-group">
                                <label>Дата выполнения заказа</label>
                                <input type="date" class="form-control" name="date_m" value="<?php echo date('Y-m-d'); ?>"  style="width: 300px;" required/>
                            </div>
                            <?php  if($idmaster!==1){ ?>
                                <div class="form-group"><label>Обивщик выполнявший заказ</label>
                                    <select name="ob" required><?php echo $ob; ?></select>
                                </div>
                                <div class="form-group">
                                    <label>Швея выполняющая заказ</label>
                                    <select name="sh" required><?php echo $sh; ?></select>
                                </div><div class="form-group"><label>Проценты обивщика,%</label><input class="form-control" type="number" value="10" name="pro_ob" required></div><div class="form-group"><label>Проценты швеи,%</label><input class="form-control" type="number" value="7" name="pro_sh" required></div>
                            <?php }else{ ?>
                                <div id="splitup" style="margin: 50px auto; font-size: 20px;width: 50%; text-align: center;">
                                    <p>Разделить заказ на разных исполнителей ?</p>
                                    <i class="btn btn-primary" onclick="yes()">Да</i>
                                    <i class="btn btn-primary" onclick="no()">Нет</i>
                                </div> <?php } ?>
                            <button type="submit" class="btn btn-primary btn-block">Заказ ГОТОВ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function no() {
            html='<div class="form-group">\n' +
                '                                <label>Обивщик выполнявший заказ</label>\n' +
                '                                <select name="ob" required><?php echo $ob; ?></select>\n' +
                '                        </div>\n' +
                '                         <div class="form-group">\n' +
                '                                <label>Швея выполняющая заказ</label>\n' +
                '                                <select name="sh" required><?php echo $sh; ?></select>\n' +
                '                        </div>\n' +
                '                         <div class="form-group">\n' +
                '                                <label>Проценты обивщика,%</label>\n' +
                '                                <input class="form-control" type="number" value="10" name="pro_ob" required>\n' +
                '                            </div>\n' +
                '                            <div class="form-group">\n' +
                '                                <label>Проценты швеи,%</label>\n' +
                '                                <input class="form-control" type="number" value="7" name="pro_sh" required>\n' +
                '                            </div><i class="btn btn-primary" onclick="otmena()">Отмена</i>'
            $('#splitup').html(html);
        }
        function yes() {
            html='<input type="hidden" name="splitup"><div class="form-group">\n' +
                '                                <label>Цена заказа первой части</label>\n' +
                '                                <input class="form-control" onblur="this.value = this.value.replace(/[^\\d]/g, \'\').replace(/\\B(?=(?:\\d{3})+(?!\\d))/g, \' \')"  style="font-size: 2rem;" onfocus="this.value = this.value.replace(/\\s/g, \'\')" type="text" name="price1" required>\n' +
                '                            </div>\n' +
                '                        <div class="form-group">\n' +
                '                                <label>Обивщик выполнявший заказ</label>\n' +
                '                                <select name="ob1" required><?php echo $ob; ?></select>\n' +
                '                        </div>\n' +
                '                         <div class="form-group">\n' +
                '                                <label>Швея выполняющая заказ</label>\n' +
                '                                <select name="sh1" required><?php echo $sh; ?></select>\n' +
                '                        </div>\n' +
                '                         <div class="form-group">\n' +
                '                                <label>Проценты обивщика,%</label>\n' +
                '                                <input class="form-control" type="number" value="10" name="pro_ob1" required>\n' +
                '                            </div>\n' +
                '                            <div class="form-group">\n' +
                '                                <label>Проценты швеи,%</label>\n' +
                '                                <input class="form-control" type="number" value="7" name="pro_sh1" required>\n' +
                '                            </div>\n' +
                '   <div class="form-group">\n' +
                '                                <label>Цена заказа второй части</label>\n' +
                '                                <input class="form-control" onblur="this.value = this.value.replace(/[^\\d]/g, \'\').replace(/\\B(?=(?:\\d{3})+(?!\\d))/g, \' \')"  style="font-size: 2rem;" onfocus="this.value = this.value.replace(/\\s/g, \'\')" type="text" name="price2" required>\n' +
                '                            </div>\n' +
                '                        <div class="form-group">\n' +
                '                                <label>Обивщик выполнявший заказ</label>\n' +
                '                                <select name="ob2" required><?php echo $ob; ?></select>\n' +
                '                        </div>\n' +
                '                         <div class="form-group">\n' +
                '                                <label>Швея выполняющая заказ</label>\n' +
                '                                <select name="sh2" required><?php echo $sh; ?></select>\n' +
                '                        </div>\n' +
                '                         <div class="form-group">\n' +
                '                                <label>Проценты обивщика,%</label>\n' +
                '                                <input class="form-control" type="number" value="10" name="pro_ob2" required>\n' +
                '                            </div>\n' +
                '                            <div class="form-group">\n' +
                '                                <label>Проценты швеи,%</label>\n' +
                '                                <input class="form-control" type="number" value="7" name="pro_sh2" required>\n' +
                '                            </div><i class="btn btn-primary" onclick="otmena()">Отмена</i>';
            $('#splitup').html(html);
        }
        function otmena(){
            html='<p>Разделить заказ на разных исполнителей ?</p><i class="btn btn-primary" onclick="yes()">Да</i><i class="btn btn-primary" onclick="no()">Нет</i>';
            $('#splitup').html(html);
        }
    </script>
</div>