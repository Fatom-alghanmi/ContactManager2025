<!DOCTYPE html>
<html>
    <head>
        <title>Contact Manager - Register</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Register</h2>

            <form action="register_contact.php" method="post" id="register_contact_form"
                enctype="multipart/form-data">

                <div id="data">

                    <label>Username:</label>
                    <input type="text" name="user_name" /><br />

                    <label>Password:</label>
                    <input type="password" name="password" /><br />

                </div>

                <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Register" /><br />

                </div>

            </form>
            
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>