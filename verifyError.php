<?php  

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
?>