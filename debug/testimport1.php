<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$spreadsheet = $reader->load("database-8.xlsx");
$sheet = $spreadsheet->getSheetByName('Clients'); 

try{
    $pdo = new PDO('sqlite:projetBDD.db');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
} catch(Exception $e) {
    echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
    die();
}

$user = array(":Id", ":Email", ":Password",":DateAndTime",":TrueOrFalse");
$user_role = array("UserId", "RoleId");

foreach ($sheet->getRowIterator() as $row) {
    $userIndex =0;
    $exlIndex =0;
    
    foreach($sheet->getColumnIterator() as $column){
    
        $data = $sheet->getCell($column->getColumnIndex() . $row->getRowIndex())->getValue().PHP_EOL;
        
        // remove line breaks
        $data = str_replace(array("\r","\n"),'',$data);

        if($exlIndex !==3){
            if($exlIndex == 0){
                $stmtUser = $pdo->prepare('INSERT INTO users (Id, Email, Password, DateAndTime, TrueOrFalse) VALUES (:Id, :Email, :Password, :DateAndTime, :TrueOrFalse )');        
                $stmtUser_role = $pdo->prepare('INSERT INTO user_roles (Id, UserId, RoleId) VALUES (:Id, :UserId, :RoleId)');                
           
                $stmtUser_role->bindValue($user_role[0], $data, PDO::PARAM_STR);
                $retainId = $data;      
            }     
            $stmtUser->bindValue($user[$userIndex], $data, PDO::PARAM_STR);          
            $userIndex +=1;
        }else{
            switch($data){
                case "ROLE_USER":
                    $stmtUser_role->bindValue($user_role[1], 1, PDO::PARAM_STR);
                    break;          
                case "ROLE_ADMIN":
                    $stmtUser_role->bindValue($user_role[1], 2, PDO::PARAM_STR);
                    break;
                case "ROLE_USER,ROLE_ADMIN":
                    $stmtUser_role->bindValue($user_role[1], 1, PDO::PARAM_STR);
                    $stmtUser_role->execute();
                    $stmtUser_role->bindValue($user_role[0], $retainId, PDO::PARAM_STR);
                    $stmtUser_role->bindValue($user_role[1], 2, PDO::PARAM_STR);
            }
        }           

        if ($exlIndex == 5){
            $stmtUser->execute();
            $stmtUser_role->execute();
        }

        $exlIndex +=1;
    }    
}
/* close connection */
$pdo = null;
?>

