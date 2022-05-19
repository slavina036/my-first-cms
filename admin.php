<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    case 'listCategories':
        listCategories();
        break;
    case 'newCategory':
        newCategory();
        break;
    case 'editCategory':
        editCategory();
        break;
    case 'deleteCategory':
        deleteCategory();
        break;
    case 'listSubcategories':
        listSubcategories();
        break;
    case 'newSubcategory':
        newSubcategory();
        break;
    case 'editSubcategory':
        editSubcategory();
        break;
    case 'deleteSubcategory':
        deleteSubcategory();
        break;
    case 'newUser':
        newUser();
        break;
    case 'editUser':
        editUser();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    case 'listUsers':
        listUsers();
        break;
    default:
        listArticles();
}

/**
 * Авторизация пользователя (админа) -- установка значения в сессию
 */
function login() {

    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    if (isset($_POST['login'])) {

        // Пользователь получает форму входа: попытка авторизировать пользователя

        if ($_POST['username'] == ADMIN_USERNAME
                && $_POST['password'] == ADMIN_PASSWORD) {

            // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора

            $_SESSION['username'] = ADMIN_USERNAME;
            header( "Location: admin.php");

        } else if (getlog($_POST['username'], $_POST['password'])){

            // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
            $_SESSION['username'] = ADMIN_USERNAME;
            header( "Location: admin.php");

            } else {

                // Ошибка входа: выводим сообщение об ошибке для пользователя
                $results['errorMessage'] = "Неправильный пароль, попробуйте ещё раз.";
                require( TEMPLATE_PATH . "/admin/loginForm.php" );
            }

    } else {

      // Пользователь еще не получил форму: выводим форму
      require(TEMPLATE_PATH . "/admin/loginForm.php");
    }
}


/**
* Проверяем логин, пароль и активность пользователя для авторизации
*/
function getlog ($login, $password)
{
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users "
            . "WHERE active=1 "
            . "AND login= :login "
            . "AND password= :password";
    $st = $conn->prepare($sql);
    $st->bindValue(":login", $login, PDO::PARAM_STR);
    $st->bindValue(":password", $password, PDO::PARAM_STR);
    $st->execute();
    $row = $st->fetch();
    $conn = null;

    return ($row);
}


function logout() {
    unset( $_SESSION['username'] );
    header( "Location: admin.php" );
}


function newArticle() {

    $results = array();
    $results['pageTitle'] = "Новая статья";
    $results['formAction'] = "newArticle";

    if ( isset( $_POST['saveChanges'] ) ) {
//            echo "<pre>";
//            print_r($results);
//            print_r($_POST);
//            echo "<pre>";
//            die();
//            В $_POST данные о статье сохраняются корректно
        // Пользователь получает форму редактирования статьи: сохраняем новую статью
        $article = new Article();
        $article->storeFormValues( $_POST );
//            echo "<pre>";
//            print_r($article);
//            echo "<pre>";
//            die();
//            А здесь данные массива $article уже неполные(есть только Число от даты, категория и полный текст статьи)
        $article->insert();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['article'] = new Article;
        $data = Category::getList();
        $results['categories'] = $data['results'];
        $results['subcategories'] = Subcategory::getList(order: 'sc.categoryId');
        $results['authors'] = User::getList();
        $results['listArticlesIdTitle'] = Article::getListArticlesIdTitle();
        require( TEMPLATE_PATH . "/admin/editArticle.php" );
    }
}


/**
 * Редактирование статьи
 *
 * @return null
 */
function editArticle() {

    $results = array();
    $results['pageTitle'] = "Редактирование статьи";
    $results['formAction'] = "editArticle";

    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
            header( "Location: admin.php?error=articleNotFound" );
            return;
        }


        $article->storeFormValues( $_POST );
        $article->update();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользвоатель еще не получил форму редактирования: выводим форму
        $results['article'] = Article::getById((int)$_GET['articleId']);
        $data = Category::getList();
        $results['categories'] = $data['results'];
        $results['subcategories'] = Subcategory::getList(order: 'sc.categoryId');
        $results['authors'] = User::getList();
        $results['listArticlesIdTitle'] = Article::getListArticlesIdTitle();
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }

}


function deleteArticle() {

    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header( "Location: admin.php?error=articleNotFound" );
        return;
    }

    $article->delete();
    header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
    $results = array();

    $data = Article::getList();
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];

    $data = Category::getList();
    $results['categories'] = array();
    foreach ($data['results'] as $category) {
        $results['categories'][$category->id] = $category;
    }
    $data = Subcategory::getList();
    $results['subcategories'] = array();
    foreach ($data['results'] as $subcategory) {
        $results['subcategories'][$subcategory->id] = $subcategory;
    }

    $results['pageTitle'] = "Список статей";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "articleNotFound")
            $results['errorMessage'] = "Error: Article not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "articleDeleted")  {
            $results['statusMessage'] = "Article deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/listArticles.php" );
}

function listCategories() {
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Список категорий";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
        if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
    }

    require( TEMPLATE_PATH . "/admin/listCategories.php" );
}


function newCategory() {

    $results = array();
    $results['pageTitle'] = "Новая категрия";
    $results['formAction'] = "newCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category->storeFormValues( $_POST );
        $category->insert();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function editCategory() {

    $results = array();
    $results['pageTitle'] = "Редактирование категории";
    $results['formAction'] = "editCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the category changes

        if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
          header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
          return;
        }

        $category->storeFormValues( $_POST );
        $category->update();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById( (int)$_GET['categoryId'] );
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function deleteCategory() {

    if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
        header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
        return;
    }

    $articles = Article::getList( 1000000, $category->id );

    if ( $articles['totalRows'] > 0 ) {
        header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
        return;
    }

    $category->delete();
    header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}


/**
 * Список подкатегорий
 *
 * @return null
 */

function listSubcategories() {
    $results = array();

    $data = Subcategory::getList();
    $results['subcategories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Список подкатегорий";

    $data = Category::getList();
    $results['categories'] = array();
    foreach ($data['results'] as $category) {
        $results['categories'][$category->id] = $category;
    }


    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "subcategoryNotFound" ) $results['errorMessage'] = "Ошибка: подкатегория не найдена";
        if ( $_GET['error'] == "subcategoryContainsArticles" ) $results['errorMessage'] = "Ошибка: Подкатегория содержит статьи. Удалите статьи или отнесите их к другой подкатегории, прежде чем удалять эту подкатегорию.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Изменения сохранены.";
        if ( $_GET['status'] == "subcategoryDeleted" ) $results['statusMessage'] = "Подкатегория удалена.";
    }

    require( TEMPLATE_PATH . "/admin/listSubcategories.php" );
}


/**
 * Создание новой подкатегории
 *
 * @return null
 */
function newSubcategory() {

    $results = array();
    $results['pageTitle'] = "Новая подкатегория";
    $results['formAction'] = "newSubcategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the subcategory edit form: save the new subcategory
        $subcategory = new Subcategory;
        $subcategory->storeFormValues( $_POST );
        $subcategory->insert();
        header( "Location: admin.php?action=listSubcategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listSubcategories" );
    } else {

        // User has not posted the subcategory edit form yet: display the form
        $data = Category::getList();
        $results['categories'] = $data['results'];
        $results['subcategory'] = new Subcategory;
        require( TEMPLATE_PATH . "/admin/editSubcategory.php" );
    }

}


/**
 * Редактирование подкатегори
 *
 * @return null
 */
function editSubcategory() {

    $results = array();
    $results['pageTitle'] = "Редактирование подкатегории";
    $results['formAction'] = "editSubcategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the subcategory edit form: save the subcategory changes

        if ( !$subcategory = Subcategory::getById( (int)$_POST['subcategoryId'] ) ) {
          header( "Location: admin.php?action=listSubcategories&error=subcategoryNotFound" );
          return;
        }

        $subcategory->storeFormValues( $_POST );
        $subcategory->update();
        $subcategory->syncCategoriesInArticles();
        header( "Location: admin.php?action=listSubcategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the subcategory list
        header( "Location: admin.php?action=listSubcategories" );
    } else {

        // User has not posted the subcategory edit form yet: display the form
        $results['subcategory'] = Subcategory::getById( (int)$_GET['subcategoryId'] );
        $results['subcategories'] = Subcategory::getList(order: 'sc.categoryId');
        $data = Category::getList();
        $results['categories'] = $data['results'];

        require( TEMPLATE_PATH . "/admin/editSubcategory.php" );
    }

}


/**
 * Удаление подкатегории
 *
 * @return null
 */
function deleteSubcategory() {

    if ( !$subcategory = Subcategory::getById( (int)$_GET['subcategoryId'] ) ) {
        header( "Location: admin.php?action=listSubcategories&error=subcategoryNotFound" );
        return;
    }

    $articles = Article::getList(subcategoryId: $subcategory->id );
    if ( $articles['totalRows'] > 0 ) {
        header( "Location: admin.php?action=listSubcategories&error=subcategoryContainsArticles" );
        return;
    }

    $subcategory->delete();
    header( "Location: admin.php?action=listSubcategories&status=subcategoryDeleted" );
}


function newUser() {

    $results = array();
    $results['pageTitle'] = "Новый пользователь";
    $results['formAction'] = "newUser";

    if ( isset( $_POST['saveChanges'] ) ) {
    $user = new User();
        $user->storeFormValues( $_POST );
        $user->insert();
        header( "Location: admin.php?action=listUsers&status=userAdded" );

    } elseif ( isset( $_POST['cancel'] ) ) {

// Пользователь сбросил результаты редактирования: возвращаемся к списку пользователей
        header( "Location: admin.php?action=listUsers" );
    } else {

// Пользователь еще не получил форму редактирования: выводим форму
        $results['user'] = new User;
        require( TEMPLATE_PATH . "/admin/editUser.php" );
    }
}


function editUser() {

    $results = array();
    $results['pageTitle'] = "Редактирование данных пользователя";
    $results['formAction'] = "editUser";

    if (isset($_POST['saveChanges'])) {

// Пользователь получил форму редактирования пользователя: сохраняем изменения
        if ( !$user = User::getById( (int)$_POST['userId'] ) ) {
            header( "Location: admin.php?error=userNotFound" );
            return;
        }

        $user->storeFormValues($_POST);
        $user->update();
        header( "Location: admin.php?action=listUsers&status=changesSaved" );

    } elseif (isset($_POST['cancel'])) {

// Пользователь отказался от результатов редактирования: возвращаемся к списку пользователей
        header( "Location: admin.php?action=listUsers" );
    } else {

// Пользвоатель еще не получил форму редактирования: выводим форму
        $results['user'] = User::getById((int)$_GET['userId']);
        require(TEMPLATE_PATH . "/admin/editUser.php");
    }

}


function deleteUser() {

    if ( !$user = User::getById( (int)$_GET['userId'] ) ) {
        header( "Location: admin.php?action=listUsers&error=userNotFound" );
        return;
    }

    $articles = Article::getList(numRows: 1000000, userId: $user->id );

    if ( $articles['totalRows'] > 0 ) {
        header( "Location: admin.php?action=listUsers&error=userContainsArticles" );
        return;
    }

    $user->delete();
    header("Location: admin.php?action=listUsers&status=userDeleted");
}


function listUsers() {
    $results = array();

    $data = User::getList();
    $results['users'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];

    $results['pageTitle'] = "Список всех пользователи";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "userNotFound" ) $results['errorMessage'] = "Ошибка: пользователь не найден.";
        if ( $_GET['error'] == "userContainsArticles" ) $results['errorMessage'] = "Ошибка: Пользователь является автором или соавторм.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Ваши изменения сохранены.";
        }
        if ($_GET['status'] == "userDeleted")  {
            $results['statusMessage'] = "Пользователь удален.";
        }
        if ($_GET['status'] == "userAdded")  {
            $results['statusMessage'] = "Пользователь добавлен.";
        }
    }

    require(TEMPLATE_PATH . "/admin/listUsers.php" );
}
