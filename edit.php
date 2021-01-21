<?php 
    $id = $_GET['id'];
    include 'dbconfig.php';
    
    $stmt = $pdo -> prepare("SELECT Id, CustomerId, CompanyName, FirstName, LastName, Phone, Adresse, PostalCode,City, Country from customers WHERE Id = $id");
    $stmt -> execute();        
    $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);  
    $id = $c_f_id =$customer[0]['Id'];
    $costomer_id = $customer[0]['CustomerId'];

    $stmt = $pdo -> prepare("SELECT Email, Password, TrueOrFalse from users WHERE Id = $costomer_id");
    $stmt -> execute();        
    $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo -> prepare("SELECT RoleId from user_roles WHERE UserId = $costomer_id");
    $stmt -> execute();        
    $user_roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo -> prepare("SELECT FunctionId from customer_functions WHERE AdresseId = $id");
    $stmt -> execute();        
    $customer_functions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $error = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'verifyError.php';
    
        if (count($error) === 0){
      
        /*  Update users */
            $sql = "UPDATE users SET Email = :Email, Password = :Password, TrueOrFalse = :TrueOrFalse WHERE Id = :Id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['Id' => $costomer_id,'Email' => $post["Email"], 'Password' => $post["Password"], 'TrueOrFalse' => $post["TrueOrFalse"]]);
            
        /*  Update user_roles */
            $stmt = $pdo -> prepare("SELECT Id, UserId, RoleId FROM user_roles WHERE UserId =$costomer_id");
            $stmt -> execute();  
            $results= $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ((isset($post['user']) && $post['user']=="yes")
             && (isset($post['admin']) && $post['admin']=="yes")){
        
                $role_id = 1;
                $counter = 0;
                foreach($results as $key => $value){
                    $id = $value['Id'];
                    $UserId = $value['UserId'];
                    $sql = "UPDATE user_roles SET UserId = :UserId, RoleId = :RoleId WHERE Id = :Id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['Id' => $id, 'UserId' => $UserId, 'RoleId' => $role_id]);
                    $role_id ++;
                    $counter ++;
                }
                if ($counter == 1){
                    $sql = "INSERT INTO user_roles (UserId, RoleId)
                    VALUES(:UserId, :RoleId)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['UserId' => $UserId, 'RoleId' => 2]);
                }      
    
            }else if (isset($post['user']) && $post['user']=="yes"){
                $role_id = 1;
                foreach($results as $key => $value){
                    $id = $value['Id'];
                    $UserId = $value['UserId'];
                    $sql = "UPDATE user_roles SET UserId = :UserId, RoleId = :RoleId WHERE Id = :Id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['Id' => $id, 'UserId' => $UserId, 'RoleId' => $role_id]);
                }      
            }else{
                $role_id = 2;
                foreach($results as $key => $value){
                    $id = $value['Id'];
                    $UserId = $value['UserId'];
                    $sql = "UPDATE user_roles SET UserId = :UserId, RoleId = :RoleId WHERE Id = :Id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['Id' => $id, 'UserId' => $UserId, 'RoleId' => $role_id]);
                }
            }        
        
        /*  Update customers */
        $sql = "UPDATE customers SET CustomerId = :CustomerId, CompanyName = :CompanyName, FirstName = :FirstName, LastName = :LastName, Phone = :Phone, Adresse = :Adresse, PostalCode = :PostalCode, City = :City, Country = :Country WHERE CustomerId = :CustomerId";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute(['CustomerId' => $costomer_id,'CompanyName' => $post["CompanyName"], 'FirstName' => $post["FirstName"], 'LastName' => $post["LastName"], 'Phone' => $post["Phone"], 'Adresse' => $post["Adresse"], 'PostalCode' => $post["PostalCode"], 'City' => $post["City"], 'Country' => $post["Country"]]);
        
        /*  Update customer_functions */
        $stmt = $pdo -> prepare("SELECT Id, AdresseId, FunctionId FROM customer_functions WHERE AdresseId =$c_f_id");
        $stmt -> execute();  
        $results= $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ((isset($post['shipping']) && $post['shipping']=="yes")
         && (isset($post['billing']) && $post['billing']=="yes")){

            $function_id = 1;
            $counter = 0;
            foreach($results as $key => $value){
                $id = $value['Id'];
                $AdresseId = $value['AdresseId'];
                $sql = "UPDATE customer_functions SET AdresseId = :AdresseId, FunctionId = :FunctionId WHERE Id = :Id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['Id' => $id, 'AdresseId' => $AdresseId, 'FunctionId' => $function_id]);
                $function_id ++;
                $counter ++;
            }
            if ($counter == 1){
                $sql = "INSERT INTO customer_functions (AdresseId, FunctionId)
                VALUES(:AdresseId, :FunctionId)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['AdresseId' => $AdresseId, 'FunctionId' => 2]);
            }            

        }else if (isset($post['shipping']) && $post['shipping']=="yes"){
            
            $function_id = 1;
            foreach($results as $key => $value){
                $id = $value['Id'];
                $AdresseId = $value['AdresseId'];
                $sql = "UPDATE customer_functions SET AdresseId = :AdresseId, FunctionId = :FunctionId WHERE Id = :Id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['Id' => $id, 'AdresseId' => $AdresseId, 'FunctionId' => $function_id]);
            }      
        }else{
            $function_id = 2;
            foreach($results as $key => $value){
                $id = $value['Id'];
                $AdresseId = $value['AdresseId'];
                $sql = "UPDATE customer_functions SET AdresseId = :AdresseId, FunctionId = :FunctionId WHERE Id = :Id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['Id' => $id, 'AdresseId' => $AdresseId, 'FunctionId' => $function_id]);
            }
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
	<title>Edit</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>
    <div class="container">
        <h1>Edit a record</h1>
        <p>* Required fields</p>

        <form method="POST">
            <div class="flex">
                <p class="flex-child">
                    <label for="FirstName">* First Name:</label>
                    <input type="text" class="input-box" name="FirstName" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['FirstName'];
                    }else{
                        echo htmlspecialchars($post['FirstName']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['FirstName'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="LastName">* Last Name:</label>
                    <input type="text" class="input-box" name="LastName" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['LastName'];
                    }else{
                        echo htmlspecialchars($post['LastName']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['LastName'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="CompanyName">Company Name:</label>
                    <input type="text" class="input-box" name="CompanyName" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['CompanyName'];
                    }else{
                        echo htmlspecialchars($post['CompanyName']);}
                    ?>"> 
                </p>
            </div>
            <div class="flex">
	            <p class="flex-child">
                    <label for="Email">* Email:</label>
                    <input type="text" class="input-box" name="Email" value="<?php 
                    if (count($error) === 0){
                        echo $user[0]['Email'];
                    }else{
                        echo htmlspecialchars($post['Email']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['Email'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="Password">* Password:</label>
                    <input type="password" class="input-box" name="Password" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['Password'];
                    }else{
                        echo htmlspecialchars($post['Password']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['Password'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="Phone">* Phone:</label>
                    <input type="text" class="input-box" name="Phone" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['Phone'];
                    }else{
                        echo htmlspecialchars($post['Phone']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['Phone'];?></span>
                    <br><br>
                </p>
            </div>
	        <div class="flex">
                <p class="flex-child">
                    <label for="Adresse">* Adresse:</label>
                    <input type="text" class="input-box" id ="Adresse" name="Adresse" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['Adresse'];
                    }else{
                        echo htmlspecialchars($post['Adresse']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['Adresse'];?></span>
                    <br><br>
                </p>
            </div>
            <div class="flex">
                <p class="flex-child">
                    <label for="PostalCode">* Postal Code:</label>
                    <input type="text" class="input-box" name="PostalCode" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['PostalCode'];
                    }else{
                        echo htmlspecialchars($post['PostalCode']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['PostalCode'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="City">* City:</label>
                    <input type="text" class="input-box" name="City" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['City'];
                    }else{
                        echo htmlspecialchars($post['City']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['City'];?></span>
                    <br><br>
                </p>
                <p class="flex-child">
                    <label for="Country">* Country:</label>
                    <input type="text" class="input-box" name="Country" value="<?php 
                    if (count($error) === 0){
                        echo $customer[0]['Country'];
                    }else{
                        echo htmlspecialchars($post['Country']);}
                    ?>"><br>
                    <span class="error"><?php echo $error['Country'];?></span>
                    <br><br>
                </p>
            </div>
            <div class="flex">
                <p class="flex-child-columnS">
                    <span>*</span>
                    <input type="radio" id="true" name="TrueOrFalse"  <?php
                    if(count($error) === 0){
                        if($user[0]['TrueOrFalse']=="true"){
                            echo "checked";
                        }       
                    }else if($post["TrueOrFalse"]=="true"){
                            echo "checked";
                    }
                    ?> value="true">
                    <label for="true">True</label>
                    <input type="radio" id="false" name="TrueOrFalse" <?php 
                    if(count($error) === 0){
                        if($user[0]['TrueOrFalse']=="false"){
                            echo "checked";
                        }    
                    }else if($post["TrueOrFalse"]=="false"){
                            echo "checked";
                    }
                    ?> value="false">
                    <label for="false">False</label><br>
                    <span class="error"><?php echo $error['TrueOrFalse'];?></span>
                    <br><br>
                </p>	
                <p class="flex-child-columnS">
                    <span>*</span>
                    <input type="checkbox" id="user" name="user"  <?php
                    if(count($error) === 0){
                        if ($user_roles[0]['RoleId']== 1 || $user_roles[1]['RoleId']== 1 ){
                            echo "checked";
                        }
                    }else if($post["user"]=="yes"){
                        echo "checked";
                    }    
                    ?>  value="yes">
                    <label for="user">User</label>
                    <input type="checkbox" id="admin" name="admin" <?php 
                    if(count($error) === 0){
                        if (($user_roles[0]['RoleId']== 2) || $user_roles[1]['RoleId']== 2 ){
                           echo "checked";
                        }
                    }else if($post["admin"]=="yes"){
                        echo "checked";
                    }    
                    ?>  value="yes">
                    <label for="admin"> Admin</label><br>
                    <span class="error"><?php echo $error['user'];?></span>
                </p>
                <p class="flex-child-columnS">
                    <span>*</span>
                    <input type="checkbox" id="shipping" name="shipping"  <?php 
                    if(count($error) === 0){
                        if ($customer_functions[0]['FunctionId']== 1 || $customer_functions[1]['FunctionId']== 1 ){
                            echo "checked";
                        }
                    }else if($post["shipping"]=="yes"){
                        echo "checked";
                    }    
                    ?>  value="yes">
                    <label for="shipping"> Shipping</label>
                    <input type="checkbox" id="billing" name="billing" <?php 
                    if(count($error) === 0){
                        if ($customer_functions[0]['FunctionId']== 2 || $customer_functions[1]['FunctionId']== 2 ){
                        echo "checked";    
                        }
                    }else if($post["billing"]=="yes"){
                        echo "checked";
                    } 
                    ?>  value="yes">
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