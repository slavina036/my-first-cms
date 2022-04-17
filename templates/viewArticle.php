<?php include "templates/include/header.php" ?>
	  
    <h1 style="width: 75%;"><?php echo htmlspecialchars( $results['article']->title )?></h1>
    <div style="width: 75%; font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>
    <div style="width: 75%;"><?php echo $results['article']->content?></div>
    <p class="pubDate">Опубликовано <?php  echo date('j F Y', $results['article']->publicationDate)?>
    
    <?php if ( $results['category'] ) { ?>
        в 
        <a href="./?action=archive&amp;categoryId=<?php echo $results['category']->id?>">
            <?php echo htmlspecialchars($results['category']->name) ?>
        </a>
        <a href="./?action=archive&amp;subcategoryId=<?php echo $results['subcategory']->id?>">
            <?php echo htmlspecialchars($results['subcategory']->name) ?>
        </a>
    <?php } ?>
        
    </p>

    <p><a href="./">Вернуться на главную страницу</a></p>
	  
<?php include "templates/include/footer.php" ?>    
                