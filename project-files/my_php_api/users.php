<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // 允许任何来源
header('Access-Control-Allow-Methods: GET, POST, DELETE');  // 允许 GET、POST 和 DELETE 方法
header('Access-Control-Allow-Headers: Content-Type');  // 允许特定的请求头
require 'db.php';

// 获取用户列表
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();
    echo json_encode($users);
}

// 添加新用户
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = password_hash($data->password, PASSWORD_DEFAULT); // 密码加密
    $email = $data->email;

    $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $email]);

    echo json_encode(['message' => 'User created successfully']);
}

// 删除用户
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id; // 获取要删除的用户 ID

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['message' => 'User deleted successfully']);
}

?>
