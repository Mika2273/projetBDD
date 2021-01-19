<?php

$error = [];
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
     
    if (empty($post["FirstName"])) {
        $error["FirstName"] = "First Name is required";
    }
    if (empty($post["LastName"])) {
        $error["LastName"]  = "Last Name is required";
    }
    if (empty($post["Email"])) {
        $error["Email"] = "Email is required";
    } else if (!filter_var($post["Email"], FILTER_VALIDATE_EMAIL)){
        $error["Email"] = "Invalid email format";
    }
    if (empty($post["Password"])) {
        $error["Password"] = "Password is required";
    } 
    if (empty($post["user"])&& empty($post["admin"])) {
        $error["user"] = "Role is required";
    }    
    if (empty($post["TrueOrFalse"])) {
        $error["TrueOrFalse"] = "Required field";
    }
    if (empty($post["Phone"])) {
        $error["Phone"] = "Phone is required";
    } 
    if (empty($post["Adresse"])) {
        $error["Adresse"] = "Adresse is required";
    }
    if (empty($post["PostalCode"])) {
        $error["PostalCode"] = "Postal Code is required";
    } 
    if (empty($post["City"])) {
        $error["City"] = "City is required";
    }
    if (empty($post["Country"])) {
        $error["Country"] = "Country is required";
    } 
    if (empty($post["shipping"])&& empty($post["billing"])) {
        $error["shipping"] = "Function is required";
    }                                                
    if (count($error) === 0){
    
        $DateAndTime = date("Y-m-d H:i:s");

        include 'dbconfig.php';
    
        /*  get the last row of customers */
        $stmt = $pdo -> prepare("SELECT MAX(CustomerId) FROM customers");
        $stmt -> execute();  

        $result= $stmt->fetch(PDO::FETCH_OBJ);
        foreach($result as $key => $value){
            $CustomerId = $value;
        }
        $CustomerId ++;

        
     
    /*  Insert new row to users */
        $sql = "INSERT INTO users (Email, Password,DateAndTime,TrueOrFalse)
        VALUES(:Email, :Password, :DateAndTime, :TrueOrFalse)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['Email' => $post["Email"], 'Password' => $post["Password"], 'DateAndTime' => $DateAndTime, 'TrueOrFalse' => $post["TrueOrFalse"]]);
    
    /*  Insert new rows to user_roles */
        $sql = "INSERT INTO users_roles (UserId, RoleId)
        VALUES(:UserId, :RoleId)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['UserId' => $UserId, 'RoleId' => $RoleId]);
    
    /*  Insert new row to customers */
        $sql = "INSERT INTO customers (CustomerId, CompanyName,FirstName,LastName,Phone, Adresse, PostalCode,City,Country)
        VALUES(:CustomerId, :CompanyName, :FirstName, :LastName, :Phone, :Adresse, :PostalCode, :City, :Country)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['CustomerId' => $CustomerId, 'CompanyName' => $post["CompanyName"], 'FirstName' => $post["FirstName"], 'LastName' => $post["LastName"], 'Phone' => $post["Phone"], 'Adresse' => $post["Adresse"], 'PostalCode' => $post["PostalCode"], 'City' => $post["City"], 'Country' => $post["Country"]]);
    
    
        header('Location: /display.php'); 
        exit();
    }    
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>add.php</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>

<form method="POST">
	<a href="display.php">Back</a>
	<p>
        <label for="FirstName">First Name:</label>
        <input type="text" id="FirstName" name="FirstName" value="<?php echo htmlspecialchars($post['FirstName']); ?>">
        <span class="error">* <?php echo $FirstNameErr;?></span>
        <br><br>
	</p>
	<p>
        <label for="LastName">Last Name:</label>
        <input type="text" id="LastName" name="LastName" value="<?php echo htmlspecialchars($post['LastName']); ?>">
        <span class="error">* <?php echo $error['LastName'];?></span>
        <br><br>
	</p>
	<p>
        <label for="CompanyName">Company Name:</label>
        <input type="text" id="CompanyName" name="CompanyName" value="<?php echo htmlspecialchars($post['CompanyName']); ?>"> 
	</p>
	<p>
        <label for="Email">Email:</label>
        <input type="text" id="Email" name="Email" value="<?php echo htmlspecialchars($post['Email']); ?>">
        <span class="error">* <?php echo $error['Email'];?></span>
        <br><br>
	</p>
    <p>
        <label for="Password">Password:</label>
        <input type="password" id="Password" name="Password" value="<?php echo htmlspecialchars($post['Password']); ?>">
        <span class="error">* <?php echo $error['Password'];?></span>
        <br><br>
    </p>
    <p>
        <input type="checkbox" id="user" name="user"  <?php if (isset($post['user']) && $post['user']=="yes") echo "checked";?>  value="yes">
        <label for="user"> USER</label><br>
        <input type="checkbox" id="admin" name="admin" <?php if (isset($post['admin']) && $post['admin']=="yes") echo "checked";?>  value="yes">
        <label for="admin"> ADMIN</label><br>
        <span class="error">* <?php echo $error['user'];?></span>
    </p>	
    <p>
        <input type="radio" id="true" name="TrueOrFalse"  <?php if (isset($post['TrueOrFalse']) && $post['TrueOrFalse']=="true") echo "checked";?> value="true">
        <label for="true">True</label><br>
        <input type="radio" id="false" name="TrueOrFalse" <?php if (isset($post['TrueOrFalse']) && $post['TrueOrFalse']=="false") echo "checked";?> value="false">
	    <label for="false">False</label><br>
        <span class="error">* <?php echo $error['TrueOrFalse'];?></span>
  <br><br>
    </p>	
    <p>
        <label for="Phone">Phone:</label>
        <input type="text" id="Phone" name="Phone" value="<?php echo htmlspecialchars($post['Phone']); ?>">
        <span class="error">* <?php echo $error['Phone'];?></span>
        <br><br>
	</p>
    <p>
        <label for="Adresse">Adresse:</label>
        <input type="text" id="Adresse" name="Adresse" value="<?php echo htmlspecialchars($post['Adresse']); ?>">
        <span class="error">* <?php echo $error['Adresse'];?></span>
        <br><br>
	</p>
    <p>
        <label for="PostalCode">Postal Code:</label>
        <input type="text" id="PostalCode" name="PostalCode" value="<?php echo htmlspecialchars($post['PostalCode']); ?>">
        <span class="error">* <?php echo $error['PostalCode'];?></span>
        <br><br>
    </p>
    <p>
        <label for="City">City:</label>
        <input type="text" id="City" name="City" value="<?php echo htmlspecialchars($post['City']); ?>">
        <span class="error">* <?php echo $error['City'];?></span>
        <br><br>
    </p>
    <p>
        <label for="Country">Country:</label>
        <input type="text" id="Country" name="Country" value="<?php echo htmlspecialchars($post['Country']); ?>">
        <span class="error">* <?php echo $error['Country'];?></span>
        <br><br>
    </p>
    <p>
        <input type="checkbox" id="shipping" name="shipping"  <?php if (isset($post['shipping']) && $post['shipping']=="yes") echo "checked";?>  value="yes">
        <label for="shipping"> shipping</label><br>
        <input type="checkbox" id="billing" name="billing" <?php if (isset($post['billing']) && $post['billing']=="yes") echo "checked";?>  value="yes">
        <label for="billing"> billing</label><br>
        <span class="error">* <?php echo $error['shipping'];?></span>
    </p>
    <input type="submit" name="submit" value="Save">
</form>
</body>
</html>