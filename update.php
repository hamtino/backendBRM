<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


$data = json_decode(file_get_contents("php://input"));


if(isset($data->id)){
    
    $msg['message'] = '';
    $post_id = $data->id;
    
 
    $get_post = "SELECT * FROM `productos` WHERE id=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
 
    if($get_stmt->rowCount() > 0){
        
       
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        $post_descripcion = isset($data->descripcion) ? $data->descripcion : $row['descripcion'];
        $post_cantidad = isset($data->cantidad) ? $data->cantidad : $row['cantidad'];
        $post_lote = isset($data->lote) ? $data->lote : $row['lote'];
        $post_vencimiento = isset($data->vencimiento) ? $data->vencimiento : $row['vencimiento'];
        $post_precio = isset($data->precio) ? $data->precio : $row['precio'];
        
        $update_query = "UPDATE `productos` SET descripcion = :descripcion, cantidad = :cantidad, lote = :lote, vencimiento = :vencimiento, precio = :precio   
        WHERE id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        
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