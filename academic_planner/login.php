<?php
include('includes/config.php');

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "SELECT * FROM users WHERE userId='$userId' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['userId'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_dept'] = $row['dept'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid User ID or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Academic Portal</h2>
            
            <?php if(!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="userId"></label>
                    <input type="text" id="userId" name="userId" required 
                           placeholder=" User ID">
                </div>
                
                <div class="form-group">
                    <label for="password"></label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Password">
                </div>
                
                <button type="submit" name="login" class="btn btn-primary btn-block">
                    Login
                </button>
            </form>
            
            <div class="auth-links">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
           
            </div>
            
            
        </div>
    </div>
</body>
</html>