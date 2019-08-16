<?php
require ("db_files/connection.php");
try {
    $PDO_connection = new PDO($dsn, $user, $password, $opt);

    $query = "SELECT e.position, e.dateOfBirth, e.rank, e.tellNumber, o.date_come, o.date_return
                                               FROM workers e
                                               LEFT JOIN outside_records o on e.worker_id = o.worker_id
                                               WHERE e.user_id = :id" ;
    $sth = $PDO_connection->prepare($query);
    $sth->bindParam(":id", $_POST['name'], PDO::PARAM_STR);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $jsonResult = json_encode($result, JSON_UNESCAPED_UNICODE);
    echo $jsonResult;

} catch (PDOException $e){
    echo $e->getMessage();
};

?>