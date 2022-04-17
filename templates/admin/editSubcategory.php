<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post"> 
          <!-- Обработка формы будет направлена файлу admin.php ф-ции newCategory либо editCategory в зависимости от formAction, сохранённого в result-е -->
        <input type="hidden" name="subcategoryId" value="<?php echo $results['subcategory']->id ?>"/>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

        <ul>
          <li>
            <label for="name">Название</label>
            <input type="text" name="name" id="name" placeholder="Название подкатегрии" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['subcategory']->name )?>" />
          </li>

          <li>
            <label for="description">Описание</label>
            <textarea name="description" id="description" placeholder="Краткое описание подкатегории" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['subcategory']->description )?></textarea>
          </li>

          <li>
                <label for="categoryId">Категория</label>
                <select name="categoryId" required>
                  <option value=""<?php echo !$results['subcategory']->categoryId ? " selected" : ""?>>(Не выбрано)</option>
                <?php foreach ( $results['categories'] as $category ) { ?>
                  <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['subcategory']->categoryId ) ? " selected" : ""?> ><?php echo htmlspecialchars( $category->name )?></option>
                <?php } ?>
                </select>
              </li>
        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Сохранить" />
          <input type="submit" formnovalidate name="cancel" value="Отменить" />
        </div>

      </form>

    <?php if ( $results['subcategory']->id ) { ?>
          <p><a href="admin.php?action=deleteSubcategory&amp;subcategoryId=<?php echo $results['subcategory']->id ?>" onclick="return confirm('Удалить эту подкатегорию?')">Удалить подкатегорию</a></p>
    <?php } ?>

<?php include "templates/include/footer.php" ?>

