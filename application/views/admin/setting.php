<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
<div class="card-header">Редактирование Отправляемого сообщения</div>
<div class="card-body">
    <div class="row">
        <div class="col-sm-12">
            <div>
                <p>Значения для автоматической подстановки данных:</p>
                <p><b>{zakazid}</b>-Номер заказа</p>
                <p><b>{syma}</b>-Стоимость заказа</p>
            </div>
                       <form action="<? echo $_SERVER['REQUEST_URI']; ?>" method="post">
                <div style="display: flex;flex-wrap: wrap;">
                <div class="form-group" style="width: 400px;">
                    <label>Сообщение оформленного заказа</label>
                <textarea class="form-control" cols="40" rows="10" name="messagezakaz"><?echo json_decode($data[0]['messagezakaz'])?></textarea>
                </div>
                <div class="form-group" style="width: 400px;">
                    <label>Сообщение отзыв </label>
                <textarea class="form-control" cols="40" rows="10" name="messageotziv"><?
                    if(!empty($data[0]['messageotziv'])){
                    echo json_decode($data[0]['messageotziv']);}?></textarea>
                </div>
                    <div class="form-group" style="width: 400px;">
                        <label>Сообщение отправка при заявке</label>
                        <textarea class="form-control" cols="40" rows="10" name="messagezaivka"><?
                            if(!empty($data[0]['messagezaivka'])){
                                echo json_decode($data[0]['messagezaivka']);}?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
 </div>
</div>
</div>
</div>
