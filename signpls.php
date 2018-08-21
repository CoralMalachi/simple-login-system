<?php
global $mysqli;
global $con;
//set up var
$usr_id = "";
$usr_password = "";
$error_mes = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //need include mysql db port id in the server
    define('DB_SERVER','localhost:3306');
    define('DB_USER','root');
    define('DB_PASS','123123');

    //grab the username and password from the textboxes
    $u_id = $_POST['userID'];
    $usr_password = $_POST['userPass'];

    $database = "dbdb";
    $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
    $db_found = new mysqli(DB_SERVER, DB_USER,DB_PASS,$database);
    if($db_found){
//        echo "db found<br/>";
    }
    if($con){
//        echo "server2 found<br/>";
    }

//    $SQL_u = "SELECT * FROM dbdb WHERE userID='$u_id'";
//    $res_u = mysqli_query($con,$SQL_u);
//    if(mysqli_num_rows($res_u) > 0){
//        print "bla";
//    }
    $SQL = $db_found->prepare("SELECT * FROM dbdb.users WHERE usr_id='$u_id'");

    if(!$SQL){
        echo ($db_found->errno);
        print($db_found->error);
    }
    $SQL->execute();
    $result = $SQL->get_result();
    //if the return value is bigger than 0 then there is already this id
    if(!is_null($result) && $result->num_rows > 0)
    {
        $error_mes = "Username already taken";
    }
    else{
        //user id is aviable - insert to db
        $SQL = $db_found->prepare("INSERT INTO dbdb.users (usr_id,password)"."VALUES ('$u_id','$usr_password')");
        if(!$SQL){
            //echo ($db_found->errno);
            print($db_found->error);
        }
        $SQL->execute();
        header("Location: login.php");

    }


}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>login system</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<br>
<form name="form1" METHOD="post" action="/first_login_system/signpls.php"  class="form-inline">
    <div class="form-group">
        <label for="exampleInputName2">ID</label>
        <input type="text" name="userID" class="form-control" id="exampleInputName2" placeholder="id" value="<?PHP print $usr_id;?>">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail2">Password</label>
        <input type="password" name="userPass" class="form-control" id="exampleInputEmail2" placeholder="********" value="<?PHP print $usr_password;?>">
    </div>

    <button type="submit" class="btn btn-info" >Register</button>
</form>
<?php print  $error_mes; ?>

</body>
</html>