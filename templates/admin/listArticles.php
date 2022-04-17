<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
	  
    <h1>Все статьи</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Дата публикации</th>
              <th>Статья</th>
              <th>Категория</th>
              <th>Подкатегория</th>
              <th>Активность</th>
            </tr>

<!--<?php echo "<pre>"; print_r ($results['articles'][2]->publicationDate); echo "</pre>"; ?> Обращаемся к дате массива $results. Дата = 0 -->
            
    <?php foreach ( $results['articles'] as $article ) { ?>

            <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
              <td><?php echo date('j M Y', $article->publicationDate)?></td>
              <td>
                <?php echo $article->title?>
              </td>
              <td>
                  
             <!--   <?php echo $results['categories'][$article->categoryId]->name?> Эта строка была скопирована с сайта-->
             <!-- <?php echo "<pre>"; print_r ($article); echo "</pre>"; ?> Здесь объект $article содержит в себе только ID категории. А надо по ID достать название категории-->
            <!--<?php echo "<pre>"; print_r ($results); echo "</pre>"; ?> Здесь есть доступ к полному объекту $results -->
             
                <?php 
                if(isset ($article->categoryId)) {
                    echo $results['categories'][$article->categoryId]->name;                        
                }
                else {
                echo "Без категории";
                }?>
              </td>
              <td>
                  
             <!--   <?php echo $results['subcategories'][$article->subcategoryId]->name?> Эта строка была скопирована с сайта-->
             <!-- <?php echo "<pre>"; print_r ($article); echo "</pre>"; ?> Здесь объект $article содержит в себе только ID подкатегории. А надо по ID достать название подкатегории-->
            <!--<?php echo "<pre>"; print_r ($results); echo "</pre>"; ?> Здесь есть доступ к полному объекту $results -->
             
                 <?php 
                if(isset ($article->subcategoryId)) {
                    echo $results['subcategories'][$article->subcategoryId]->name;                        
                }
                else {
                echo "Без подкатегории";
                }?> 
              </td>
              
              <td>
                  <input type="checkbox" name="active" id="active" value="1" <?= $article->active ? 'checked' : '' ?> disabled>
              </td>
            </tr>

    <?php } ?>
            
          </table>

          <p>Всего статей: <?php echo $results['totalRows']?></p>

          <p><a href="admin.php?action=newArticle">Добавить новую статью</a></p>

<?php include "templates/include/footer.php" ?>              