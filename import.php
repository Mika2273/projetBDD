<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$spreadsheet = $reader->load("database-3.xlsx");
$sheet = $spreadsheet->getSheetByName('Clients'); 

include 'dbconfig.php';

$user = [":Id", ":Email", ":Password",":DateAndTime",":TrueOrFalse"];
$user_role = ["UserId", "RoleId"];

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
                    $stmtUser_role->bindValue($user_role[1], 1, PDO::PARAM_INT);
                    break;          
                case "ROLE_ADMIN":
                    $stmtUser_role->bindValue($user_role[1], 2, PDO::PARAM_INT);
                    break;
                case "ROLE_USER,ROLE_ADMIN":
                    $stmtUser_role->bindValue($user_role[1], 1, PDO::PARAM_INT);
                    $stmtUser_role->execute();
                    $stmtUser_role->bindValue($user_role[0], $retainId, PDO::PARAM_STR);
                    $stmtUser_role->bindValue($user_role[1], 2, PDO::PARAM_INT);
            }
        }           

        if ($exlIndex == 5){
            $stmtUser->execute();
            $stmtUser_role->execute();
        }

        $exlIndex +=1;
    }    
}

$sheet = $spreadsheet->getSheetByName('Adresses'); 
$customers = [":CustomerId", ":CompanyName", ":FirstName", ":LastName", ":Phone", "Adresse", "PostalCode", "City", "Country"];
$storage = ["CompanyName", "FirstName", "LastName", "Phone", "Adress", "PostalCode", "City", "Country"];
$storageNext = ["CompanyName", "FirstName", "LastName", "Phone", "Adress", "PostalCode", "City", "Country"];
$AdresseId = 0;

foreach ($sheet->getRowIterator() as $row) {
    $cIndex =0;
    $sIndex =0;
    $exlIndex =0;

    foreach($sheet->getColumnIterator() as $column){
    
        $data = $sheet->getCell($column->getColumnIndex() . $row->getRowIndex())->getValue().PHP_EOL;
        
        // remove line breaks
        $data = str_replace(array("\r","\n"),'',$data);

        switch($exlIndex){
            case 0:
                $stmtCustomers = $pdo->prepare('INSERT INTO customers (Id, CustomerId, CompanyName, FirstName, LastName, Phone, Adresse, PostalCode, City, Country) 
                VALUES (:Id, :CustomerId, :CompanyName, :FirstName, :LastName, :Phone, :Adresse, :PostalCode, :City, :Country)');        
               
                $stmtCustomer_functions = $pdo->prepare('INSERT INTO customer_functions (Id, AdresseId, FunctionId) 
                VALUES (:Id, :AdresseId, :FunctionId)');                 
                break;
            case 1:      
                $stmtCustomers->bindValue($customers[$cIndex], substr($data,9), PDO::PARAM_STR);   
                $cIndex +=1;
                break;          
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:                                
                $storage[$sIndex] = $data;
                $stmtCustomers->bindValue($customers[$cIndex], $data, PDO::PARAM_STR);
                $cIndex ++;
                $sIndex ++;
                break;
            case 10:    
                if ($storage == $storageNext){
                    $stmtCustomer_functions->bindValue(":AdresseId", $AdresseId, PDO::PARAM_INT);
                    if($data == "shipping"){
                        $stmtCustomer_functions->bindValue(":FunctionId", 1, PDO::PARAM_INT);        
                    }else if($data == "billing"){
                        $stmtCustomer_functions->bindValue(":FunctionId", 2, PDO::PARAM_INT);
                    }
                    $stmtCustomer_functions->execute();
                }else{
                    $stmtCustomers->execute();
                    $AdresseId ++;
                    $stmtCustomer_functions->bindValue(":AdresseId", $AdresseId, PDO::PARAM_INT);

                    if($data == "shipping"){
                        $stmtCustomer_functions->bindValue(":FunctionId", 1, PDO::PARAM_INT);       
                    }else if($data == "billing"){
                        $stmtCustomer_functions->bindValue(":FunctionId", 2, PDO::PARAM_INT); 
                    }
                    $stmtCustomer_functions->execute();
                    $cIndex = 0;
                    $sIndex = 0;
                    $storageNext = $storage;
                }
        }
        $exlIndex ++;
    }    
}

/* close connection */
$pdo = null;
?>

