<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Content-type: application/x-www-form-urlencoded');

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$db = new mysqli('localhost','root','','curso_angular4');

//Listar todos los productos

$app->get('/productos', function() use($db, $app){
    $sql = 'SELECT * FROM productos ORDER BY id DESC;';
    $query = $db->query($sql);
    $productos = array();

    while($producto = $query->fetch_assoc()) {
        $productos[] = $producto;
    }

    $result = array(
        'status'=> 'success',
        'code' => 200,
        'data' => $productos
    );

    echo json_encode($result);
});

//Devolver un solo producto

$app->get('/producto/:id', function($id) use($db, $app){
    $sql = 'SELECT * FROM productos WHERE id = '.$id;
    $query = $db->query($sql);

    if($query->num_rows == 1){
        $producto = $query->fetch_assoc();
        $result = array(
            'status'=> 'success',
            'code' => 200,
            'data' => $producto
        );
    }else{
        $producto = null;
        $result = array(
            'status'=> 'error',
            'code' => 404,
            'data' => 'producto no disponible'
        );
    }

    

    echo json_encode($result);
});

//Eliminar un producto

$app->get('/delete-producto/:id', function($id) use($db, $app){
    $sql='DELETE FROM productos where id ='.$id;
    $query = $db->query($sql);

    if($query){
        $result = array(
            'status'=> 'success',
            'code' => 200,
            'message' => 'El producto se elimino correctamente'
        );
    }else{
        $result = array(
            'status'=> 'error',
            'code' => 404,
            'message' => 'El producto no se ha eliminado correctamente'
        );
    }
    echo json_encode($result);
});

//Actualizar un producto

$app->post('/update-producto/:id', function($id) use($db, $app){
    $json = $app->request->post('json');
    $data = json_decode($json,true);
    
    $sql = "UPDATE productos SET ".
            "nombre = '{$data['nombre']}',".
            "descripcion =  '{$data['descripcion']}', ";

        if(isset($data['imagen'])){
            $sql .="imagen = '{$data['imagen']}', ";
        }

    $sql .= "precio = '{$data['precio']}' WHERE id = {$id} ";

    $query = $db->query($sql);
    
    if($query){
        $result = array(
            'status'=> 'success',
            'code' => 200,
            'message' => 'El producto se ha actualizado correctamente'
        );
    }else{
        $result = array(
            'status'=> 'error',
            'code' => 404,
            'message' => 'El producto no se ha actualizado correctamente'
        );
    }
          
    echo json_encode($result);
});

//subir una imagen a un producto

$app->post('/upload-file', function() use($db, $app){
    
    if(isset($_FILES['uploads'])){
        $piramideUploader = new PiramideUploader();
        
        $upload = $piramideUploader->upload('image','uploads','uploads',array('image/jpeg','image/png','image/gif'));
        $file = $piramideUploader->getInfoFile();
        $filename = $file['complete_name'];

        if(isset($upload) && $upload['uploaded'] == false ){
            $result = array(
                'status'=> 'error',
                'code' => 404,
                'message' => 'El archivo no ha podido subirse'
            );        
        }else{
            $result = array(
                'status'=> 'success',
                'code' => 200,
                'message' => 'El archivo se ha subido correctamente',
                'filename' => $filename
            );
        }
    }
    echo json_encode($result);
});

// guardar productos

$app->post('/productos', function() use($app,$db){
    $json = $app->request->post('json');
    $data = json_decode($json,true);
    
    if(!isset($data['nombre'])){
        $data['nombre']= null;
    }

    if(!isset($data['descripcion'])){
        $data['descripcion']= null;
    }

    if(!isset($data['precio'])){
        $data['precio']= null;
    }

    if(!isset($data['imagen'])){
        $data['imagen']= null;
    }

    $query = "INSERT INTO productos VALUES (NULL,".
    "'{$data['nombre']}',".
    "'{$data['descripcion']}',".
    "'{$data['precio']}',".
    "'{$data['imagen']}'".
    ");";

    $insert = $db->query($query);

    $result = array(
        'status'=> 'error',
        'code' => 404,
        'message' => 'El Producto no se ha creado correctamente'
    );

    if($insert){
        $result = array(
            'status'=> 'succes',
            'code' => 200,
            'message' => 'Producto creado correctamente'
        );
    }

    echo json_encode($result);;
    
});
$app->run();