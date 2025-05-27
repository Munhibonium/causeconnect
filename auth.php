//#================ User Registration (auth.php)
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'register') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $password]);
        
        // Start session and log user in
        session_start();
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['email'] = $email;
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Email already exists']);
    }
}
?>
