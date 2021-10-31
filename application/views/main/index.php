<!--<div class="container">
    <div class="row"><a href="/admin/login">Вход в панель управления</a></div>
    <? //header('Location: /admin/login');?>
</div>-->
<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Вход в панель управления</div>
        <div class="card-body">
            <form action="/" method="post">
                <div class="form-group">
                    <label>Логин</label>
                    <input class="form-control" type="text" name="login">
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input class="form-control" type="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Вход</button>
            </form>
        </div>
    </div>
</div>
<script>
    ﻿$(document).ready(function() {
	$('form').submit(function(event) {
		var json;
		event.preventDefault();
		/* $('form').find ('input, textearea, select').each(function() {
  if($(this).val()===''){$(this).focus();$(this).css('border','2px solid red');exit}else{$(this).css('border','2px solid green')}
});*/
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
			success: function(result) {
				json = jQuery.parseJSON(result);
				if (json.url) {
					window.location.href = '/' + json.url;
				} else if(json.status) {
					alert(json.status + ' - ' + json.message);
				}else{
				    $('form').html(json);
				}
			},
		});
	});
});
</script>