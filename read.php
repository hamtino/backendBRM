<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");


require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();


if(isset($_GET['id']))
{
  
    $post_id = filter_var($_GET['id'], FILTER_VALIDATE_INT,[
        'options' => [
            'default' => 'all_posts',
            'min_range' => 1
        ]
    ]);
}
else{
    $post_id = 'all_posts';
}

$sql = is_numeric($post_id) ? "SELECT * FROM `productos` WHERE id='$post_id'" : "SELECT * FROM `productos`"; 

$stmt = $conn->prepare($sql);

$stmt->execute();


if($stmt->rowCount() > 0){
   
    $posts_array = [];
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        $post_data = [
            'id' => $row['id'],
            'descripcion' => $row['descripcion'],
            'cantidad' => $row['cantidad'],
            'lote' => $row['lote'],
            'vencimiento' => $row['vencimiento'],
            'precio' => $row['precio'],
        ];
        
        array_push($posts_array, $post_data);
    }
    
    echo json_encode($posts_array);
 

}
else{
   
    echo json_encode(['message'=>'No post found']);
}
?>