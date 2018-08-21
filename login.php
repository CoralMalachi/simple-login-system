<?php
$usr_id = "";
$usr_pword = "";
$errorMessage = "";
global $mysqli;

//check to see if the form has been POSTED or not (was the LOGIN button clicked?)
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //need include mysql db port id in the server
    define('DB_SERVER','localhost:3306');
    define('DB_USER','root');
    define('DB_PASS','123123');

    //get the values the user entered
    $usr_id = $_POST['username'];
    $usr_pword = $_POST['password'];

    //connect db and server
    $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);

    if($con)
    {
        //create a prepare statement to selects all the records where the user id match the one provided
        $sql_user = $con->prepare("SELECT * FROM dbdb.users WHERE usr_id = '$usr_id'");
        //check for error
        if(!$sql_user){
            echo ($con->errno);
            print($con->error);
        }
        //execute the sql query
        $sql_user->execute();
        $res_user = $sql_user->get_result();
        if($res_user->num_rows == 1)
        {
            //the fetch_assoc() return the row with the user id provided, as an array
            $usr_row_db = $res_user->fetch_assoc();
            //the password_verify() check if the provided password from the textbox is equal to the correct
            //password from the db
            if(password_verify($usr_pword,$usr_row_db['password']))
            {
                //in order the user can be remembered across web pages, we create a session -
                //we can store values in a session and these values will be available to all pages on the site
                //when the user close the browser the session will end
                session_start();
                //create a variable named login and if the user OK its value is 1
                $_SESSION['login'] = 1;
                //use the header function to redirect the user to the page on our site for members
                header("Location:welcome.php");
            }else{
                $errorMessage = "Password rejected";
                session_start();
                //set login var to a blank string means that the user has not logged on
                //successfully. later we'll check if the var is empty string, and if it does
                //we'll redirect the user to the login page
                $_SESSION['login']='';
            }
        }
        else
        {
            $errorMessage = 'login failed';
        }
    }
}


?>

<html>
<head>
    <title>Basic Login Script</title>
</head>
<body>

<FORM NAME ="form1" METHOD ="POST" ACTION ="login.php">

    Username: <INPUT TYPE = 'TEXT' Name ='username' placeholder="id"  value="<?PHP print $usr_id;?>" maxlength="20">
    Password: <INPUT TYPE = 'TEXT' Name ='password' placeholder="*******" value="<?PHP print $usr_pword;?>" maxlength="16">

    <P align = center>
        <INPUT TYPE = "Submit" Name = "Submit1"  VALUE = "Login">
    </P>

</FORM>

<P>
    <?PHP print $errorMessage;?>




</body>
</html>