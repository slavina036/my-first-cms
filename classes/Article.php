<?php


/**
 * Класс для обработки статей
 */
class Article
{
    // Свойства
    /**
    * @var int ID статей из базы данны
    */
    public $id = null;

    /**
    * @var int Дата первой публикации статьи
    */
    public $publicationDate = null;

    /**
    * @var string Полное название статьи
    */
    public $title = null;

     /**
    * @var int ID категории статьи
    */
    public $categoryId = null;
    
     /**
    * @var int ID подкатегории статьи
    */
    public $subcategoryId = null;

    /**
    * @var string Краткое описание статьи
    */
    public $summary = null;

    /**
    * @var string HTML содержание статьи
    */
    public $content = null;
    
    /**
    * @var string HTML содержание статьи
    */
    public $authors = null;
    
    /**
    * @param assoc Значения свойств
    */
    public $active = null;
    
      
    /**
     * Создаст объект статьи
     * 
     * @param array $data массив значений (столбцов) строки таблицы статей
     */
    public function __construct($data=array())
    {

        if (isset($data['id'])) {
          $this->id = (int) $data['id'];
      }
      
      if (isset( $data['publicationDate'])) {
          $this->publicationDate = (string) $data['publicationDate'];     
      }

      if (isset($data['title'])) {
          $this->title = $data['title'];        
      }
      
      if (isset($data['categoryId'])) {
          $this->categoryId = (int) $data['categoryId'];      
      }
      
      if (isset($data['subcategoryId'])) {
          $this->subcategoryId = (int) $data['subcategoryId'];      
      }
      
      if (isset($data['summary'])) {
          $this->summary = $data['summary'];         
      }
      
      if (isset($data['content'])) {
          $this->content = $data['content'];  
      }
      
      if (isset($data['authors'])){
          $this->authors = (array) $data['authors'];  
      }
      
      $this->active = !empty($data['active']); 
     }


    /**
    * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
    *
    * @param assoc Значения записи формы
    */
    public function storeFormValues ( $params ) {
        
      if (isset($params['subcategoryId'])) {
          $subcategory = Subcategory::getById($params['subcategoryId']);
          $categoryId = $subcategory->categoryId;
          $params['categoryId'] = $categoryId;
      }
      // Сохраняем все параметры
      $this->__construct( $params );
      
//echo "<pre>";
//print_r($params);
//echo "</pre>";
//die();
        // Разбираем и сохраняем дату публикации
      if ( isset($params['publicationDate']) ) {
        $publicationDate = explode ( '-', $params['publicationDate'] );

        if ( count($publicationDate) == 3 ) {
          list ( $y, $m, $d ) = $publicationDate;
          $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
        }
      }
    }


    /**
    * Возвращаем объект статьи соответствующий заданному ID статьи
    *
    * @param int ID статьи
    * @return Article|false Объект статьи или false, если запись не найдена или возникли проблемы
    */
    public static function getById($id) {
        
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) "
                . "AS publicationDate FROM articles WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();

        $row = $st->fetch();
        $conn = null;
        
        if ($row) { 
            
            $row['authors'] = self::getAuthors($id);
            $article = new Article($row);
            
            return $article;
        }
    }

    
    /**
     * Получаем из базы данных имена авторов статьи по ID статьи
     */
   public static function getAuthors($id): array
   {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT u.* FROM users u JOIN users_articles ua ON u.id = ua.userId "
                . "where ua.articleId = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        
        $authors = $st->fetchAll();
        return array_map(
            fn(array $row): User => new User($row),
            $authors
        );
    }
    

    /**
    * Возвращает все (или диапазон) объекты Article из базы данных
    *
    * @param int $numRows Количество возвращаемых строк (по умолчанию = 1000000)
    * @param int $categoryId Вернуть статьи только из категории с указанным ID
    * @param int $subcategoryId Вернуть статьи только из подкатегории с указанным ID
    * @param string $order Столбец, по которому выполняется сортировка статей (по умолчанию = "publicationDate DESC")
    * @return Array|false Двух элементный массив: results => массив объектов Article; totalRows => общее количество строк
    */
    public static function getList($numRows=1000000, $categoryId=null, 
                                   $order="publicationDate DESC", $active=null, 
                                   $subcategoryId=null, $userId=null) 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $fromPart = "FROM articles";
        $clause = '';
        
        if (!empty($userId)) {
            $clause = " JOIN users_articles ON users_articles.articleId = articles.id "
                    . "WHERE users_articles.userId = :userId ";
        } else {
            
            $clauses = [];

            if (!empty($categoryId)) {
                $clauses[] = 'categoryId = :categoryId';
            }
            if (!empty($active)) {
                $clauses[] = 'active= :active';
            }
            if (!empty($subcategoryId)) {
                $clauses[] = 'subcategoryId = :subcategoryId';
            }

            $clause = implode(' AND ', $clauses);

            if (!empty($clause)) {
                $clause = " WHERE $clause ";
            }
        }
        
        $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate
                $fromPart $clause
                ORDER BY  $order  LIMIT :numRows";
        $st = $conn->prepare($sql);
//                        echo "<pre>";
//                        print_r($st);
//                        echo "</pre>"; die();
//                        Здесь $st - текст предполагаемого SQL-запроса, причём переменные не отображаются
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        
        if(!empty($active))
            $st->bindValue(":active", $active, PDO::PARAM_INT);
        
        if ($categoryId) 
            $st->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT);
        
        if ($subcategoryId) 
            $st->bindValue( ":subcategoryId", $subcategoryId, PDO::PARAM_INT);
        
        if ($userId) 
            $st->bindValue( ":userId", $userId, PDO::PARAM_INT);
        
        $st->execute(); // выполняем запрос к базе данных
//                        echo "<pre>";
//                        print_r($st);
//                        echo "</pre>";
//                        Здесь $st - текст предполагаемого SQL-запроса, причём переменные не отображаются
        $list = array();
        
        while ($row = $st->fetch()) {
            $row['authors'] = self::getAuthors($row['id']);
            $article = new Article($row);
            $list[] = $article;
        }
            
        // Получаем общее количество статей, которые соответствуют критерию
        $sql = "SELECT COUNT(*) AS totalRows $fromPart $clause";
        $st = $conn->prepare($sql);
        if(!empty($active))
            $st->bindValue(":active", $active, PDO::PARAM_INT);
        if ($categoryId) 
            $st->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT);
        if ($subcategoryId) 
            $st->bindValue( ":subcategoryId", $subcategoryId, PDO::PARAM_INT);
        if ($userId) 
            $st->bindValue( ":userId", $userId, PDO::PARAM_INT);
        $st->execute();
        $totalRows = $st->fetch();
        $conn = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
    }


    /**
    * Вставляем текущий объект статьи в базу данных, устанавливаем его свойства.
    */


    /**
    * Вставляем текущий объек Article в базу данных, устанавливаем его ID.
    */
    public function insert() {
        
        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );
        // Вставляем статью
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO articles ( publicationDate, categoryId, subcategoryId, title, summary, content, active ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId, :subcategoryId, :title, :summary, :content, :active )";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
        $st->bindValue( ":subcategoryId", $this->subcategoryId, PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        
        echo "<pre>"; 
        print_r($this);
        echo "</pre>"; die();
        
        
        foreach ($this->authors as $key => $userId) {
            echo $userId;
            $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
            $sql = "INSERT INTO authors (userId, articleId) VALUES (:userId, :articleId)";
            $st = $conn->prepare($sql);
            $st->bindValue( ":userId", $userId, PDO::PARAM_INT );
            $st->bindValue( ":articleId", $this->id, PDO::PARAM_INT );
            $st->execute();
            $conn = null;
            
        }
//        die();
    }

    /**
    * Обновляем текущий объект статьи в базе данных
    */
    public function update() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::update(): "
              . "Attempt to update an Article object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate),"
              . " categoryId=:categoryId, subcategoryId=:subcategoryId, title=:title, "
              . " summary=:summary, active=:active, content=:content WHERE id = :id";
      
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
      $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
      $st->bindValue( ":subcategoryId", $this->subcategoryId, PDO::PARAM_INT );
      $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
      $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
      $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
      $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
      
//      $sql = "UPDATE author SET "
    }


    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
    
}
