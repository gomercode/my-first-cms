<?php


/**
 * Класс для обработки статей
 */
class User
{
    // Свойства
    /**
    * @var int ID статей из базы данны
    */
    public $id = null;

   

    /**
    * @var string логин
    */
    public $login = null;

     /**
    * @var string password
    */
    public $password = null;

     /**
    * @var string активность пользователя
    */
    public $activity = null;
   
    /**
     * Создаст объект user
     * 
     * @param array $data массив значений (столбцов) строки таблицы user
     */
    public function __construct($data=array())
    {
        
      if (isset($data['id'])) {
          $this->id = (int) $data['id'];
      }
      
      if (isset( $data['login'])) {
          $this->login = (string) $data['login'];     
      }

      //die(print_r($this->publicationDate));

      if (isset($data['password'])) {
          $this->password = $data['password'];        
      }
     
       if (isset($data['activity'])) {
          $this->activity = $data['activity'];         
      }
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
    * Возвращаем объект user соответствующий заданному ID user
    *
    * @param int ID user
    * @return user|false Объект user или false, если запись не найдена или возникли проблемы
    */
    public static function getById($id) {
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
    * Возвращает все (или диапазон) объекты User из базы данных
    *
    * @param int $numRows Количество возвращаемых строк (по умолчанию = 1000000)
    * @param string $order Столбец, по которому выполняется сортировка статей (по умолчанию = "id")
    * @return Array|false Двух элементный массив: results => массив объектов User; totalRows => общее количество строк
    */
    public static function getList($numRows=1000000, $order="id") 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT SQL_CALC_FOUND_ROWS *
                FROM users 
                ORDER BY  $order  LIMIT :numRows";
        
        $st = $conn->prepare($sql);
//                        echo "<pre>";
//                        print_r($st);
//                        echo "</pre>";
//                        Здесь $st - текст предполагаемого SQL-запроса, причём переменные не отображаются
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        $st->execute(); // выполняем запрос к базе данных
//                        echo "<pre>";
//                        print_r($st);
//                        echo "</pre>";
//                        Здесь $st - текст предполагаемого SQL-запроса, причём переменные не отображаются
        $list = array();

        while ($row = $st->fetch()) {
            $user = new User($row);
            $list[] = $user;
        }

        // Получаем общее количество пользователей, которые соответствуют критерию
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
    }


    /**
    * Вставляем текущий объект пользователя в базу данных, устанавливаем его свойства.
    */


    /**
    * Вставляем текущий объек user в базу данных, устанавливаем его ID.
    */
    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "User::insert(): Attempt to insert an user object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем статью
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO users (login, password, activity) VALUES (:login, :password, :activity)";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":login", $this->login, PDO::PARAM_STR );
        $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
        if($this->activity == "check"){
            $st->bindValue( ":activity", "1", PDO::PARAM_INT );
        }
        else{
              $st->bindValue( ":activity", "0", PDO::PARAM_INT );
        }
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }

    /**
    * Обновляем текущий объект user в базе данных
    */
    public function update() {

      // Есть ли у объекта user ID?
      if ( is_null( $this->id ) ) trigger_error ( "User::update(): "
              . "Attempt to update an user object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем user
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE users SET login=:login, password=:password, activity=:activity  WHERE id = :id";
      
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":login", $this->login, PDO::PARAM_STR );
      $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
       if($this->activity == "check"){
           $st->bindValue(":activity", "1", PDO::PARAM_INT );
        }
        else{
              $st->bindValue(":activity", "0", PDO::PARAM_INT );
        }
      $st->execute();
      $conn = null;
    }


    /**
    * Удаляем текущий объект user из базы данных
    */
    public function delete() {

      // Есть ли у объекта user ID?
      if ( is_null( $this->id ) ) trigger_error ( "User::delete(): Attempt to delete an user object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM users WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

}