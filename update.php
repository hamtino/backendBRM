<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->id)){
    
    $msg['message'] = '';
    $post_id = $data->id;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM `productos` WHERE id=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
        $post_descripcion = isset($data->descripcion) ? $data->descripcion : $row['descripcion'];
        $post_cantidad = isset($data->cantidad) ? $data->cantidad : $row['cantidad'];
        $post_lote = isset($data->lote) ? $data->lote : $row['lote'];
        $post_vencimiento = isset($data->vencimiento) ? $data->vencimiento : $row['vencimiento'];
        $post_precio = isset($data->precio) ? $data->precio : $row['precio'];
        
        $update_query = "UPDATE `productos` SET descripcion = :descripcion, cantidad = :cantidad, lote = :lote, vencimiento = :vencimiento, precio = :precio   
        WHERE id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':descripcion', htmlspecialchars(strip_tags($post_descripcion)),PDO::PARAM_STR);
        $update_stmt->bindValue(':cantidad', htmlspecialchars(strip_tags($post_cantidad)),PDO::PARAM_INT);
        $update_stmt->bindValue(':lote', htmlspecialchars(strip_tags($post_lote)),PDO::PARAM_INT);
        $update_stmt->bindValue(':vencimiento', htmlspecialchars(strip_tags($post_vencimiento)),PDO::PARAM_STR);
        $update_stmt->bindValue(':precio', htmlspecialchars(strip_tags($post_precio)),PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $post_id,PDO::PARAM_INT);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'Data updated successfully';
        }else{
            $msg['message'] = 'data not updated';
        }   
        
    }
    else{
        $msg['message'] = 'Invlid ID';
    }  
    
    echo  json_encode($msg);
    
}
?>