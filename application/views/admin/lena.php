<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Заказы</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="/admin/lena" method="post" >
                        <?php
                        echo $lena;
                        ?>
                            <p class="btn btn-primary" id="check">Выбрать все заказы</p>
                            <p class="btn btn-primary" id="checknot">Снять выделение</p>
                            <div id="resultsum">Отдать лене <i id="colzakaz"></i>  заказов на сумму <i id="sumzakaz"></i>RUB ///10%/// <i id="prozakaz"></i> RUB </div>
                        <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        colzakaz=0;
        sumzakaz=0;
        prozakaz=0;
        element=$(".custom-control-input");
        $(".custom-control-input").on('change', function () {
            if($(this).attr('data-status')=='1'){
                --colzakaz;
                sum=$(this).attr('data-price');
                $(this).attr('data-status',0);
                sumzakaz=sumzakaz-Number(sum);
                prozakaz=sumzakaz/10;
                $('#sumzakaz').html(sumzakaz);
                $('#colzakaz').html(colzakaz);
                $('#prozakaz').html(prozakaz);
            }else{
                colzakaz++;
                sum=$(this).attr('data-price');
                $(this).attr('data-status',1);
                sumzakaz+=Number(sum);
                prozakaz=sumzakaz/10;
                $('#sumzakaz').html(sumzakaz);
                $('#colzakaz').html(colzakaz);
                $('#prozakaz').html(prozakaz);
            }
        })
        $('#check').on('click', function(){
            for(i=0;i<=element.length-1;i++){
                element[i].checked = true;
            }
            for (i = 0; i <= element.length - 1; i++) {
                colzakaz++;
                sum = element[i].getAttribute('data-price');
                element[i].setAttribute('data-status', 1);
                sumzakaz += Number(sum);
                prozakaz=sumzakaz/10;
                $('#sumzakaz').html(sumzakaz);
                $('#colzakaz').html(colzakaz);
                $('#prozakaz').html(prozakaz);
            }
        });
        $('#checknot').on('click', function(){
            for(i=0;i<=element.length-1;i++){
                element[i].checked = false;
            }
            colzakaz=0;
            sumzakaz=0;
            prozakaz=0;
            $('#sumzakaz').html(sumzakaz);
            $('#colzakaz').html(colzakaz);
            $('#prozakaz').html(prozakaz);
        });
    </script>
</div>