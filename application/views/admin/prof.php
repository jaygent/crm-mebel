<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
          <div class="card-header">Редактирование сотрудника админ</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                           <form action="<? echo $_SERVER['REQUEST_URI']; ?>" method="post">
                              <div class="form-group">
    <label>Новый пароль</label>
    <input type="password" class="form-control" name="password"  value="" minlength="8" required>
  </div>
  <div class="form-group">
    <label>Повторите пароль</label>
    <input type="password" class="form-control" name="password1"  value="" minlength="8" required>
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>