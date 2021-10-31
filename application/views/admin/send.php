<div class="content-wrapper">
    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">Отправка сообщений</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="lds-ring noactivelds"><div></div><div></div><div></div><div></div></div>
                        <form action="<? echo $_SERVER['REQUEST_URI']; ?>" method="post">
                            <div class="form">
                                <div class="form-group">
                                    <label>Номер телефона</label>
                                    <input type="number" class="form-control" name="number[]"  value="" placeholder="8916400" minlength="8"  maxlength="14" required>
                                </div></div>
                            <p class="btn btn-info" id="addbtn" style="cursor: pointer;">Добавить номер</p>
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <div class="card-title">Отправленные cмс на номера</div>
                        <div class="row datebtn">
                            <div id="day" class="btn btn-primary">Сегодня</div>
                            <div id="yesterday" class="btn btn-outline-primary">Вчера</div>
                            <div id="3day" class="btn btn-outline-primary">3 дня</div>
                            <div id="week" class="btn btn-outline-primary">Неделя</div>
                        </div>
                        <div id="resultsmsdata" style="margin-top:20px; "></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    json=<?echo $senddate;?>;
   json = JSON.stringify(json);
    json=JSON.parse(json);
    const resultsmsdata=$('#resultsmsdata');
    const btnactiv=$('.datebtn');
    $(document).ready(()=>{
     result=json.filter(json=>json.date==moment().format('YYYY-MM-DD'));
      print(result);
        $('#day').on('click',()=>{
            result=json.filter(json=>json.date==moment().format('YYYY-MM-DD'));
            btnactiv.children(".btn-primary").removeClass('btn-primary').addClass('btn-outline-primary');
            $('#day').removeClass('btn-outline-primary').addClass('btn-primary');
            print(result);
        })
        $('#yesterday').on('click',()=>{
            result=json.filter(json=>json.date==moment().subtract(1,'d').format('YYYY-MM-DD'));
            btnactiv.children(".btn-primary").removeClass('btn-primary').addClass('btn-outline-primary');
            $('#yesterday').removeClass('btn-outline-primary').addClass('btn-primary');
            print(result);
        })
        $('#3day').on('click',()=>{
            today = moment().format('YYYY-MM-DD');
            tolastday=moment().subtract(3,'d').format('YYYY-MM-DD');
            result=json.filter(json=>json.date<=today && json.date>tolastday);
            btnactiv.children(".btn-primary").removeClass('btn-primary').addClass('btn-outline-primary');
            $('#3day').removeClass('btn-outline-primary').addClass('btn-primary');
            print(result);
        })
        $('#week').on('click',()=>{
            today = moment().format('YYYY-MM-DD');
            tolastday=moment().subtract(7,'d').format('YYYY-MM-DD');
            result=json.filter(json=>json.date<=today && json.date>tolastday);
            btnactiv.children(".btn-primary").removeClass('btn-primary').addClass('btn-outline-primary');
            $('#week').removeClass('btn-outline-primary').addClass('btn-primary');
            print(result);
        })
    })
   function print(arr){
       resultsmsdata.empty();
       arr.forEach(function(item, i, arr) {
           resultsmsdata.append(item.ack);
       });
    }
</script>
<script>
    $('#addbtn').click(function () {
        var inpputf=$('.form-group').length;
        if(inpputf<5){
            $( ".form" ).append('<div class="form-group">\n' +
                '                                <label>Номер телефона</label>\n' +
                '                                <input type="number" class="form-control" name="number[]"  value="" placeholder="89152655400" minlength="8"  maxlength="14" required>\n' +
                '                            </div>');
        }else{alert('Больше добавить нельзя')}}
    );
</script>