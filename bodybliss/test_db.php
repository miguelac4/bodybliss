<?php
//Only for production
//error_reporting(0);

$servername = "localhost";

$username = "root";

$password = "";

$dbname = "bodybliss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {

    die("Connection Failed" . $conn->connect_error);

}

echo 'success';

//////////////////////////Database querys/////////////////////////////////////

// SELECT (read from database)////////////////////////////////////////
$sql = "SELECT id, email, name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br> id: " . $row["id"]. " <br> Email: " . $row["email"]. " <br> Name: " . $row["name"]. "<br>";
    }
} else {
    echo "0 results";
}
////////////////////////////////////////////////////////////////////
//ADD lines to data base////////////////////////////////////////////
//$email = "john@example.com";
//$name = "John";
//$sql = "INSERT INTO users(email, name) VALUES ('$email', '$name')";
//$result = $conn->query($sql);
//if($result !== true){
//    echo "ERROR TO ADD";
//}
///////////////////////////////////////////////////////////////////
/// //Remove lines of data base////////////////////////////////////////////
$email = "john@example.com";
$sql = "DELETE FROM users WHERE email = '$email'";
$result = $conn->query($sql);
/////////////////////////////////////////////////////////////////////
/// //Update of a data base line////////////////////////////////////////////
//$email_new = "bert@example.com";
//$name_new = "Bert";
//$email_old = "john@example.com";
//$name_old = "John";
//$sql = "UPDATE users SET email = '$email_new', name = '$name_new' WHERE email = '$email_old'";
//$result = $conn->query($sql);
/////////////////////////////////////////////////////////////////////

// FINAL:
$conn->close();
?>



