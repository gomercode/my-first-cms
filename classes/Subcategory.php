<?php

/**
 * Класс для обработки категорий статей
 */

class Subcategory
{
    // Свойства

    /**
    * @var int ID категории из базы данных
    */
    public $id = null;

    /**
    * @var string Название подкатегории
    */
    public $name = null;

    /**
    * @var string  id категории которой пренадлежит
    */
    public $category = null;




    public function __construct( $data=array() ) {
      if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
      if ( isset( $data['name'] ) ) $this->name = $data['name'];
      if ( isset( $data['category'] ) ) $this->category = $data['category'];
      
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
    * @param int ID подкатегории
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
    * @return Array|false Двух элементный массив: results => массив с объектами Subcategory; totalRows => общее количество подкатегорий
    */
    public static function getList( $numRows=1000000, $order="name ASC" ) 
    {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
  
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM subcategories
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
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
    }


    /**
    * Вставляем текущий объект subcategory в базу данных и устанавливаем его свойство ID.
    */

    public function insert() {

      // У объекта subcategory уже есть ID?
      if ( !is_null( $this->id ) ) trigger_error ( "Subcategory::insert(): Attempt to insert a subcategory object that already has its ID property set (to $this->id).", E_USER_ERROR );

      // Вставляем подкатегорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "INSERT INTO subcategories ( name, category ) VALUES ( :name, :category )";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":category", $this->category, PDO::PARAM_STR );
      $st->execute();
      $this->id = $conn->lastInsertId();
      $conn = null;
    }


    /**
    * Обновляем текущий объект subcategory в базе данных.
    */

    public function update() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Subcategory::update(): Attempt to update a subcategory object that does not have its ID property set.", E_USER_ERROR );

      // Обновляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $sql = "UPDATE subcategories SET name=:name, category=:category WHERE id = :id";
      $st = $conn->prepare ( $sql );
      $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
      $st->bindValue( ":category", $this->category, PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }


    /**
    * Удаляем текущий объект subcategory из базы данных.
    */

    public function delete() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "subcategory::delete(): Attempt to delete a subcategory object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем категорию
      $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
      $st = $conn->prepare ( "DELETE FROM subcategories WHERE id =:id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $conn = null;
    }

}
	  
	

