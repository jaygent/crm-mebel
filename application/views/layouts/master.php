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
        <link href="/public/styles/select2.min.css" rel="stylesheet">
        <link href="/public/styles/admin.css" rel="stylesheet">
    </head>
    <body class="fixed-nav sticky-footer bg-dark">
        <?php if ($this->route['action'] != 'login'): ?>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="mainNav">
                <a class="navbar-brand" href="/master/posts">Диспетчера</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                        <li class="nav-item">
                            <a class="nav-link" href="/master/posts">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Журналы</span>
                            </a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link" href="/master/search">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Поиск</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/master/rasxod">
                            <span class="nav-link-text">Расход</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/master/year">
                            <i class="fa fa-fw fa-list"></i>
                            <span class="nav-link-text">Архив</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/master/logout">
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
    case '/master/search':
        break;
    default:
        echo '<script src="/public/scripts/form.js"></script>';
        break;
}
        ?>
    </body>
</html>