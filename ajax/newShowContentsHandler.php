<?php
    require ('../config.php');

    if (isset($_GET['articleId'])) {
        $article = Article::getById((int)$_GET['articleId']);
        $article->content .= " Контент загружен без перезагрузки страницы с помощью ajax-запроса type: 'GET'";
        echo $article->content;
    }
    if (isset ($_POST['articleId'])) {
        $article = Article::getById((int)$_POST['articleId']);
        $article->content .=  " Контент загружен без перезагрузки страницы с помощью ajax-запроса type: 'POST'";
        echo json_encode($article);

}

