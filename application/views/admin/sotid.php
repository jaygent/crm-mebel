<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header"><? echo 'Редактирования сотрудника - '.$data[0]['name']; ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                           <form action="<? echo $_SERVER['REQUEST_URI']; ?>" method="post">
                              <div class="form-group">
    <label>Имя/Фамилия</label>
    <input type="text" class="form-control" name="name"  value="<? echo $data[0]['name'] ?>">
  </div>
  <div class="form-group">
    <label>Проффесия</label>
    <select name="proff"><? switch($data[0]['proff']){
            case 'мастер': 
                echo '<option selected value="мастер" >Мастер</option><option value="швея" >Швея</option><option value="Обивщик">Обивщик</option><option value="Диспетчер" >Диспетчер</option>';
            break;
            case 'швея':
                echo '<option value="мастер" >Мастер</option><option selected value="швея" >Швея</option><option value="Обивщик">Обивщик</option><option value="Диспетчер" >Диспетчер</option>';
            break;
            case 'Обивщик':
                echo '<option value="мастер" >Мастер</option><option  value="швея" >Швея</option><option selected value="Обивщик">Обивщик</option><option value="Диспетчер" >Диспетчер</option>';
            break;
            case 'Диспетчер':
                echo '<option value="мастер" >Мастер</option><option value="швея" >Швея</option><option value="Обивщик">Обивщик</option><option selected value="Диспетчер" >Диспетчер</option>';
            break;
    }
    ?></select>
  </div>
         <div class="form-group">
    <label>Пароль</label>
    <input type="password" id="password-input" placeholder="Введите пароль" name="password">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                    <script> $('#addmaster').click(function(){
                        $('form').toggle();
                        });  
$('body').on('click', '.password-checkbox', function(){
    if ($(this).is(':checked')){
        $('#password-input').attr('type', 'text');
    } else {
        $('#password-input').attr('type', 'password');
    }
}); </script>
                </div>
            </div>
        </div>
    </div>
</div>