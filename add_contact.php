<?php
    session_start();

    require_once 'image_util.php'; // the process_image function

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    if (isset($_FILES['file1'])) {
        $filename = $_FILES['file1']['name'];

        if (!empty($filename)) {
            $source = $_FILES['file1']['tmp_name'];
            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

            move_uploaded_file($source, $target);

            // create the '400' and '100' versions of the image
            process_image($image_dir_path, $filename);
        }
    }

    // get data from the form
    $first_name = filter_input(INPUT_POST, 'first_name');
    $last_name = filter_input(INPUT_POST, 'last_name');
    $email_address = filter_input(INPUT_POST, 'email_address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');
    $status = filter_input(INPUT_POST, 'status');
    $dob = filter_input(INPUT_POST, 'dob');
    $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

    $file_name = $_FILES['file1']['name'];

    $i = strrpos($file_name, '.');
    $image_name = substr($file_name, 0, $i);
    $ext = substr($file_name, $i);
    $image_name_100 = $image_name . '_100' . $ext;

    require_once('database.php');
    $queryContacts = 'SELECT * FROM contacts';
    $statement1 = $db->prepare($queryContacts);
    $statement1->execute();
    $contacts = $statement1->fetchAll();
    $statement1->closeCursor();

    foreach ($contacts as $contact) {
        if ($email_address == $contact["emailAddress"]) {
            $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
            header("Location: error.php");
            die();
        }
    }

    if ($first_name == null || $last_name == null || $email_address == null ||
        $phone_number == null || $status == null || $dob == null || $type_id === false) {
        $_SESSION["add_error"] = "Invalid contact data. Check all fields and try again.";
        header("Location: error.php");
        die();
    } else {
        // Add the contact to the database
        $query = 'INSERT INTO contacts
            (firstName, lastName, emailAddress, phone, status, dob, imageName, typeID)
            VALUES
            (:firstName, :lastName, :emailAddress, :phone, :status, :dob, :imageName, :typeID)';

        $statement = $db->prepare($query);
        $statement->bindValue(':firstName', $first_name);
        $statement->bindValue(':lastName', $last_name);
        $statement->bindValue(':emailAddress', $email_address);
        $statement->bindValue(':phone', $phone_number);
        $statement->bindValue(':status', $status);
        $statement->bindValue(':dob', $dob);
        $statement->bindValue(':imageName', $image_name_100);
        $statement->bindValue(':typeID', $type_id);
        $statement->execute();
        $statement->closeCursor();
    }

    $_SESSION["fullName"] = $first_name . " " . $last_name;

    // redirect to confirmation page
    header("Location: confirmation.php");
    die();
?>