<?php include "templates/include/header.php" ?>
<?php
//    echo "<pre>";
//    print_r($results);
//    echo "<pre>";
////    die();
?>
    <h1 style="width: 75%;"><?php echo htmlspecialchars( $results['article']->title )?></h1>
    <div style="width: 75%; font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>
    <div style="width: 75%;"><?php echo $results['article']->content?></div>
    <p class="pubDate">Опубликовано <?php  echo date('j F Y', $results['article']->publicationDate)?>

    <?php if ( $results['category'] ) { ?>
        в категории "
        <a href="./?action=archive&amp;categoryId=<?php echo $results['category']->id?>">
            <?php echo htmlspecialchars($results['category']->name) ?>
        </a>" подкатегории "
        <a href="./?action=archive&amp;subcategoryId=<?php echo $results['article']->subcategoryId?>">
            <?php echo htmlspecialchars($results['subcategory']->name) ?>
        </a>"
    <?php } ?>

    </p>
    <p>
        Авторы статьи:
        <br>
        <?php foreach ($results['article']->authors as $key => $author) {
        ?>
            <a href="./?action=archive&amp;author=<?= $author->id ?>">
                <?= $author->login ?>
            </a>
        <br>
        <?php
            }
        ?>
    </p>

    <p>
        Уникальных просмотров:
        <a><?= $results['article']->uniqueViews?></a>
    </p>

    <p>
        Всего просмотров:
        <a><?= $results['article']->allViews?></a>
    </p>
    <p id="move-article">
        <?php if (isset($results['article']->previousArticleId)) { ?>
            <a href=".?action=viewArticle&amp;articleId=<?php echo $results['article']->previousArticleId?>" id="previous"> << Предыдущая статья</a>
        <?php } ?>
        <?php if (isset($results['article']->nextArticleId)) { ?>
        <a href=".?action=viewArticle&amp;articleId=<?php echo $results['article']->nextArticleId?>" id="next">  Следующая статья >> </a>
        <?php } ?>
    </p>

    <p><a href="./">Вернуться на главную страницу</a></p>

<?php include "templates/include/footer.php" ?>
