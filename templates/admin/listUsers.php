

<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

    <h1>Все пользователи</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Логин</th>
              <th>Пароль</th>
              <th>Активность</th>
            </tr>
            
<!--Обращаемся к дате массива $results. Дата = 0 -->
        
    <?php 
    foreach ( $results['users'] as $user ) { ?>

            <tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $user->id?>'">
              <td><?php echo $user->login?></td>
              <td><?php echo $user->password?></td>
              <td>
                  <input type="checkbox" name="active" id="active" value="1" <?= $user->active ? 'checked' : '' ?> disabled>
              </td>
            </tr>
            
    <?php } ?>
            
          </table>
            
    <p>Всего пользователей: <?php echo $results['totalRows']?></p>

          <p><a href="admin.php?action=newUser">Добавить нового пользователя</a></p>

<?php include "templates/include/footer.php" ?>