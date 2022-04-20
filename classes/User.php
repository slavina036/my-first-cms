<?php

/**
 * Класс для обработки пользователей
 */

class User
{
    // Свойства
    /**
    * @var int ID пользователя из базы данны
    */
    public $id = null;
    /**
    * @var string Логин пользователя
    */
    public $login = null;
     /**
    * @var string Пароль пользователя
    */
    public $password = null;
    /**
    * @var int Активность пользователя
    */
    public $active = null;
    
    
    /**
     * Создаст объект пользователя
     */
    public function __construct($data=array())
    {
        
      if (isset($data['id'])) {
          $this->id = (int) $data['id'];
      }
      
      if (isset( $data['login'])) {
          $this->login = (string) $data['login'];     
      }

      if (isset($data['password'])) {
          $this->password = (string) $data['password'];        
      }      
      
      $this->active = !empty($data['active']);  
     }

    
    /**
    * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
    *
    * @param assoc Значения записи формы
    */
    public function storeFormValues ( $params ) {

      // Сохраняем все параметры
      $this->__construct( $params );
    }

    
    /**
    * Возвращаем объект пользователя соответствующий заданному ID
    *
    * @param int ID пользователя
    * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
    */
    public static function getById($id) 
    {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM users WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        
        if ($row) { 
            return new User($row);
        }
    }


    /**
    * Возвращает все объекты Users из базы данных
    */
    public static function getList() 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM users";
        $st = $conn->prepare($sql);
        $st->execute(); 
        $list = array();

        while ($row = $st->fetch()) {
            $user = new User($row);
            $list[] = $user;
        }
        
        // Получаем общее количество пользователй
        
        $sql = "SELECT COUNT(*) AS totalRows FROM users";
        $st = $conn->prepare($sql);
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
    * Вставляем текущий объек Article в базу данных, устанавливаем его ID.
   */
    public function insert() {

        // Есть уже у объекта User ID?
        if ( !is_null( $this->id ) ) trigger_error ( "User::insert(): Attempt to insert an User object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем пользователя
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO users ( login, password, active ) VALUES ( :login, :password, :active )";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":login", $this->login, PDO::PARAM_STR );
        $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    
    /**
    * Обновляем текущий объект в базе данных
    */
    public function update() {

      // Есть ли у объекта ID?
      if ( is_null( $this->id ) ) trigger_error ( "User::update(): "
              . "Attempt to update an User object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем 
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD ); 
      $sql = "UPDATE users SET login = :login, password = :password, active = :active  WHERE id = :id";
      
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":login", $this->login, PDO::PARAM_STR );
      $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
      $st->bindValue( ":active", $this->active, PDO::PARAM_INT );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

    
    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() {

      // Есть ли у объекта ID?
      if ( is_null( $this->id ) ) trigger_error ( "User::delete(): Attempt to delete an USer object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем пользователя
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM users WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }
    
    
}