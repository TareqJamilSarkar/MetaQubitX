<?php
session_start();
require 'db.php';  

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $admin = $_POST['admin'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($admin === 'tareq' && $password === 'tareq321') {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid admin credentials.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    header("Location: admin.php");
    exit;
}

// Only show contacts if logged in as admin
if (!isset($_SESSION['is_admin'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Qubit Cloud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #f6f8fb;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            margin: 0;
        }
        .admin-login-container {
            max-width: 420px;
            margin: 80px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(49,47,255,0.08);
            padding: 44px 32px;
            text-align: center;
        }
        h1 {
            color: #312fff;
            font-size: 2.1rem;
            margin-bottom: 24px;
        }
        label {
            display: block;
            text-align: left;
            margin-bottom: 14px;
            font-weight: 500;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #d4d7ee;
            font-size: 1rem;
            background: #f6f8fb;
        }
        .button {
            padding: 12px 26px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            background: #312fff;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            transition: background 160ms ease, transform 140ms ease;
        }
        .button:hover {
            background: #5345d6;
            transform: translateY(-1px);
        }
        .contact-msg {
            margin-bottom: 18px;
            color: #d81b60;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <h1>Admin Login</h1>
        <?php if (isset($error)): ?>
            <div class="contact-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>
                Admin Name:
                <input type="text" name="admin" required>
            </label>
            <label>
                Password:
                <input type="password" name="password" required>
            </label>
            <button type="submit" name="admin_login" class="button">Login</button>
        </form>
    </div>
</body>
</html>
<?php
exit;
endif;

// If admin logged in, show contacts:
$stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
$contacts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Qubit Cloud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #f6f8fb;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            margin: 0;
        }
        .admin-panel-container {
            max-width: 1050px;
            margin: 50px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(49,47,255,0.08);
            padding: 44px 32px;
        }
        h1 {
            color: #312fff;
            font-size: 2.1rem;
            margin-bottom: 24px;
            text-align: center;
        }
        .button {
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            background: #312fff;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
            transition: background 160ms ease, transform 140ms ease;
            margin-bottom: 26px;
        }
        .button:hover {
            background: #5345d6;
            transform: translateY(-1px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #fafafa;
            border-radius: 14px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 10px;
            text-align: left;
        }
        th {
            background: #e6e8fa;
            color: #312fff;
            font-weight: 700;
            font-size: 1.05rem;
            border-bottom: 1px solid #d4d7ee;
        }
        td {
            background: #fff;
            font-size: 1rem;
            border-bottom: 1px solid #eee;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .logout-link {
            float: right;
            margin-top: -20px;
            margin-right: -12px;
            background: #e6e8fa;
            color: #312fff;
            border: 2px solid #312fff;
        }
        .logout-link:hover {
            background: #312fff;
            color: #fff;
        }
        @media (max-width:900px){
            .admin-panel-container { padding: 12px 6px; }
            table, th, td { font-size: 0.97rem; }
        }
        @media (max-width:580px){
            table, th, td { font-size: 0.9rem; padding: 8px 4px; }
        }
    </style>
</head>
<body>
    <div class="admin-panel-container">
        <h1>Contact Submissions</h1>
        <a href="admin.php?logout=1" class="button logout-link">Logout</a>
        <?php if (empty($contacts)): ?>
            <p>No contacts submitted yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($contacts as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['name']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo htmlspecialchars($c['phone']); ?></td>
                        <td><?php echo htmlspecialchars($c['subject']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($c['message'])); ?></td>
                        <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>