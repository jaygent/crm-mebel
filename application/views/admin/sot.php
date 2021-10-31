<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Сотрудники</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php  
                        $data='';
                        foreach ($master as $value) {
                        	switch ($value['work']) {
                        		case '1':
                        			$work='<a class="btn btn-success" href="/admin/otpusk/'.$value['id_master'].'">Отправить в Отпуск</a>';
                        			break;
                        		case '0':
                        			$work='<a class="btn btn-info" href="/admin/otpusk/'.$value['id_master'].'">Вызвать на Работу</a>';
                        			break;
                        	}
                            $data.='<div style="border:1px solid green;">';
                            $data.='<p> Имя : '.$value['name'].' Профессия : '.$value['proff'].' <a class="btn btn-primary" href="/admin/sot/'.$value['id_master'].'">Редактировать</a><a class="btn btn-danger" href="/admin/sotdel/'.$value['id_master'].'">Удалить</a>'.$work.'</p></div>';
                        }
                        echo $data;
                        ?>
                        <div id="addmaster" class="btn btn-primary">Добавить сотрудника/Удалить</div>
                        <form action="/admin/sot" method="post" class="formsot" style='display:none;'>
                              <div class="form-group">
    <label>Имя/Фамилия</label>
    <input type="text" class="form-control name" name="name" placeholder="Вася Пинск">
  </div>
  <div class="form-group">
    <label>Проффесия</label>
    <select name="proff"><option value="мастер" >Мастер</option><option value="Выездной мастер">Выездной Мастер</option><option value="швея" >Швея</option><option value="Обивщик" >Обивщик</option><option value="Диспетчер" >Диспетчер</option></select>
  </div>
  <div class="form-group">
    <label>Максимальный процент с заказа, %</label>
    <input type="number"placeholder="6" name="max_pro">
  </div>
     <div class="form-group">
    <label>Логин</label>
    <input type="text" class="form-control login" name="login" placeholder="Vasia">
  </div>
  <div class="form-group">
    <label>Пароль</label>
    <input type="password" id="password-input" placeholder="Введите пароль" name="password">
    <label><input type="checkbox" class="password-checkbox"> Показать пароль</label>
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>
                        <script> $('#addmaster').click(function(){
                        $('form').toggle();
                        });  
$('body').on('click', '.password-checkbox', function(){
    if ($(this).is(':checked')){
        $('#password-input').attr('type', 'text');
    } else {
        $('#password-input').attr('type', 'password');
    }
}); 
function urlLit(w,v) {
var tr='a b v g d e ["zh","j"] z i y k l m n o p r s t u f h c ch sh ["shh","shch"] ~ y ~ e yu ya ~ ["jo","e"]'.split(' ');
var ww=''; w=w.toLowerCase();
for(i=0; i<w.length; ++i) {
cc=w.charCodeAt(i); ch=(cc>=1072?tr[cc-1072]:w[i]);
if(ch.length<3) ww+=ch; else ww+=eval(ch)[v];}
return(ww.replace(/[^a-zA-Z0-9\-]/g,'-').replace(/[-]{2,}/gim, '-').replace( /^\-+/g, '').replace( /\-+$/g, ''));
}

$(document).ready(function() {
$('.name').bind('change keyup input click', function(){
$('.login').val(urlLit($('.name').val(),0))
});
});
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>