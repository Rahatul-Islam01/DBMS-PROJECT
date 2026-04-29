<?php
include('includes/config.php');
include('includes/auth.php');
$userId = $_SESSION['user_id'];
$message = "";

if(isset($_POST['add_course'])) {
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    
    $check_course = mysqli_query($conn, "SELECT * FROM course WHERE course_code='$course_code'");
    if(mysqli_num_rows($check_course) == 0) {
        $message = " Course code does not exist!";
    } else {
      
        $check_enroll = mysqli_query($conn, "SELECT * FROM enroll WHERE userId='$userId' AND course_code='$course_code'");
        if(mysqli_num_rows($check_enroll) > 0) {
            $message = " You are already enrolled in this course!";
        } else {
            $sql = "INSERT INTO enroll (course_code, userId, section) VALUES ('$course_code', '$userId', '$section')";
            if(mysqli_query($conn, $sql)) {
                $message = "Course added successfully!";
            } else {
                $message = "Error adding course!";
            }
        }
    }
}

if(isset($_GET['drop'])) {
    $course_code = mysqli_real_escape_string($conn, $_GET['drop']);
    mysqli_query($conn, "DELETE FROM enroll WHERE userId='$userId' AND course_code='$course_code'");
    $message = "Course dropped successfully!";
    header("Location: courses.php?msg=" . urlencode($message));
    exit();
}

if(isset($_GET['msg'])) {
    $message = $_GET['msg'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <h1>Course Management</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span>ID: <?php echo $_SESSION['user_id']; ?></span>
                </div>
                <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
                <a href="includes/logout.php" class="btn btn-logout">Logout</a>
            </div>
        </header>
        
        <!-- Navigation -->
        <nav class="main-nav">
            <a href="dashboard.php" class="nav-link">Dashboard</a>
            <a href="courses.php" class="nav-link active">My Courses</a>
            <a href="resources.php" class="nav-link">Resources</a>
            <a href="exam_routine.php" class="nav-link">Exam Routine</a>
            <a href="tasks.php" class="nav-link">✓ Tasks</a>
        </nav>
        
        <!-- Content -->
        <div class="content">
            <?php if(!empty($message)): ?>
                <div class="alert-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="content-section">
                <h2>➕ Add New Course</h2>
                <form method="POST" class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Course Code:</label>
                            <input type="text" name="course_code" required 
                                   placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Section:</label>
                            <input type="text" name="section" required 
                                   placeholder="">
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="add_course" class="btn btn-primary">
                                Add Course
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="content-section">
                <h2>Enrolled Courses</h2>
                <?php
                $sql = "SELECT e.*, c.course_name, c.credits, c.trimester 
                        FROM enroll e 
                        JOIN course c ON e.course_code = c.course_code 
                        WHERE e.userId='$userId' 
                        ORDER BY c.course_code";
                $result = mysqli_query($conn, $sql);
                
                if(mysqli_num_rows($result) > 0): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Section</th>
                                <th>Credits</th>
                                <th>Trimester</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['course_code']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                                <td><?php echo $row['section']; ?></td>
                                <td><?php echo $row['credits']; ?></td>
                                <td><?php echo $row['trimester']; ?></td>
                                <td>
                                    <a href="courses.php?drop=<?php echo $row['course_code']; ?>" 
                                       class="btn btn-danger btn-small"
                                       onclick="return confirm('Are you sure you want to drop this course?')">
                                       Drop
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="no-data">
                        <p>You are not enrolled in any courses yet.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="content-section">
                <h2>All Available Courses</h2>
                <?php
                $all_courses_sql = "SELECT * FROM course ORDER BY course_code";
                $all_courses_result = mysqli_query($conn, $all_courses_sql);
                ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Trimester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($course = mysqli_fetch_assoc($all_courses_result)): ?>
                            <tr>
                                <td><?php echo $course['course_code']; ?></td>
                                <td><?php echo $course['course_name']; ?></td>
                                <td><?php echo $course['credits']; ?></td>
                                <td><?php echo $course['trimester']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>