<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header"><?php echo $title; ?></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row"><div class="col-sm-6">
                                <div class="lds-ring noactivelds"><div></div><div></div><div></div><div></div></div>
                                <form action="/admin/static" method="POST">
                                    <div class="form-group">
                                        <label>Дата начала</label>
                                        <input class="form-control" type="date" name="data_first" value="<? echo date('Y-m-01') ?>" required style="width:300px;">
                                        <label>Дата окончания</label>
                                        <input class="form-control" type="date" name="data_last" value="<? echo date('Y-m-d'); ?>" required style="width:300px;">
                                    </div>
                                    <div class="form-group"><button class="btn btn-info">Получить</button></div>
                                </form></div>
                            <div class="col-sm-6">
                                <?
                                foreach ($year as $key=>$stat){
                                    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal'.$key.'">'.$key.'

</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal'.$key.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">'.$key.'</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        '.$stat.'
      </div>
    </div>
  </div>
</div>';
                                }
                                ?>
                            </div></div>
                        <div id='result' style="margin-top:50px;"><?php print_r($static); ?></div>
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
    </div>
</div>
