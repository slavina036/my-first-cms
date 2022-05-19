<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<!--       Данные о массиве $results и типе формы передаются корректно-->
        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>
              <li>
                  <?php // echo "<pre>"; print_r($results); echo "</pre>";?>
                  <label for="title">Название</label>
                <input type="text" name="title" id="title" placeholder="Название статьи" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->title )?>" />
              </li>

              <li>
                <label for="summary">Краткое содержание</label>
                <textarea name="summary" id="summary" placeholder="Краткое описание статьи" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary )?></textarea>
              </li>

              <li>
                <label for="content">Содержание</label>
                <textarea name="content" id="content" placeholder="HTML-содержимое статьи" required maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $results['article']->content )?></textarea>
              </li>

<!--              <li>
                <label for="categoryId">Предыдущая статья</label>
                <select name="previousArticleId">
                  <option value=""<?php echo !$results['article']->previousArticleId ? " selected" : "" ?> disabled>(Не выбрано)</option>
                    <?php foreach ($results['listArticlesIdTitle'] as $articleIdTitle) {?>
                      <option value="<?= $articleIdTitle->id?>"><?php echo htmlspecialchars($articleIdTitle->title)?></option>
                    <?php } ?>
                </select>
              </li>-->

              <li>
                <label for="categoryId">Следующая статья</label>
                <select name="nextArticleId">
                    <option value=""<?php echo !$results['article']->nextArticleId ? " selected" : "" ?> disabled>(Не выбрано)</option>
                    <?php foreach ($results['listArticlesIdTitle'] as $articleIdTitle) {?>
                      <option value="<?= $articleIdTitle->id?>"><?php echo htmlspecialchars($articleIdTitle->title)?></option>
                    <?php } ?>
                </select>
              </li>

              <li>
                <label for="categoryId">Категория и подкатегория</label>
                <select name="subcategoryId" required>
                  <option value=""<?php echo !$results['article']->subcategoryId ? " selected" : ""?>>(Не выбрано)</option>
                <?php
                $currentCategoryName = null;
                foreach ( $results['subcategories']['results'] as $subcategory ) {
                    if($currentCategoryName !== $subcategory->categoryName) {
                        if (!is_null($currentCategoryName)) { ?>
                            </optgroup>
                       <?php  }
                        $currentCategoryName = $subcategory->categoryName;

                       ?>
                    <optgroup label="<?= $subcategory->categoryName ?>"<?php echo !$results['article']->categoryId ? 'selected' : ''?>">

                    <?php } ?>
                      <option value="<?php echo $subcategory->id?>"<?php echo ( $subcategory->id == $results['article']->subcategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $subcategory->name )?></option>
                <?php } ?>
                    </optgroup>
                </select>
              </li>

              <li>
                <lebel for="authors">Авторы</lebel>
                <select name="authors[]" multiple required>
                    <option value=""<?php echo !$results['article']->authors ? " selected" : "" ?> disabled>(Не выбрано)</option>
                    <?php

                        $authorsIdList = array_map(
                          fn(User $author): int => $author->id,
                          $results['article']->authors ?? []
                        );

                      foreach ( $results['authors']['results'] as $user) {?>
                        <option value="<?= $user->id?>"<?= (in_array($user->id, $authorsIdList)) ? " selected" : "" ?>><?php echo htmlspecialchars( $user->login )?></option>
                      <?php } ?>
                  </select>
              </li>

              <li>
                <label for="publicationDate">Дата публикации</label>
                <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->publicationDate ? date( "Y-m-d", $results['article']->publicationDate ) : "" ?>" />
              </li>

              <li>
                  <label for="active">Активность</label>
                  <input type="checkbox" name="active" id="active" value="1" <?= $results['article']->active ? 'checked' : '' ?> />
              </li>

            </ul>

            <div class="buttons">
              <input type="submit" name="saveChanges" value="Сохранить" />
              <input type="submit" formnovalidate name="cancel" value="Отменить" />
            </div>

        </form>

    <?php if ($results['article']->id) { ?>
          <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Удалить эту статью?')">
                  Удалить статью
              </a>
          </p>
    <?php } ?>

<?php include "templates/include/footer.php" ?>

