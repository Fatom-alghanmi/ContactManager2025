<?php
session_start();

$contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
$first_name = filter_input(INPUT_POST, 'first_name');
$last_name = filter_input(INPUT_POST, 'last_name');
$email_address = filter_input(INPUT_POST, 'email_address');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$status = filter_input(INPUT_POST, 'status');
$dob = filter_input(INPUT_POST, 'dob');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['image'];

require_once('database.php');

// Check for duplicate email
$queryContacts = 'SELECT * FROM contacts';
$statement1 = $db->prepare($queryContacts);
$statement1->execute();
$contacts = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($contacts as $contact) {
    if ($email_address === $contact["emailAddress"] && $contact_id !== $contact["contactID"]) {
        $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
        header("Location: error.php");
        die();
    }
}

if ($first_name === null || $last_name === null || $email_address === null || 
    $phone_number === null || $dob === null || $type_id === null) {
    $_SESSION["add_error"] = "Invalid contact data, Check all fields and try again.";
    header("Location: error.php");
    die();
}

require_once('image_util.php');

// Get current image name from database
$query = 'SELECT imageName FROM contacts WHERE contactID = :contactID';
$statement = $db->prepare($query);
$statement->bindValue(':contactID', $contact_id);
$statement->execute();
$current = $statement->fetch();
$current_image_name = $current['imageName'];
$statement->closeCursor();

$image_name = $current_image_name;

if ($image && $image['error'] === UPLOAD_ERR_OK) {
    // Delete old image files if they exist
    $base_dir = 'images/';
    if ($current_image_name) {
        $dot = strrpos($current_image_name, '_100.');
        if ($dot !== false) {
            $original_name = substr($current_image_name, 0, $dot) . substr($current_image_name, $dot + 4);
            $original = $base_dir . $original_name;
            $img_100 = $base_dir . $current_image_name;
            $img_400 = $base_dir . substr($current_image_name, 0, $dot) . '_400' . substr($current_image_name, $dot + 4);

            if (file_exists($original)) unlink($original);
            if (file_exists($img_100)) unlink($img_100);
            if (file_exists($img_400)) unlink($img_400);
        }
    }

    // Upload and process new image
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    // Save new _100 filename for database
    $dot_position = strrpos($original_filename, '.');
    $name_without_ext = substr($original_filename, 0, $dot_position);
    $extension = substr($original_filename, $dot_position);
    $image_name = $name_without_ext . '_100' . $extension;
}

// Update contact
$query = 'UPDATE contacts
    SET firstName = :firstName,
        lastName = :lastName,
        emailAddress = :emailAddress,
        phone = :phone,
        status = :status,
        dob = :dob,
        typeID = :typeID,
        imageName = :imageName
    WHERE contactID = :contactID';

$statement = $db->prepare($query);
$statement->bindValue(':firstName', $first_name);
$statement->bindValue(':lastName', $last_name);
$statement->bindValue(':emailAddress', $email_address);
$statement->bindValue(':phone', $phone_number);
$statement->bindValue(':status', $status);
$statement->bindValue(':dob', $dob);
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':contactID', $contact_id);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $first_name . " " . $last_name;
header("Location: update_confirmation.php");
die();
