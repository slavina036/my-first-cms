<div id="adminHeader">
    <h2>Widget News Admin</h2>
    <p>Вы вошли как <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>.
        <a href="admin.php?action=listArticles">Статьи</a> 
        <a href="admin.php?action=listCategories">Категории</a> 
        <a href="admin.php?action=listSubcategories">Подкатегории</a> 
        <a href="admin.php?action=listUsers"?>Пользователи</a>
        <a href="admin.php?action=logout"?>Выйти</a>
    </p>
</div>
