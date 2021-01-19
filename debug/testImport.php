<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$spreadsheet = $reader->load("test.xlsx");
$sheet = $spreadsheet->getSheetByName('Fruits'); 

try{
    $pdo = new PDO('sqlite:test.db');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
} catch(Exception $e) {
    echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
    die();
}

$pdo->query("CREATE TABLE IF NOT EXISTS Fruits
(
    [Id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    [Name] varchar(255)  NOT NULL,
    [Color] varchar(255)  NOT NULL,
    [Price] INTEGER  NOT NULL
);");

$colName = array(":Name", ":Color", ":Price");

foreach ($sheet->getRowIterator() as $row) {
    $colIndex =0;
    foreach($sheet->getColumnIterator() as $column)
    {
        $data = $sheet->getCell($column->getColumnIndex() . $row->getRowIndex())->getValue().PHP_EOL;

        if($colIndex == 0){
            $stmt = $pdo->prepare('INSERT INTO Fruits (Id, Name, Color, Price) VALUES (:Id, :Name, :Color, :Price)');        
        }     
     
        $stmt->bindValue($colName[$colIndex], $data, PDO::PARAM_STR);
       
        if ($colIndex == 2){
            $stmt->execute();

        }    
        $colIndex +=1; 
    }
}
/* close connection */
$pdo = null;
?>

