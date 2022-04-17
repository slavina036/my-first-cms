<?php include "templates/include/header.php" ?>
	<?php include "templates/admin/include/header.php" ?>
	  
            <h1>Все подкатегории статей</h1>
	  
	<?php if ( isset( $results['errorMessage'] ) ) { ?>
	        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>
	  
	  
	<?php if ( isset( $results['statusMessage'] ) ) { ?>
	        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
	<?php } ?>
	  
            <table>
                <tr>
                    <th>Подкатегории</th>
                    <th>Категории</th>
                </tr>

        <?php foreach ( $results['subcategories'] as $subcategory ) { ?>

                <tr onclick="location='admin.php?action=editSubcategory&amp;subcategoryId=<?php echo $subcategory->id?>'">
                    <td>
                        <?php echo $subcategory->name?>
                    </td>
                    <td>
                    <!--<?php echo $results['categories'][$subcategory->categoryId]->name?> Эта строка была скопирована с сайта-->
                    <!--<?php echo "<pre>"; print_r ($subcategory); echo "</pre>"; ?> Здесь объект $article содержит в себе только ID категории. А надо по ID достать название категории-->
                    <!--<?php echo "<pre>"; print_r ($results); echo "</pre>"; ?> Здесь есть доступ к полному объекту $results -->

                        <?php 
                        if(isset ($subcategory->categoryId)) {
                            echo $results['categories'][$subcategory->categoryId]->name;                        
                        }
                        else {
                        echo "Без категории";
                        }?>
                      </td>    
                </tr>

        <?php } ?>
                

            </table>

            <p>Всего категорий: <?php echo $results['totalRows']?> </p>

            <p><a href="admin.php?action=newSubcategory">Добавить новую подкатегорию</a></p>
	  
	<?php include "templates/include/footer.php" ?>
