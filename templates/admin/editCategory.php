<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post"> 
          <!-- Обработка формы будет направлена файлу admin.php ф-ции newCategory либо editCategory в зависимости от formAction, сохранённого в result-е -->
        <input type="hidden" name="categoryId" value="<?php echo $results['category']->id ?>"/>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

        <ul>

          <li>
            <label for="name">Название</label>
            <input type="text" name="name" id="name" placeholder="Название категории" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['category']->name )?>" />
          </li>

          <li>
            <label for="description">Описание</label>
            <textarea name="description" id="description" placeholder="Краткое описание категории" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['category']->description )?></textarea>
          </li>

        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Сохранить" />
          <input type="submit" formnovalidate name="cancel" value="Отменить" />
        </div>

      </form>

    <?php if ( $results['category']->id ) { ?>
          <p><a href="admin.php?action=deleteCategory&amp;categoryId=<?php echo $results['category']->id ?>" onclick="return confirm('Удалить эту категорию?')">Удалить категорию</a></p>
    <?php } ?>

<?php include "templates/include/footer.php" ?>

