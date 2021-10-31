<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Расчет запрплаты</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <style type="text/css">
                            .zakaz{
                                    border: 1px solid;padding: 5px;display: flex;flex-wrap: wrap;
                            }
                            .zakaz p{margin-left: 5px;margin-right: 5px;}
                        </style>
                        <div class="lds-ring noactivelds"><div></div><div></div><div></div><div></div></div>
                     <form action="/admin/zarplata" method="post">
                        <div class="form-group">
                                <label>Дата начала работы</label>
                                <input class="form-control" type="date" name="data_first" value="2020-05-01" required style="width:300px;">
                                <label>Дата окончания работы</label>
                                <input class="form-control" type="date" name="data_last" value="<? echo date('Y-m-d'); ?>" required style="width:300px;">
                                <label>Работник</label>
                                <select name="master" required><?php foreach($master as $m){echo "<option value='".$m['id_master']."' >".$m['name']."</option>";} ?></select>
                            </div>
                            <div class="form-group">
                             <label>Вывести данные в файл</label>
                             <input type="checkbox" name="savefile" style="transform: scale(1.5); opacity: 0.9; cursor: pointer;">
                         </div>
                        <button type="submit" class="btn btn-primary btn-block">Расчитать</button>
                        </form>
                    </div>
                </div>
                <div id='result' style="margin-top:50px;"><? echo $home; ?></div>
                <script type="text/javascript">
    $(document).ready(function() {
    $('form').submit(function(event) {
        var json;
        event.preventDefault();
        $(this).addClass('noactivelds');
        $(".lds-ring").removeClass('noactivelds');
        $('#result').empty();
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(result) {
                $('form').removeClass('noactivelds');
        $(".lds-ring").addClass('noactivelds');
                              json = jQuery.parseJSON(result);
                $('#result').html(json);
                
            },
        });
    });});
                </script>
            </div>
        </div>
    </div>
</div>