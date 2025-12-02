<?php
session_start();
header('Content-Type: application/json');
require_once "includes/db_connect.php";

// Check for required input
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'No action specified.']);
    exit;
}

$action = $_POST['action'];
$response = ['success' => false, 'message' => ''];

// Sanitize inputs for security
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

switch ($action) {
    case 'add':
        // Check for required fields for adding a user
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['role'])) {
            $response['message'] = 'Missing required fields for Add User.';
            break;
        }

        $username = sanitize_input($conn, $_POST['username']);
        $password = password_hash(sanitize_input($conn, $_POST['password']), PASSWORD_DEFAULT); // HASH THE PASSWORD!
        $name     = sanitize_input($conn, $_POST['name']);
        $email    = sanitize_input($conn, $_POST['email']);
        $role     = sanitize_input($conn, $_POST['role']);

        $sql = "INSERT INTO users (username, password, name, email, role) VALUES ('$username', '$password', '$name', '$email', '$role')";

        if (mysqli_query($conn, $sql)) {
            $response['success'] = true;
            $response['message'] = 'User added successfully.';
        } else {
            $response['message'] = 'Error adding user: ' . mysqli_error($conn);
        }
        break;

    case 'edit':
        // Check for required fields for editing a user
        if (empty($_POST['id']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['role'])) {
            $response['message'] = 'Missing required fields for Edit User.';
            break;
        }

        $id       = (int)$_POST['id'];
        $username = sanitize_input($conn, $_POST['username']);
        $name     = sanitize_input($conn, $_POST['name']);
        $email    = sanitize_input($conn, $_POST['email']);
        $role     = sanitize_input($conn, $_POST['role']);

        // Only update password if a new password field was included and populated
        $password_update = "";
        if (!empty($_POST['password'])) {
            $password = password_hash(sanitize_input($conn, $_POST['password']), PASSWORD_DEFAULT);
            $password_update = ", password = '$password'";
        }

        $sql = "UPDATE users SET username = '$username', name = '$name', email = '$email', role = '$role' $password_update WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            $response['success'] = true;
            $response['message'] = 'User updated successfully.';
        } else {
            $response['message'] = 'Error updating user: ' . mysqli_error($conn);
        }
        break;

    case 'delete':
        // Check for required fields for deleting a user
        if (empty($_POST['id'])) {
            $response['message'] = 'Missing User ID for Delete.';
            break;
        }

        $id = (int)$_POST['id'];
        $sql = "DELETE FROM users WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            $response['success'] = true;
            $response['message'] = 'User deleted successfully.';
        } else {
            $response['message'] = 'Error deleting user: ' . mysqli_error($conn);
        }
        break;

    default:
        $response['message'] = 'Invalid action.';
        break;
}

echo json_encode($response);
mysqli_close($conn);
?>