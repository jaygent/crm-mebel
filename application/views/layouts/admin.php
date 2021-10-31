<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?></title>
        <link href="/public/styles/bootstrap.css" rel="stylesheet">
        <link href="/public/styles/font-awesome.css" rel="stylesheet">
        <script src="/public/scripts/jquery.js"></script>
        <script src="/public/scripts/popper.js"></script>
        <script src="/public/scripts/bootstrap.js"></script>
        <script src="/public/scripts/select2.min.js"></script>
        <script src="/public/scripts/moment.js"></script>
        <link href="/public/styles/select2.min.css" rel="stylesheet">
        <link href="/public/styles/admin.css" rel="stylesheet">
    </head>
    <body class="fixed-nav sticky-footer bg-dark">
        <?php if ($this->route['action'] != 'login'): ?>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
                <a class="navbar-brand" href="/admin/posts">Панель Администратора</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                        <? if(isset($idmaster)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/add<? if(isset($idmaster)){echo '/'.$idmaster;} ?>">
                            <i class="fa fa-fw fa-plus"></i>
                            <span class="nav-link-text">Добавить заказ</span>
                            </a>
                        </li>
                    <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/posts">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Журналы</span>
                            </a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link" href="/admin/search">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Поиск</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/static">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Статистика</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/send">
                                <i class="fa fa-fw fa-list"></i>
                                <span class="nav-link-text">СМС</span>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link" href="/admin/sot">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Сотрудники</span>
                            </a>
                        </li>
                           <li class="nav-item">
                            <a class="nav-link" href="/admin/zarplata">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Расчет зарплаты</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/lena">
                                <i class="fa fa-fw fa-list"></i>
                                <span class="nav-link-text">Расчет Лены</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/rasxod">
                            <span class="nav-link-text">Расход</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/year">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Архив</span>
                            </a>
                        </li>
                          <li class="nav-item">
                            <a class="nav-link" href="/admin/prof">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Профиль</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/setting">
                                <i class="fa fa-fw fa-cog"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/logout">
                            <i class="fa fa-fw fa-sign-out"></i>
                            <span class="nav-link-text">Выход</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        <?php endif; ?>
        <?php echo $content; ?>
        <?php if ($this->route['action'] != 'login'): ?>
            <footer class="sticky-footer">
                <div class="container">
                </div>
            </footer>
        <?php endif; ?>
        <style> @media(max-width: 500px){
    .select2-container{ width: 100% !important; }</style>
        <?php
        switch ($_SERVER['REQUEST_URI']) {
	case '/admin/zarplata':
		break;
    case '/admin/search':
		break;
	case '/admin/rasxod':
		break;
	default:
		echo '<script src="/public/scripts/form.js"></script>';
		break;
}
        ?>
    </body>
</html>
