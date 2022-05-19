
<?php include "templates/include/header.php" ?>
    <ul id="headlines">
    <?php foreach ($results['articles'] as $article) { ?>
        <li class='<?php echo $article->id?>'>
            <h2>
                <span class="pubDate">
                    <?php echo date('j F', $article->publicationDate)?>
                </span>

                <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                    <?php echo htmlspecialchars( $article->title )?>
                </a>

                <?php if (isset($article->categoryId)) { ?>
                    <span class="category">
                        Категория:
                        <a href=".?action=archive&amp;categoryId=<?php echo $article->categoryId?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name )?>
                        </a>
                    </span>
                <?php }
                else { ?>
                    <span class="category">
                        <?php echo "Без категории"?>
                    </span>
               <?php } ?>

                <?php if (isset($article->subcategoryId)) { ?>
                    <span class="category">
                        Подкатегория:
                        <a href=".?action=archive&amp;subcategoryId=<?php echo $article->subcategoryId?>">

                            <?php echo htmlspecialchars($results['subcategories'][$article->subcategoryId]->name )?>

                        </a>
                    </span>
                <?php }
                else { ?>
                    <span class="category">
                        <?php echo "Без категории"?>
                    </span>
                <?php } ?>

                <span class="category">
                    Авторы:
                    <br>
                    <?php
                        foreach ($article->authors as $key => $author) { ?>
                    <a href=".?action=archive&amp;userId=<?php echo $author->id?>">
                        <?= $author->login?>
                    </a><br>
                    <?php } ?>
                </span>

                <span class="category">
                    Уникальных просмотров:
                    <a><?= $article->uniqueViews?></a>
                </span>

                <span class="category">
                    Всего просмотров:
                    <a><?= $article->allViews?></a>
                </span>

            </h2>

            <p class="summary" data-contentId="<?php echo $article->id?>"><?php echo mb_substr(htmlspecialchars($article->content), 0, 50, 'utf8').'...'?></p>
            <img id="loader-identity" src="JS/ajax-loader.gif" alt="gif">

            <ul class="ajax-load">
                <li><a class="ajaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">Показать продолжение (POST)</a></li>
                <li><a class="ajaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">Показать продолжение (GET)</a></li>
            </ul>

            <ul class="new-ajax-load">
                <li><a class="newAjaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">NEW Показать продолжение (POST)</a></li>
                <li><a class="newAjaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">NEW Показать продолжение (GET)</a></li>
            </ul>


            <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="showContent" data-contentId="<?php echo $article->id?>">Показать полностью</a>
        </li>

    <?php } ?>
    </ul>
    <p><a href="./?action=archive">Архив статей</a></p>
<?php include "templates/include/footer.php" ?>