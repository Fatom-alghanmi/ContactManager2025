<?php
    session_start();

    if (!isset($_SESSION["isLoggedIn"])) 
     {
        // User is not logged in, redirect to login page
        $url = "login_form.php";
        header("Location: " . $url);
        die();
    }

    require("database.php");
    $queryContacts = 'SELECT * FROM contacts';
    $statement1 = $db->prepare($queryContacts);
    $statement1->execute();
    $contacts = $statement1->fetchAll();

    $statement1->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Contact Manager - Home</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Contact List</h2>

            <table>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Birth Date</th>
                    <th>Photo</th>
                    <th>&nbsp;</th> <!-- for edit button -->
                    <th>&nbsp;</th> <!-- for delete button -->
                </tr>

                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo $contact['firstName']; ?></td>
                        <td><?php echo $contact['lastName']; ?></td>
                        <td><?php echo $contact['emailAddress']; ?></td>
                        <td><?php echo $contact['phone']; ?></td>
                        <td><?php echo $contact['status']; ?></td>
                        <td><?php echo $contact['dob']; ?></td>
                        <td><img src="<?php echo htmlspecialchars('./images/' . $contact['imageName']); ?>" alt="<?php echo htmlspecialchars('./images/' . $contact['imageName']); ?>"  /></td>
                        <td>
                            <form action="update_contact_form.php" method="post">
                                <input type="hidden" name="contact_id"
                                    value="<?php echo $contact['contactID']; ?>" />
                                <input type="submit" value="Update" />
                            </form>
                        </td> <!-- for edit button -->
                        <td>
                            <form action="delete_contact.php" method="post">
                                <input type="hidden" name="contact_id"
                                    value="<?php echo $contact['contactID']; ?>" />
                                <input type="submit" value="Delete" />
                            </form>
                        </td> <!-- for delete button -->
                    </tr>
                <?php endforeach; ?>

            </table>
            <p><a href="add_contact_form.php">Add Contact</a></p>
            <p><a href="logout.php">Logout</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>