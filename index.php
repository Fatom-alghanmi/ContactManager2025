
<?php
    session_start();
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
                </tr>

                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo $contact['firstName']; ?></td>
                        <td><?php echo $contact['lastName']; ?></td>
                        <td><?php echo $contact['emailAddress']; ?></td>
                        <td><?php echo $contact['phone']; ?></td>
                        <td><?php echo $contact['status']; ?></td>
                        <td><?php echo $contact['dob']; ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>
            <p><a href="add_contact_form.php">Add Contact</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>
