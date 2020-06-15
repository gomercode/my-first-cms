<?php


class Author
{
public static function update($articleId,$users){



    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "DELETE FROM authors  WHERE article_id=:id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":id", $articleId, PDO::PARAM_INT );
    $st->execute();

    foreach ($users as $user){

        $sql = "INSERT INTO authors (article_id, user_id) VALUES (:id, :user)";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":id",$articleId, PDO::PARAM_INT );



        $st->bindValue( ":user",$user, PDO::PARAM_INT );
        $st->execute();
    }

    $conn = null;
}
}