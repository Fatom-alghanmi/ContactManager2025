<?php
require_once('database.php');

// Get the contact ID
$contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);

// Select the contact from the database
$query = 'SELECT * FROM contacts WHERE contactID = :contact_id';
$statement = $db->prepare($query);
$statement->bindValue(':contact_id', $contact_id);
$statement->execute();
$contact = $statement->fetch();
$statement->closeCursor();

// Get contact types
$queryTypes = 'SELECT * FROM types';
$statement2 = $db->prepare($queryTypes);
$statement2->execute();
$types = $statement2->fetchAll();
$statement2->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Manager - Update Contact</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>
<main>
    <h2>Update Contact</h2>
    <form action="update_contact.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="contact_id" value="<?php echo $contact['contactID']; ?>" />
        <div id="data">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo $contact['firstName']; ?>" /><br />

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $contact['lastName']; ?>" /><br />

            <label>Email Address:</label>
            <input type="text" name="email_address" value="<?php echo $contact['emailAddress']; ?>" /><br />

            <label>Phone Number:</label>
            <input type="text" name="phone_number" value="<?php echo $contact['phone']; ?>" /><br />

            <label>Status:</label>
            <input type="radio" name="status" value="member" <?php if ($contact['status'] == 'member') echo 'checked'; ?> />Member
            <input type="radio" name="status" value="nonmember" <?php if ($contact['status'] == 'nonmember') echo 'checked'; ?> />Non-Member<br />

            <label>Birth Date:</label>
            <input type="date" name="dob" value="<?php echo $contact['dob']; ?>" /><br />

            <label>Contact Type:</label>
            <select name="type_id">
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo $type['typeID']; ?>" <?php if ($type['typeID'] == $contact['typeID']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($type['contactType']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br />

            <?php if (!empty($contact['imageName'])): ?>
                <label>Current Image:</label>
                <img src="images/<?php echo htmlspecialchars($contact['imageName']); ?>" height="100"><br />
            <?php endif; ?>

            <label>Update Image:</label>
            <input type="file" name="image"><br />
        </div>

        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Update Contact" /><br />
        </div>
    </form>

    <p><a href="index.php">View Contact List</a></p>
</main>
<?php include("footer.php"); ?>
</body>
</html>
