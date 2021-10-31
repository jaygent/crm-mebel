<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Расходник <a class="btn btn-primary" href="/master/rasxodadd">Добавить рассходник</a></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="kalendar">
                            <label>Выбирите дату просмотра расходов</label>
                        <input id="inputkalendar" type="date" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <p class="btn btn-primary btn-block racet" onclick="$('form').toggle()" >Расчет расходов за промежуток времени</p>
                        </div>
                        <form action="/master/rasxod" method="post" style="display: none;">
                            <input type="hidden" name="shet" value="1">
                            <div class="form-group">
                                <label>Дата начала работы</label>
                                <input class="form-control" type="date" name="data_first" value="2020-05-01" required style="width:300px;">
                                <label>Дата окончания работы</label>
                                <input class="form-control" type="date" name="data_last" value="<? echo date('Y-m-d'); ?>" required style="width:300px;">
                                <label>Работник</label>
                                <select name="master" required><?php foreach($master as $m){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";} ?></select>
                            </div>
                           <!-- <div class="form-group">
                                <label>Вывести данные в файл</label>
                                <input type="checkbox" name="savefile" style="transform: scale(1.5); opacity: 0.9; cursor: pointer;">
                            </div> -->
                            <button type="submit" class="btn btn-primary btn-block">Расчитать</button>
                        </form>
                        <div class="result"></div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $(document).on('click', 'i', function(e){
                        var is = confirm("Вы хотите удалить расходник?");
                        if(is){
                        del=$(this).attr('data-id');
                        $.ajax({
                            type: 'post',
                            url: '/master/rasxod',
                            data: {del},
                            success: function(result){
                                json = jQuery.parseJSON(result);
                                if (json.url) {
                                    window.location.href = '/' + json.url;
                                }
                            }
                        })}
                    })
                    if($('#inputkalendar').val()!==''){
                        date=$('#inputkalendar').val();
                        $.ajax({
                            type: 'post',
                            url: '/master/rasxod',
                            data: {date},
                            success: function(data){
                                json = jQuery.parseJSON(data);
                                $(".result").html(json);
                            }
                        })
                    }
                    $('#inputkalendar').bind("change input",function() {
                        date=$(this).val();
                        $.ajax({
                            type: 'post',
                            url: '/master/rasxod',
                            data: {date},
                            success: function(data){
                                json = jQuery.parseJSON(data);
                                $(".result").html(json);
                            }
                        })
                    });
                    $('form').submit(function(event) {
                        var json;
                        event.preventDefault();
                        $.ajax({
                            type: $(this).attr('method'),
                            url: $(this).attr('action'),
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(result) {
                                json = jQuery.parseJSON(result);
                                $('.result').html(json);

                            },
                        });
                    });});
            </script>
        </div>
    </div>
</div>