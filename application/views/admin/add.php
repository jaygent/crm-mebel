<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header"><?php echo $title; ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="/admin/add/<?echo $idmaster; ?>" method="post">
                            <div class="form-group">
                                <label>Номер заказа</label>
                                <input class="form-control" type="text" name="nomer_zakaza" required>
                            </div>
                            <div class="form-group">
                                <label>Номер заказа Лены</label>
                                <input class="form-control" type="text" name="nomer_lena">
                            </div>
                            <div class="form-group">
                                <label>Дата взятия заказа</label>
                                <input class="form-control" type="date" name="dates" value="<? echo date('Y-m-d'); ?>" style="width: 300px;" required>
                            </div>
                            <div class="form-group">
                                <label>Адрес и Имя клиента </label>
                                <textarea class="form-control" cols="40" rows="10" name="klient" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ввидете номер телефон №1 Клиента <i>мобильный</i> </label>
                                <input class="form-control" type="number" maxlength="11" minlength="10" name="phone1"  placeholder="89151796598" required>
                            </div>
                            <div class="form-group">
                                <label>Ввидите номер телефона №2 <i>при его наличии</i></label>
                                <input class="form-control" type="number" maxlength="12" minlength="11" name="phone2" placeholder="89151796598">
                            </div>
                           <div class="form-group">
                                <label>Мебель</label>
                                <textarea class="form-control" cols="40" rows="5" name="mebel" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Ткань</label>
                                <textarea class="form-control" cols="40" rows="5" name="tkan" required></textarea>
                            </div>
                                <div class="form-group">
                                <label>Цена</label>
                                <input class="form-control" onblur="this.value = this.value.replace(/[^\d]/g, '').replace(/\B(?=(?:\d{3})+(?!\d))/g, ' ')"  style="font-size: 2rem;" onfocus="this.value = this.value.replace(/\s/g, '')" type="text" name="price" required>
                            </div>
                            <div class="form-group">
                                <label>Примечание</label>
                                <textarea class="form-control" cols="40" rows="5" name="prim"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Форма оплаты</label><select name="type_oplata" required><option value="1">Наличными</option><option value="2">Без Нал</option><option value="3">На карту</option></select>
                           <div class="form-group">
                                <label>Мастер взявший заказ</label>
                               <select name="master" ><?php if($idmaster!==1){foreach($master as $m){if($m['id_master']==$idmaster){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";}}}else{foreach($master as $m){if($m['proff']==='мастер'){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";}}} ?>
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Диспетчер взявший заказ</label>
                                <select name="dispetcher" ><?php foreach($master as $m){if($m['proff']==='Диспетчер'){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";}} ?></select>
                            </div>
                            </div>
                            <input type="submit" class="btn btn-primary btn-block" value="Добавить">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
