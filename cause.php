//#===================  Create Cause (causes.php)
<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'error' => 'Not authenticated']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $goalAmount = $_POST['goal_amount'];
    $creditsPerDollar = $_POST['credits_per_dollar'];
    
    // Handle file upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/causes/';
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO causes 
                              (user_id, title, description, category, goal_amount, image_path, credits_per_dollar) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $title, $description, $category, $goalAmount, $imagePath, $creditsPerDollar]);
        
        echo json_encode(['success' => true, 'cause_id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
