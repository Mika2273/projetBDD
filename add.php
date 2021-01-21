<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = [];
    include 'verifyError.php';

    if (count($error) === 0){
    
        include 'dbconfig.php';
    
    /*  Insert new row to users */
        $sql = "INSERT INTO users (Email, Password,DateAndTime,TrueOrFalse)
                VALUES(:Email, :Password, :DateAndTime, :TrueOrFalse)";
        $stmt = $pdo->prepare($sql);
        $DateAndTime = date("Y-m-d H:i:s");
        $stmt->execute(['Email' => $post["Email"], 'Password' => $post["Password"], 'DateAndTime' => $DateAndTime, 'TrueOrFalse' => $post["TrueOrFalse"]]);
        
    /*  Insert new row to user_roles */
        if (isset($post['user']) && $post['user']=="yes"){

            $stmt = $pdo -> prepare("SELECT MAX(Id) FROM users");
            $stmt -> execute();  
    
            $result= $stmt->fetch(PDO::FETCH_OBJ);
            
            foreach($result as $key => $value){
                $UserId = $value;
            }

            $sql = "INSERT INTO user_roles (UserId, RoleId)
            VALUES(:UserId, :RoleId)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['UserId' => $UserId, 'RoleId' => 1]);

        }

        if (isset($post['admin']) && $post['admin']=="yes"){

            $stmt = $pdo -> prepare("SELECT MAX(Id) FROM users");
            $stmt -> execute();  
    
            $result= $stmt->fetch(PDO::FETCH_OBJ);
            foreach($result as $key => $value){
                $UserId = $value;
            }

            $sql = "INSERT INTO user_roles (UserId, RoleId)
            VALUES(:UserId, :RoleId)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['UserId' => $UserId, 'RoleId' => 2]);

        }
    
    
    /*  Insert new row to customers */
        $stmt = $pdo -> prepare("SELECT MAX(CustomerId) FROM customers");
        $stmt -> execute();  

        $result= $stmt->fetch(PDO::FETCH_OBJ);
        foreach($result as $key => $value){
            $CustomerId = $value;
        }
        $CustomerId ++;
    
        $sql = "INSERT INTO customers (CustomerId, CompanyName,FirstName,LastName,Phone, Adresse, PostalCode,City,Country)
        VALUES(:CustomerId, :CompanyName, :FirstName, :LastName, :Phone, :Adresse, :PostalCode, :City, :Country)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['CustomerId' => $CustomerId, 'CompanyName' => $post["CompanyName"], 'FirstName' => $post["FirstName"], 'LastName' => $post["LastName"], 'Phone' => $post["Phone"], 'Adresse' => $post["Adresse"], 'PostalCode' => $post["PostalCode"], 'City' => $post["City"], 'Country' => $post["Country"]]);
    
    /*  Insert new row to customer_functions */
        if (isset($post['shipping']) && $post['shipping']=="yes"){

            $stmt = $pdo -> prepare("SELECT MAX(Id) FROM customers");
            $stmt -> execute();  
    
            $result= $stmt->fetch(PDO::FETCH_OBJ);
            foreach($result as $key => $value){
                $AdresseId = $value;
            }

            $sql = "INSERT INTO customer_functions(AdresseId, FunctionId)
            VALUES(:AdresseId, :FunctionId)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['AdresseId' => $AdresseId, 'FunctionId' => 1]);

        }

        if (isset($post['billing']) && $post['billing']=="yes"){

            $stmt = $pdo -> prepare("SELECT MAX(Id) FROM customers");
            $stmt -> execute();  
    
            $result= $stmt->fetch(PDO::FETCH_OBJ);
            foreach($result as $key => $value){
                $AdresseId = $value;
            }

            $sql = "INSERT INTO customer_functions(AdresseId, FunctionId)
            VALUES(:AdresseId, :FunctionId)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['AdresseId' => $AdresseId, 'FunctionId' => 2]);
        } 
    
        header('Location: /display.php'); 
        exit();
    }    
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>
    <div class="container">
        <h1>Create a new record</h1>
        <p>* Required fields</p>
        <form method="POST">
            <div class="flex">
                <p class="flex-child">
                    <label for="FirstName">* First Name:</label>
                    <input type="text" class="input-box" name="FirstName" value="<?php echo htmlspecialchars($post['FirstName']); ?>"><br>
                    <span class="error"><?php echo $error['FirstName'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="LastName">* Last Name:</label>
                    <input type="text" class="input-box" name="LastName" value="<?php echo htmlspecialchars($post['LastName']); ?>"><br>
                    <span class="error"><?php echo $error['LastName'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="CompanyName">Company Name:</label>
                    <input type="text" class="input-box" name="CompanyName" value="<?php echo htmlspecialchars($post['CompanyName']); ?>"> 
                </p>
            </div>
            <div class="flex">
	            <p class="flex-child">
                    <label for="Email">* Email:</label>
                    <input type="text" class="input-box" name="Email" value="<?php echo htmlspecialchars($post['Email']); ?>"><br>
                    <span class="error"><?php echo $error['Email'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="Password">* Password:</label>
                    <input type="password" class="input-box" name="Password" value="<?php echo htmlspecialchars($post['Password']); ?>"><br>
                    <span class="error"><?php echo $error['Password'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="Phone">* Phone:</label>
                    <input type="text" class="input-box" name="Phone" value="<?php echo htmlspecialchars($post['Phone']); ?>"><br>
                    <span class="error"><?php echo $error['Phone'];?></span>
                    <br><br>
                </p>
            </div>
	        <div class="flex">
                <p class="flex-child">
                    <label for="Adresse">* Adresse:</label>
                    <input type="text" class="input-box" id ="Adresse" name="Adresse" value="<?php echo htmlspecialchars($post['Adresse']); ?>"><br>
                    <span class="error"><?php echo $error['Adresse'];?></span>
                    <br><br>
                </p>
            </div>
            <div class="flex">
                <p class="flex-child">
                    <label for="PostalCode">* Postal Code:</label>
                    <input type="text" class="input-box" name="PostalCode" value="<?php echo htmlspecialchars($post['PostalCode']); ?>"><br>
                    <span class="error"><?php echo $error['PostalCode'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="City">* City:</label>
                    <input type="text" class="input-box" name="City" value="<?php echo htmlspecialchars($post['City']); ?>"><br>
                    <span class="error"><?php echo $error['City'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="Country">* Country:</label>
                    <input type="text" class="input-box" name="Country" value="<?php echo htmlspecialchars($post['Country']); ?>"><br>
                    <span class="error"><?php echo $error['Country'];?></span>
                    <br><br>
                </p>
            </div>
            <div class="flex">
                <p class="flex-child-columnS">
                    <span>*</span>
                    <input type="radio" id="true" name="TrueOrFalse"  <?php if (isset($post['TrueOrFalse']) && $post['TrueOrFalse']=="true") echo "checked";?> value="true">
                    <label for="true">True</label>
                    <input type="radio" id="false" name="TrueOrFalse" <?php if (isset($post['TrueOrFalse']) && $post['TrueOrFalse']=="false") echo "checked";?> value="false">
                    <label for="false">False</label><br>
                    <span class="error"><?php echo $error['TrueOrFalse'];?></span>
                    <br><br>
                </p>	
                <p class="flex-child-columnS">
                    <span>*</span>
                    <input type="checkbox" id="user" name="user"  <?php if (isset($post['user']) && $post['user']=="yes") echo "checked";?>  value="yes">
                    <label for="user">User</label>
                    <input type="checkbox" id="admin" name="admin" <?php if (isset($post['admin']) && $post['admin']=="yes") echo "checked";?>  value="yes">
                    <label for="admin"> Admin</label><br>
                    <span class="error"><?php echo $error['user'];?></span>
                </p>
                <p class="flex-child-columnS">
                    <span>*</span>
                    <input type="checkbox" id="shipping" name="shipping"  <?php if (isset($post['shipping']) && $post['shipping']=="yes") echo "checked";?>  value="yes">
                    <label for="shipping"> Shipping</label>
                    <input type="checkbox" id="billing" name="billing" <?php if (isset($post['billing']) && $post['billing']=="yes") echo "checked";?>  value="yes">
                    <label for="billing"> Billing</label><br>
                    <span class="error"><?php echo $error['shipping'];?></span>
                </p>
            </div>
            <input class="button orange" type="submit" name="submit" value="Save">
            <a class= "button blue" href="display.php">Back</a>
        </form>
    </div>
</body>
</html>