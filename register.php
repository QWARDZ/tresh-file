<?php
require_once 'db.php';
require_once 'config.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize input
    $input_username = sanitizeInput($_POST["username"] ?? '');
    $input_password = $_POST["password"] ?? '';
    
    if (empty($input_username) || empty($input_password)) {
        $error_message = "All fields are required.";
    } else {
        
        // Encrypt username
        $encrypted_username = encryptData($input_username);
        
        // Hash password
        $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);
        
        try {
            // Check if username exists
            $sql = "SELECT username FROM users";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $existing_users = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            $username_exists = false;
            foreach ($existing_users as $user) {
                if (decryptData($user['username']) === $input_username) {
                    $username_exists = true;
                    break;
                }
            }
            
            if ($username_exists) {
                $error_message = "Username already exists.";
            } else {
                // Insert with parameterized query
                $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                $statement = $conn->prepare($sql);
                $statement->execute([$encrypted_username, $hashed_password]);
                
                $success_message = "Registration successful!";
            }
            
        } catch (PDOException $error) {
            $error_message = "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; padding: 50px; background: #f0f0f0; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 5px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { background: #ffdddd; color: red; padding: 10px; margin-bottom: 10px; }
        .success { background: #ddffdd; color: green; padding: 10px; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        
        <div class="link">
            <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
