<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if(isset($data->descripcion) && isset($data->cantidad) && isset($data->lote) && isset($data->vencimiento) && isset($data->precio)){
    // CHECK DATA VALUE IS EMPTY OR NOT
    if(!empty($data->descripcion) && !empty($data->cantidad) && !empty($data->descripcion)){
        
        $insert_query = "INSERT INTO `productos`(descripcion,cantidad,lote,vencimiento,precio) VALUES(:descripcion,:cantidad,:lote,:vencimiento,:precio)";
        
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':descripcion', htmlspecialchars(strip_tags($data->descripcion)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':cantidad', htmlspecialchars(strip_tags($data->cantidad)),PDO::PARAM_INT);
        $insert_stmt->bindValue(':lote', htmlspecialchars(strip_tags($data->lote)),PDO::PARAM_INT);
        $insert_stmt->bindValue(':vencimiento', htmlspecialchars(strip_tags($data->vencimiento)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':precio', htmlspecialchars(strip_tags($data->precio)),PDO::PARAM_STR);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Data Inserted Successfully';
        }else{
            $msg['message'] = 'Data not Inserted';
        } 
        
    }else{
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
    }
}
else{
    $msg['message'] = 'Please fill all the fields | descripcion, cantidad, lote';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>