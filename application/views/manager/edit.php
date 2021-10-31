<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="/manager/edit/<? echo $idmaster; ?>/<?php echo $data[0]['id_zakaza']; ?>" method="post" >
                            <div class="form-group">
                                <label>Номер заказа</label>
                                <input class="form-control" type="number" name="nomer_zakaza" value="<?echo htmlspecialchars($data[0]['nomer_zakaza'], ENT_QUOTES); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Номер заказа Лена</label>
                                <input class="form-control" type="number" name="nomer_lena" value="<?echo htmlspecialchars($data[0]['nomer_lena'], ENT_QUOTES); ?>">
                            </div>
                            <div class="form-group">
                                <label>Оплачен Лене заказ?</label>
                                <select class="form-control" name="lena_status">
                                    <? switch ($data[0]['lena_status']){
                                        case 0:
                                            $lena_zakaz='<option selected value="0">Нет</option><option value="1">Да</option>';
                                            break;
                                        case 1:
                                            $lena_zakaz='<option value="0">Нет</option><option selected value="1">Да</option>';
                                            break;
                                    }
                                    echo $lena_zakaz;
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="statuss" value="<? echo $data[0]['statuss']; ?>"/>
                            <div class="form-group">
                                <label>Дата</label>
                                <input class="form-control" type="date" value="<?php echo htmlspecialchars($data[0]['dates'], ENT_QUOTES); ?>" name="dates">
                            </div>
                            <div class="form-group">
                                <label>Адрес и Имя клиента</label>
                                <textarea class="form-control" rows="10" name="klient"><?php echo htmlspecialchars($data[0]['klient'], ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ввидете номер телефон №1 Клиента <i>мобильный</i> </label>
                                <input class="form-control" type="number" maxlength="11" minlength="10" value="<?php echo htmlspecialchars($data[0]['phone1'], ENT_QUOTES); ?>" name="phone1"  placeholder="89151796598" required>
                            </div>
                            <div class="form-group">
                                <label>Ввидите номер телефона №2 <i>при его наличии</i></label>
                                <input class="form-control" type="number" maxlength="12" minlength="11" value="<?php echo htmlspecialchars($data[0]['phone2'], ENT_QUOTES); ?>" name="phone2" >
                            </div>
                            <div class="form-group">
                                <label>Мебель</label>
                                <textarea class="form-control" rows="10" name="mebel"><?php echo htmlspecialchars($data[0]['mebel'], ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ткань</label>
                                <textarea class="form-control" rows="10" name="tkan"><?php echo htmlspecialchars($data[0]['tkan'], ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Форма оплаты</label>
                                <select class="form-control" name="type_oplata"><?php
                                    switch ($data[0]['type_oplata']) {
                                        case '1':
                                            echo "<option selected value='1'>Оплата наличными</option><option value='2'>Без/нал оплата</option><option value='3'>Оплата на карту</option>";
                                            break;
                                        case '2':
                                            echo "<option value='1'>Оплата наличными</option><option selected value='2'>Без/нал оплата</option><option value='3'>Оплата на карту</option>";
                                            break;
                                        case '3':
                                            echo "<option value='1'>Оплата наличными</option><option value='2'>Без/нал оплата</option><option selected value='3'>Оплата на карту</option>";
                                            break;
                                    }

                                    ?></select>
                            </div>
                            <div class="form-group">
                                <label>Дата сдачи</label>
                                <input class="form-control" type="date" value="<?php echo htmlspecialchars($data[0]['date_sd'], ENT_QUOTES); ?>" name="date_sd">
                            </div>
                            <div class="form-group">
                                <label>Примечание</label>
                                <textarea class="form-control" rows="10" name="prim"><?php echo htmlspecialchars($data[0]['prim'], ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Цена</label>
                                <input class="form-control" onblur="this.value = this.value.replace(/[^\\d]/g, \'\').replace(/\\B(?=(?:\\d{3})+(?!\\d))/g, \' \')"  style="font-size: 2rem;" onfocus="this.value = this.value.replace(/\\s/g, \'\')" type="text" name="price" value="<? echo $data[0]['price'];?>" required>
                            </div>
                            <?php
                            $selectmaster='';$selectsh='';$selectob='';$selectdisp='';$chetmaster=0; $chetdisp=0;
                            $selectobd='';$selectshd='';
                            if(!empty($data[0]['master'] )){ foreach ($data[0]['master'] as $k => $val) {
                                switch ($val['proff']) {
                                    case 'мастер':
                                        if(empty($chetmaster)){$labelmaster='Мастер взявший заказ';}else{$labelmaster='Мастер сдавший заказ';}
                                        $chetmaster++;
                                        $selectmaster.='<div class="form-group"><label>'.$labelmaster.'</label><select class="form-control" name="mastzakaz['.$val["id"].']">';
                                        foreach ($master as $m) {
                                            if($m['proff']==='мастер'){
                                                if($val['id_master']===$m['id_master']){
                                                    $selectmaster.="<option selected value='".$m['id_master']."'>".$m['name']."</option>";
                                                }else{
                                                    $selectmaster.="<option value='".$m['id_master']."'>".$m['name']."</option>";
                                                }
                                            }
                                        }
                                        $selectmaster.='</select></div>';
                                        break;
                                    case 'швея':
                                        if(empty($val['id'])){$val['id']=0;}
                                            $selectsh.='<div class="form-group"><label>Швея выполниющая заказ</label><select class="form-control" name="shzakaz['.$val["id"].']">';
                                            if($idmaster!==1){$selectsh.='<option></option>';}
                                            foreach ($master as $m) {
                                                if($m['proff']==='швея'){
                                                    $date_m=$val['date_m'];
                                                    if($val['id_master']===$m['id_master']){
                                                        $selectsh.='<option selected value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                        $selectshd.='<option selected value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                    }else{
                                                        $selectsh.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                        $selectshd.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                    }
                                                }
                                            }
                                            $selectsh.='</select></div>';
                                        break;
                                    case 'Обивщик':
                                        if(empty($val['id'])){$val['id']=0;}
                                            $selectob.='<div class="form-group"><label>Обивщик выполниющая заказ</label><select class="form-control" name="obzakaz['.$val["id"].']">';
                                            if($idmaster!==1){$selectob.='<option></option>';}
                                            foreach ($master as $m) {
                                                if($m['proff']==='Обивщик'){
                                                    if($val['id_master']===$m['id_master']){
                                                        $selectob.='<option selected value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                        $selectobd.='<option selected value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                    }else{
                                                        $selectob.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                        $selectobd.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                                                    }
                                                }
                                            }
                                            $selectob.='</select></div>';
                                        break;
                                    case 'Диспетчер':
                                        if(empty($chetdisp)){$labeldips='Диспетчер сдавший заказ';}else{$labeldips='Диспетчер взявший заказ';}
                                        $chetdisp=$chetdisp+1;
                                        $selectdisp.='<div class="form-group"><label>'.$labeldips.'</label><select class="form-control" name="dispzakaz['.$val["id"].']">';
                                        foreach ($master as $m) {
                                            if($m['proff']==='Диспетчер'){
                                                if($val['id_master']===$m['id_master']){
                                                    $selectdisp.="<option selected value='".$m['id_master']."'>".$m['name']."</option>";
                                                }else{
                                                    $selectdisp.="<option value='".$m['id_master']."'>".$m['name']."</option>";
                                                }
                                            }
                                        }
                                        $selectdisp.='</select></div>';
                                        break;
                                }
                            }}

                            echo '<hr>'.$selectmaster;
                            $splitupob1id=0;$splitupob2id=0;$splitupsh1id=0;$splitupsh2id=0;
                            $selectob1=$selectobd;$selectob2=$selectobd;$selectsh1=$selectshd;$selectsh2=$selectshd;
                            if(!empty($data[0]['mastersplitup'])) {
                                $selectob1 = '';
                                $selectob2 = '';
                                $selectsh1 = '';
                                $selectsh2 = '';
                                foreach ($data[0]['mastersplitup'] as $splitup) {
                                    if ($splitup['part'] == 1) {
                                        $price1 = $splitup['prices'];
                                    } else {
                                        $price2 = $splitup['prices'];
                                    }
                                    switch ($splitup['proff']) {
                                        case 'Обивщик':
                                            if ($splitup['part'] == 1) {
                                                foreach ($master as $m) {
                                                    if ($m['proff'] === 'Обивщик') {
                                                        $splitupob1id = $splitup['id_split'];
                                                        $splitproob1=$splitup['pro'];
                                                        if ($splitup['id_master'] === $m['id_master']) {
                                                            $selectob1 .= '<option selected value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        } else {
                                                            $selectob1 .= '<option value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                foreach ($master as $m) {
                                                    if ($m['proff'] === 'Обивщик') {
                                                        $splitupob2id = $splitup['id_split'];
                                                        $splitproob2=$splitup['pro'];
                                                        if ($splitup['id_master'] === $m['id_master']) {
                                                            $selectob2 .= '<option selected value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        } else {
                                                            $selectob2 .= '<option value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        }
                                                    }
                                                }
                                            }
                                            break;
                                        case 'швея':
                                            if ($splitup['part'] == 1) {
                                                foreach ($master as $m) {
                                                    if ($m['proff'] === 'швея') {
                                                        $splitupsh1id = $splitup['id_split'];
                                                        $splitprosh1=$splitup['pro'];
                                                        $date_m = $val['date_m'];
                                                        if ($splitup['id_master'] === $m['id_master']) {
                                                            $selectsh1 .= '<option selected value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        } else {
                                                            $selectsh1 .= '<option value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        }
                                                    }
                                                }
                                            } else {
                                                foreach ($master as $m) {
                                                    if ($m['proff'] === 'швея') {
                                                        $splitupsh2id = $splitup['id_split'];
                                                        $splitprosh2=$splitup['pro'];
                                                        if ($splitup['id_master'] === $m['id_master']) {
                                                            $selectsh2 .= '<option selected value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        } else {
                                                            $selectsh2 .= '<option value="' . $m['id_master'] . '">' . $m['name'] . '</option>';
                                                        }
                                                    }
                                                }
                                            }
                                            break;

                                    }
                                }
                            }
                            if($data[0]['statuss']>0){
                                ?>

                                <div class="form-group">
                                    <label>Дата выполнения заказа</label>
                                    <input type="date" class="form-control" name="date_m" value="<?php echo $date_m; ?>"  style="width: 300px;" required/>
                                </div>
                            <?php } if(empty($data[0]['mastersplitup'])) {?>
                            <div id="splitup" style="margin: 50px auto; font-size: 20px;width: 50%; text-align: center;">
                                <div class="form-group">
                                    <label>Проценты обивщика,%</label>
                                    <input class="form-control" type="number" value="<?php echo htmlspecialchars($data[0]['pro_ob']*100, ENT_QUOTES); ?>" name="pro_ob">
                                </div>
                                <div class="form-group">
                                    <label>Проценты швеи,%</label>
                                    <input class="form-control" type="number" value="<?php echo htmlspecialchars($data[0]['pro_sh']*100, ENT_QUOTES); ?>" name="pro_sh">
                                </div>
                                <?php
                                echo $selectsh;
                                echo $selectob;
                                if($idmaster==1){echo '<i class="btn btn-primary" onclick="otmena()">СБрос</i></div>'; }
                                echo '</div>';
                            }
                                if(!empty($data[0]['mastersplitup'])){?>
                                    <div id="splitup" style="margin: 50px auto; font-size: 20px;width: 50%; text-align: center;">
                                        <input type="hidden" name="splitup"><div class="form-group">
                                            <label>Цена заказа первой части</label>
                                            <input class="form-control" onblur="this.value = this.value.replace(/[^\\d]/g, \'\').replace(/\\B(?=(?:\\d{3})+(?!\\d))/g, \' \')"  style="font-size: 2rem;" onfocus="this.value = this.value.replace(/\\s/g, \'\')" type="text" name="price1" value="<? echo $price1;?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Обивщик выполнявший заказ</label>
                                            <select name="ob1[<? echo $splitupob1id; ?>]" required><?php echo $selectob1; ?></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Швея выполняющая заказ</label>
                                            <select name="sh1[<? echo $splitupsh1id; ?>]" required><?php echo $selectsh1; ?></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Проценты обивщика,%</label>
                                            <input class="form-control" type="number" value="<?php echo htmlspecialchars($splitproob1*100, ENT_QUOTES); ?>" name="pro_ob1" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Проценты швеи,%</label>
                                            <input class="form-control" type="number" value="<?php echo htmlspecialchars($splitprosh1*100, ENT_QUOTES); ?>" name="pro_sh1" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Цена заказа второй части</label>
                                            <input class="form-control" onblur="this.value = this.value.replace(/[^\\d]/g, \'\').replace(/\\B(?=(?:\\d{3})+(?!\\d))/g, \' \')"  style="font-size: 2rem;" onfocus="this.value = this.value.replace(/\\s/g, \'\')" type="text" name="price2" value="<? echo $price2;?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Обивщик выполнявший заказ</label>
                                            <select name="ob2[<? echo $splitupob2id; ?>]" required><?php echo $selectob2; ?></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Швея выполняющая заказ</label>
                                            <select name="sh2[<? echo $splitupsh2id; ?>]" required><?php echo $selectsh2; ?></select>
                                        </div>
                                        <div class="form-group">
                                            <label>Проценты обивщика,%</label>
                                            <input class="form-control" type="number" value="<?php echo htmlspecialchars($splitproob2*100, ENT_QUOTES); ?>" name="pro_ob2" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Проценты швеи,%</label>
                                            <input class="form-control" type="number" value="<?php echo htmlspecialchars($splitprosh2*100, ENT_QUOTES); ?>" name="pro_sh2" required>
                                        </div><? if($idmaster==1){  ?><i class="btn btn-primary" onclick="otmena()">СБрос</i><? } ?></div>
                                <?php }
                                echo '<hr>'.$selectdisp;
                                ?>
                                <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
                                <a href="/manager/posts/<? echo $idmaster; ?>" class="btn btn-primary btn-block">Назад</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $ob='';$sh='';
        foreach($master as $m){
            switch($m['proff']){
                case 'Обивщик':
                    $ob.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                    break;
                case 'швея':
                    $sh.='<option value="'.$m['id_master'].'">'.$m['name'].'</option>';
                    break;
            }
        }
        if(empty($selectob)){
            $selectob.='<div class="form-group"><label>Обивщик выполниющая заказ</label><select class="form-control" name="obzakaz[0]">';
            $selectob.=$ob;
            $selectob.='</select></div>';
        }
        if(empty($selectsh)){

            $selectsh.='<div class="form-group"><label>Швея выполниющая заказ</label><select class="form-control" name="shzakaz[0]">';;
            $selectsh.=$sh;
            $selectsh.='</select></div>';
        }
        ?>
        <script>
            function otmena(){
                html='<p>Разделить заказ на разных исполнителей ?</p><i class="btn btn-primary" onclick="yes()">Да</i><i class="btn btn-primary" onclick="no()">Нет</i>';
                $('#splitup').html(html);
            }
            function no() {
                html='<div class="form-group">\n' +
                    '                                <?php echo $selectob; ?>\n' +
                    '                        </div>\n' +
                    '                         <div class="form-group">\n' +
                    '                                <?php echo $selectsh; ?>\n' +
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
                    '                                <select name="ob1[<? echo $splitupob1id; ?>]" required><?php echo $selectob1; ?></select>\n' +
                    '                        </div>\n' +
                    '                         <div class="form-group">\n' +
                    '                                <label>Швея выполняющая заказ</label>\n' +
                    '                                <select name="sh1[<? echo $splitupsh1id; ?>]" required><?php echo $selectsh1; ?></select>\n' +
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
                    '                                <select name="ob2[<? echo $splitupob2id; ?>]" required><?php echo $selectob2; ?></select>\n' +
                    '                        </div>\n' +
                    '                         <div class="form-group">\n' +
                    '                                <label>Швея выполняющая заказ</label>\n' +
                    '                                <select name="sh2[<? echo $splitupsh2id; ?>]" required><?php echo $selectsh2; ?></select>\n' +
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
        </script>
    </div>
</div>

