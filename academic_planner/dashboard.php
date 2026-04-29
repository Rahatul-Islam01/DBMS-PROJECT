<?php
include('includes/config.php');
include('includes/auth.php');
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <h1>🎓 Academic Portal</h1>
                <p class="welcome">Welcome, <?php echo htmlspecialchars($userName); ?>!</p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span>ID: <?php echo $userId; ?></span>
                    <span>Dept: <?php echo $_SESSION['user_dept']; ?></span>
                </div>
                <a href="includes/logout.php" class="btn btn-logout">Logout</a>
            </div>
        </header>
        
        <!-- Navigation -->
        <nav class="main-nav">
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="courses.php" class="nav-link">My Courses</a>
            <a href="resources.php" class="nav-link">Resources</a>
            <a href="exam_routine.php" class="nav-link">Exam Routine</a>
            <a href="tasks.php" class="nav-link">✓ Tasks</a>
            <a href="upload_resource.php" class="nav-link">Upload Resource</a>
        </nav>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <h2>Dashboard Overview</h2>
            
            <div class="stats-cards">
                <?php
                $courses_sql = "SELECT COUNT(*) as total FROM enroll WHERE userId='$userId'";
                $courses_result = mysqli_query($conn, $courses_sql);
                $courses_row = mysqli_fetch_assoc($courses_result);
                $total_courses = $courses_row['total'];
                
                $tasks_sql = "SELECT COUNT(*) as total FROM tasks WHERE userId='$userId' AND status='incomplete'";
                $tasks_result = mysqli_query($conn, $tasks_sql);
                $tasks_row = mysqli_fetch_assoc($tasks_result);
                $pending_tasks = $tasks_row['total'];
                $resources_sql = "SELECT COUNT(DISTINCT r.id) as total 
                                 FROM resource r 
                                 JOIN enroll e ON r.Folder_name LIKE CONCAT(e.course_code, '/%')
                                 WHERE e.userId='$userId' AND r.Approve='yes'";
                $resources_result = mysqli_query($conn, $resources_sql);
                $resources_row = mysqli_fetch_assoc($resources_result);
                $available_resources = $resources_row['total'];
                
                $exams_sql = "SELECT COUNT(DISTINCT er.id) as total 
                             FROM examroutine er 
                             JOIN enroll e ON er.course_code = e.course_code 
                             WHERE e.userId='$userId'";
                $exams_result = mysqli_query($conn, $exams_sql);
                $exams_row = mysqli_fetch_assoc($exams_result);
                $upcoming_exams = $exams_row['total'];
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3>Enrolled Courses</h3>
                        <p class="stat-number"><?php echo $total_courses; ?></p>
                    </div>
                    <a href="courses.php" class="stat-link">View Courses →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3>Pending Tasks</h3>
                        <p class="stat-number"><?php echo $pending_tasks; ?></p>
                    </div>
                    <a href="tasks.php" class="stat-link">View Tasks →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3>Resources</h3>
                        <p class="stat-number"><?php echo $available_resources; ?></p>
                    </div>
                    <a href="resources.php" class="stat-link">View Resources →</a>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon"></div>
                    <div class="stat-info">
                        <h3>Upcoming Exams</h3>
                        <p class="stat-number"><?php echo $upcoming_exams; ?></p>
                    </div>
                    <a href="exam_routine.php" class="stat-link">View Schedule →</a>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>⚡ Quick Actions</h3>
                <div class="action-buttons">
                    <a href="courses.php" class="btn btn-action">Add Course</a>
                    <a href="tasks.php" class="btn btn-action">Add Task</a>
                    <a href="upload_resource.php" class="btn btn-action">Upload File</a>
                    <a href="resources.php" class="btn btn-action">View Resources</a>
                </div>
            </div>
            
            <!-- Recent Courses -->
            <div class="recent-section">
                <h3>📋 Recently Enrolled Courses</h3>
                <?php
                $recent_sql = "SELECT e.*, c.course_name 
                              FROM enroll e 
                              JOIN course c ON e.course_code = c.course_code 
                              WHERE e.userId='$userId' 
                              ORDER BY e.id DESC LIMIT 5";
                $recent_result = mysqli_query($conn, $recent_sql);
                
                if(mysqli_num_rows($recent_result) > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Section</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($recent_result)): ?>
                            <tr>
                                <td><?php echo $row['course_code']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                                <td><?php echo $row['section']; ?></td>
                                <td>
                                    <a href="courses.php?drop=<?php echo $row['course_code']; ?>" 
                                       class="btn btn-danger btn-small"
                                       onclick="return confirm('Drop this course?')">
                                       Drop
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">You are not enrolled in any courses yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>