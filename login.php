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
        $error_message = "Invalid username or password.";
    } else {
        
        try {
            // Fetch all users
            $sql = "SELECT username, password FROM users";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $all_users = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            $user_found = false;
            $user_password = null;
            
            // Find user by decrypting usernames
            foreach ($all_users as $user) {
                if (decryptData($user['username']) === $input_username) {
                    $user_found = true;
                    $user_password = $user['password'];
                    break;
                }
            }
            
            // Verify password
            if ($user_found && password_verify($input_password, $user_password)) {
                $success_message = "Login successful!";
            } else {
                $error_message = "Invalid username or password.";
            }
            
        } catch (PDOException $error) {
            $error_message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial; padding: 50px; background: #f0f0f0; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 5px; }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        .error { background: #ffdddd; color: red; padding: 10px; margin-bottom: 10px; }
        .success { background: #ddffdd; color: green; padding: 10px; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        
        <div class="link">
            <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>
