<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


$data = json_decode(file_get_contents("php://input"));


$msg['message'] = '';


if(isset($data->descripcion) && isset($data->cantidad) && isset($data->lote) && isset($data->vencimiento) && isset($data->precio)){

    if(!empty($data->descripcion) && !empty($data->cantidad) && !empty($data->descripcion)){
        
        $insert_query = "INSERT INTO `productos`(descripcion,cantidad,lote,vencimiento,precio) VALUES(:descripcion,:cantidad,:lote,:vencimiento,:precio)";
        
        $insert_stmt = $conn->prepare($insert_query);

        $insert_stmt->bindValue(':descripcion', htmlspecialchars(strip_tags($data->descripcion)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':cantidad', htmlspecialchars(strip_tags($data->cantidad)),PDO::PARAM_INT);
        $insert_stmt->bindValue(':lote', htmlspecialchars(strip_tags($data->lote)),PDO::PARAM_INT);
        $insert_stmt->bindValue(':vencimiento', htmlspecialchars(strip_tags($data->vencimiento)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':precio', htmlspecialchars(strip_tags($data->precio)),PDO::PARAM_STR);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Datos creados correctamente';
        }else{
            $msg['message'] = 'los Datos no se pudieron crear';
        } 
        
    }else{
        $msg['message'] = 'Se detectaron campos vacios';
    }
}
else{
    $msg['message'] = 'campos incorrectos';
}

echo  json_encode($msg);
?>