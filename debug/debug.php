<?php
$pdo = new PDO('sqlite:../projetBDD.db');
$AdresseId = 0;
$AdresseId ++;
$stmtcustomer_functions = $pdo->prepare('INSERT INTO customer_functions (Id, AdresseId, FunctionId) 
                VALUES (:Id, :AdresseId, :FunctionId)');  
// $stmtcustomer_functions->bindValue(":Id", 1, PDO::PARAM_INT);
$stmtcustomer_functions->bindValue(":AdresseId", $AdresseId, PDO::PARAM_INT);
$stmtcustomer_functions->bindValue(":FunctionId", 3, PDO::PARAM_INT);
$stmtcustomer_functions->execute();

$pdo = null;



// $pdo = new PDO('sqlite:test.db');
// $stmt = $pdo->prepare('INSERT INTO Fruits (Id, Name, Color, Price) VALUES (:Id, :Name, :Color, :Price)');        
// $stmt->bindValue(":Name", "Cherry", PDO::PARAM_STR);
// $stmt->bindValue(":Color", "Red", PDO::PARAM_STR);
// $stmt->bindValue(":Price", "Red", PDO::PARAM_STR);
// $stmt->execute();

// $pdo = null;