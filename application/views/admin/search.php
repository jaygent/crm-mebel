<style type="text/css">
  @media screen and (max-width: 992px)  {
 table.delivery thead {
    display: none;
  }
  table.delivery tr {
    display: block;
    margin-bottom: 1rem;
    border-bottom: 2px solid #e8e9eb;
  }
  table.delivery td {
    display: block;
    text-align: right;
  }
  table.delivery td:before {
    content: attr(aria-label);
    float: left;
    font-weight: bold;

  }}
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Поиск по номеру заказа или данных клиента</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
<div class="form-group" style="display:flex;">
    <input type="number" id="data" value="<? echo date('Y')?>" min="2017" max="2100">
                        <input style="margin:0 5px;" type="text" class="who form-control" name="ref0" data-type="0" placeholder="Поиск по номеру заказа" value="" required>
                     <input style="margin:0 5px;" type="text" class="who form-control" name="ref1" data-type="1" placeholder="Поиск по клиентам" value="" required>
                        </div>
<div class="search_result"></div>
                    </div>
                    <script>
                    $(document).ready(function() {
$('.who').bind("change keyup input",function() {
    if(this.value.length >= 1){
        ref=$(this).val();
        type=$(this).attr('data-type');
        data=$('#data').val();
        if(type==0){$('[data-type=1]').val('');}else{$('[data-type=0]').val('');}
        $.ajax({
            type: 'post',
            url: '/admin/search',
            data: {ref,type,data},
            success: function(data){
                $(".search_result").html(data);
           }
       })}
});});
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
