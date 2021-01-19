<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <!-- Load icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/display.css">
    
</head>
<body>
    <div class="top">
        <form class="search" action="search.php">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        <form class="create" method="get" action="add.php">
             <button type="submit">ADD</button>
        </form>
    </div>
    <div class="clear"></div>

    <div class ="container">
        <h1>Customers</h1>
        <table>
            <tr>
                <th class="colSmall">ID</th>
                <th class="col">Company Name</th>
                <th class="col">First Name</th>
                <th class="col">Last Name</th>
                <th class="col">Action</th>
            </tr>

<?php
    try{
        $pdo = new PDO('sqlite:projetBDD.db');
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
    } catch(Exception $e) {
        echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
        die();
    }
    /* Create a prepared statement */
    $stmt = $pdo -> prepare("SELECT CustomerId, CompanyName, FirstName, LastName from customers");
    
    /* execute the query */
    $stmt -> execute();        
    
    /* fetch all results */
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($customers as $row){
        extract($row);

        echo'
        
        <tr>
            <td>'.$CustomerId.'</td>
            <td>'.$CompanyName.'</td>
            <td>'.$FirstName.'</td>
            <td>'.$LastName.'</td>
            <td><div class="edit">
                    <a href="edit.php?id='.$CustomerId.'">EDIT</a>
                </div>
                <div class="delete">
                    <a href="delete.php?id='.$CustomerId.'">DELETE</a>
                </div>
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