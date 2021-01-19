<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="test.css">
</head>
<body>
    <div class ="container">
        <h1>Fruits</h1>
        <table>
            <tr>
                <th class="col">Name</th>
                <th class="col">Color</th>
                <th class="col">Price</th>
                <th class="colSmall">Action</th>
            </tr>

<?php
    try{
        $pdo = new PDO('sqlite:test.db');
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
    } catch(Exception $e) {
        echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
        die();
    }
    /* Create a prepared statement */
    $stmt = $pdo -> prepare("SELECT * from Fruits");
    
    /* execute the query */
    $stmt -> execute();        
    
    /* fetch all results */
    $fruits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($fruits as $row){
        extract($row);

        echo'
        
        <tr>
            <td>'.$Name.'</td>
            <td>'.$Color.'</td>
            <td>'.$Price.'</td>
            <td><form class="buttons" method="get" action="testEdit.php">
                    <button type="submit">EDIT</button>
                </form>
                <form class="buttons" method="get" action="testDelete.php">
                    <button type="submit">DELETE</button>
                </form>
            </td>
        </tr>';
    }
            
    /* close connection */
    $pdo = null;
?> 
        </table>
       </div>
    </body>
</html>    
