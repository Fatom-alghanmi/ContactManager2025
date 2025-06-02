<?php
    session_start();


    // get data from the form
    $user_name = filter_input(INPUT_POST, 'user_name');
    $password = filter_input(INPUT_POST, 'password');
   
    $_SESSION["pass"] = $password; // Store password in session for later use

    

    require_once('database.php');

    $query = 'SELECT  password  FROM registrations
                WHERE userName = :user_name';
    $statement1 = $db->prepare($query);
    $statement1->bindValue(':user_name', $user_name);
   
    $statement1->execute();
    $row = $statement1->fetch();
   

    $statement1->closeCursor();

   $hash = $row['password'];

   $_SESSION['isLoggedIn'] = password_verify($_SESSION["pass"], $hash);

    if ($_SESSION['isLoggedIn'] == True) 
    {
        $_SESSION["user_name"] = $user_name; // Store username in session
        $_SESSION['password'] = $password; // Store password in session
        $_SESSION['hash'] = $hash; // Store hash in session

        $url = 'index.php';
        header("Location: " . $url);
        die();
    }
     elseif ($_SESSION['isLoggedIn'] == FALSE)  
     {
        $_SESSION = []; // clear all session data
        session_destroy(); // clean up the session ID

        $url = "login_form.php";
     } 
     else 
     {
        $_SESSION = []; // clear all session data
        session_destroy(); // clean up the session ID

        $url = "login_form.php";
     }

?>