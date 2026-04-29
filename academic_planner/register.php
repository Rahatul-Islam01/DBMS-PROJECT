<?php
include('includes/config.php');
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";
$success = false;

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    
    $check_sql = "SELECT * FROM users WHERE userId='$userId' OR email='$email'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        $message = "User ID or Email already exists!";
    } else {
       
        $sql = "INSERT INTO users (userId, name, email, password, dept) 
                VALUES ('$userId', '$name', '$email', '$password', '$dept')";
        
        if(mysqli_query($conn, $sql)) {
            $message = "Registration successful! You can now login.";
            $success = true;
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Create New Account</h2>
            
            <?php if(!empty($message)): ?>
                <div class="<?php echo $success ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="auth-form">
                <div class="form-group">
                    <label for="userId">User ID</label>
                    <input type="text" id="userId" name="userId" required 
                           placeholder="">
                </div>
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Enter your name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email address">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Create a password">
                </div>
                
                <div class="form-group">
                    <label for="dept">Department</label>
                    <input type="text" id="dept" name="dept" required 
                           placeholder="Enter your department">
                </div>
                
                <button type="submit" name="register" class="btn btn-primary btn-block">
                    Register
                </button>
            </form>
            
            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
             
            </div>
        </div>
    </div>
</body>
</html>