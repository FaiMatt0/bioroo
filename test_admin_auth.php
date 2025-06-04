<!DOCTYPE html>
<html>
<head>
    <title>Admin Authentication Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; }
        form { background: #f8f9fa; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .logout-btn { background: #dc3545; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Authentication Test</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Load required files
        require_once 'config/config.php';
        require_once 'config/database.php';
        require_once 'models/User.php';
        require_once 'utils/helpers.php';
        
        session_start();
        
        // Handle logout
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            session_destroy();
            session_start();
            echo "<div class='section success'>Logged out successfully</div>";
        }
        
        // Handle login
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            echo "<div class='section'>";
            echo "<h3>Login Attempt</h3>";
            
            try {
                $userModel = new User();
                $user = $userModel->getByEmail($email);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['is_admin'] = ($user['role'] === 'admin');
                    $_SESSION['role'] = $user['role'];
                    
                    echo "<div class='success'>";
                    echo "✓ Login successful!<br>";
                    echo "User: {$user['first_name']} {$user['last_name']}<br>";
                    echo "Email: {$user['email']}<br>";
                    echo "Role: {$user['role']}<br>";
                    echo "Is Admin: " . ($_SESSION['is_admin'] ? 'Yes' : 'No') . "<br>";
                    echo "</div>";
                } else {
                    echo "<div class='error'>✗ Invalid credentials</div>";
                }
            } catch (Exception $e) {
                echo "<div class='error'>✗ Login error: " . $e->getMessage() . "</div>";
            }
            
            echo "</div>";
        }
        
        // Show current session status
        echo "<div class='section info'>";
        echo "<h3>Current Session Status</h3>";
        if (isLoggedIn()) {
            echo "Logged in: Yes<br>";
            echo "User ID: " . $_SESSION['user_id'] . "<br>";
            echo "Name: " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . "<br>";
            echo "Email: " . $_SESSION['user_email'] . "<br>";
            echo "Role: " . $_SESSION['role'] . "<br>";
            echo "Is Admin: " . (isAdmin() ? 'Yes' : 'No') . "<br>";
            echo "<a href='?action=logout'><button class='logout-btn'>Logout</button></a>";
        } else {
            echo "Logged in: No<br>";
        }
        echo "</div>";
        
        // Show login form if not logged in
        if (!isLoggedIn()) {
            ?>
            <form method="POST">
                <h3>Admin Login</h3>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="admin@bioro.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <small>Try common passwords like: admin, password, 123456</small>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            
            <div class="section info">
                <h3>Available Users</h3>
                <?php
                try {
                    $conn = getDBConnection();
                    $result = $conn->query("SELECT id, email, first_name, last_name, role FROM users ORDER BY role DESC, id");
                    if ($result && $result->num_rows > 0) {
                        echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
                        echo "<tr><th>ID</th><th>Email</th><th>Name</th><th>Role</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['first_name']} {$row['last_name']}</td>";
                            echo "<td><strong>{$row['role']}</strong></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No users found in database.";
                    }
                } catch (Exception $e) {
                    echo "Error retrieving users: " . $e->getMessage();
                }
                ?>
            </div>
            <?php
        } else if (isAdmin()) {
            ?>
            <div class="section success">
                <h3>Admin Access Granted</h3>
                <p>You are logged in as an admin. You can now:</p>
                <ul>
                    <li><a href="<?= BASE_URL ?>/admin">Go to Admin Dashboard</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/products">Manage Products</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/products/create">Create New Product</a></li>
                    <li><a href="test_product_creation_complete.php">Test Product Creation</a></li>
                </ul>
            </div>
            <?php
        } else {
            ?>
            <div class="section error">
                <h3>Access Denied</h3>
                <p>You are logged in but do not have admin privileges.</p>
            </div>
            <?php
        }
        ?>
        
    </div>
</body>
</html>
