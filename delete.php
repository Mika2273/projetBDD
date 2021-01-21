<?php 
    $id = $_GET['id'];
    include 'dbconfig.php';
    
    $stmt = $pdo -> prepare("SELECT Id, CustomerId from customers WHERE Id = $id");
    $stmt -> execute();        
    $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $id =$customer[0]['Id'];
    $costomer_id = $customer[0]['CustomerId'];

    $stmt = $pdo -> prepare("DELETE from customers WHERE Id = $id");
    $stmt -> execute(); 
    
    $stmt = $pdo -> prepare("DELETE from customer_functions WHERE AdresseId = $id");
    $stmt -> execute(); 
    
    $stmt = $pdo -> prepare("DELETE from users WHERE Id = $costomer_id");
    $stmt -> execute(); 

    $stmt = $pdo -> prepare("DELETE from user_roles WHERE UserId = $costomer_id");
    $stmt -> execute(); 

    
    header('Location: /display.php'); 
    exit();   