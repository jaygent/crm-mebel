<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Расходник Добавить <a class="btn btn-primary" href="/admin/rasxod">Расходник</a></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="/admin/rasxodadd" method="post">
                            <div class="form-group">
                                <label>Дата расхода</label>
                                <input class="form-control" type="date" name="data" value="<? echo(date('Y-m-d')); ?>" required style="width:300px;">
                                <label>Cумма расхода</label>
                                <input class="form-control" type="number" name="price" value="" required style="width:300px;">
                                <label>Цель расхода средств</label>
                                <input class="form-control" type="text" name="com" value="" style="width:300px;">
                                <label>Работник</label>
                                <select name="master" required><?php foreach($master as $m){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";} ?></select>
                             </div>
                            <button type="submit" class="btn btn-primary btn-block">Добавить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>