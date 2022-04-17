<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<!--        <?php echo "<pre>";
            print_r($results);
            print_r($data);
        echo "<pre>"; ?> Данные о массиве $results и типе формы передаются корректно-->

        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="userId" value="<?php echo $results['user']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>
            <ul>

              <li>
                <label for="title">Логин</label>
                <input type="text" name="login" id="title" placeholder="Введите логин пользователя" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['user']->login )?>" />
              </li>
              
              <li>
                <label for="password">Пароль</label>
                <input type="text" name="password" id="password" placeholder="Введите пароль пользователя" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['user']->password )?>" />
              </li>
              
              <li>
                  <label for="active">Активность</label>
                  <input type="checkbox" name="active" id="active" value="1" <?= $results['user']->active ? 'checked' : '' ?> />
              </li> 
             
            </ul>

            <div class="buttons">
              <input type="submit" name="saveChanges" value="Сохранить" />
              <input type="submit" formnovalidate name="cancel" value="Отменить" />
            </div>

        </form>

    <?php if ($results['user']->id) { ?>
          <p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['user']->id ?>" onclick="return confirm('Удалить этого пользователя?')">
                  Удалить этого пользователя
             </a>
          </p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>
