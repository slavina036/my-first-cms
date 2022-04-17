<?php

/**
 * Класс для обработки подкатегорий статей
 */

class Subcategory
{
    // Свойства

    /**
    * @var int ID подкатегории из базы данных
    */
    public $id = null;
 
    /**
    * @var string Название  подкатегории
    */
    public $name = null;
 
    /**
    * @var int ID категории из базы данных
    */
    public $categoryId = null;
 
    /**
    * @var string Название  категории
    */
    public $categoryName = null;

    /**
    * @var string Короткое описание подкатегории
    */
    public $description = null;


    /**
    * Устанавливаем свойства объекта с использованием значений в передаваемом массиве
    *
    * @param assoc Значения свойств
    */
    
    public function __construct( $data=array() ) {
      if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if ( isset( $data['name'] ) ) $this->name = $data['name'];
      if ( isset( $data['description'] ) ) $this->description = $data['description'];
      if ( isset( $data['categoryId'] ) ) $this->categoryId = $data['categoryId'];
      if ( isset( $data['category_name'] ) ) $this->categoryName = $data['category_name'];
    }

    /**
    * Устанавливаем свойства объекта с использованием значений из формы редактирования
    *
    * @param assoc Значения из формы редактирования
    */

    public function storeFormValues ( $params ) {

      // Store all the parameters
      $this->__construct( $params );
    }


    /**
    * Возвращаем объект Subcategory, соответствующий заданному ID
    *
    * @param int ID категории
    * @return Subcategory|false Объект Subcategory object или false, если запись не была найдена или в случае другой ошибки
    */

    public static function getById( $id ) 
    {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM subcategories WHERE id = :id";
        $st = $conn->prepare( $sql );
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) 
            return new Subcategory($row);
    }


    /**
    * Возвращаем все (или диапазон) объектов Subcategory из базы данных
    *
    * @param int Optional Количество возвращаемых строк (по умолчаниюt = all)
    * @param string Optional Столбец, по которому сортируются категории(по умолчанию = "name ASC")
    * @return Array|false Двух элементный массив: results => массив с объектами Category; totalRows => общее количество категорий
    */
    public static function getList( $numRows=1000000, $order="name ASC" ) 
    { 
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
        $fromPart = "FROM subcategories sc JOIN categories c ON sc.categoryId = c.id";
        $sql = "SELECT sc.*, c.name AS category_name $fromPart
                ORDER BY $order LIMIT :numRows";

        $st = $conn->prepare( $sql );
        $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
        $st->execute();
        $list = array();

        while ( $row = $st->fetch() ) {
          $subcategory = new Subcategory( $row );
          $list[] = $subcategory;
        }

        // Получаем общее количество категорий, которые соответствуют критериям
        $sql = "SELECT COUNT(*) AS totalRows $fromPart";
        $totalRows = $conn->query( $sql )->fetch();
        $conn = null;
        return [
            'results' => $list,
            'totalRows' => $totalRows[0]
        ];
    }

    
    /**
    * Синхронизиреум изменение категории при редактировании подкатегории с данными в 
    * таблице articles в БД
    */
    public function syncCategoriesInArticles(){
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "UPDATE articles a set a.categoryId  = "
                . "( select categoryId from subcategories s where s.id = a.subcategoryId) "
                . "where a.subcategoryId = :subcategoryId ";
        
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":subcategoryId", $this->id, PDO::PARAM_INT );
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }
    /**
    * Вставляем текущий объект Subategory в базу данных и устанавливаем его свойство ID.
    */

    public function insert() {

      // У объекта Subcategarticlesory уже есть ID?
      if ( !is_null( $this->id ) ) trigger_error ( "Subcategory::insert(): Attempt to insert a Subcategory object that already has its ID property set (to $this->id).", E_USER_ERROR );

      // Вставляем подкатегорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "INSERT INTO subcategories ( name, categoryId, description ) VALUES ( :name, :categoryId, :description )";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_STR );
      $st->execute();
      $this->id = $conn->lastInsertId();
      $conn = null;
    }


    /**
    * Обновляем текущий объект Subcategory в базе данных.
    */

    public function update() {

      // У объекта Subcategory  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Subcategory::update(): Attempt to update a Subcategory object that does not have its ID property set.", E_USER_ERROR );

      // Обновляем подкатегорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE subcategories SET name=:name, categoryId=:categoryId, description=:description WHERE id = :id";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
      $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }


    /**
    * Удаляем текущий объект Subcategory из базы данных.
    */

    public function delete() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Subcategory::delete(): Attempt to delete a Subcategory object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM subcategories WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

}
	  
	

