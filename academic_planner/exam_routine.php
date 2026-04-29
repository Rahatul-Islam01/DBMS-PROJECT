<?php
include('includes/config.php');
include('includes/auth.php');
$userId = $_SESSION['user_id'];

$sql = "SELECT er.* 
        FROM examroutine er
        JOIN enroll e ON er.course_code = e.course_code
        WHERE e.userId='$userId'
        ORDER BY er.exam_date, er.exam_time";
$result = mysqli_query($conn, $sql);

$all_exams_sql = "SELECT * FROM examroutine ORDER BY exam_date, exam_time";
$all_exams_result = mysqli_query($conn, $all_exams_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Routine - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <h1>📅 Exam Routine</h1>
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
            <a href="courses.php" class="nav-link">My Courses</a>
            <a href="resources.php" class="nav-link">Resources</a>
            <a href="exam_routine.php" class="nav-link active">Exam Routine</a>
            <a href="tasks.php" class="nav-link">✓ Tasks</a>
        </nav>
        
        <!-- Content -->
        <div class="content">
            <div class="content-header">
                <h2>Your Exam Schedule</h2>
            </div>
            
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Section</th>
                                <th>Teacher</th>
                                <th>Exam Date</th>
                                <th>Exam Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['course_code']; ?></td>
                                <td><?php echo $row['course_title']; ?></td>
                                <td><?php echo $row['section']; ?></td>
                                <td><?php echo $row['teacher']; ?></td>
                                <td>
                                    <span class="date-badge"><?php echo $row['exam_date']; ?></span>
                                </td>
                                <td><?php echo $row['exam_time']; ?></td>
                                <td>
                                    <span class="room-badge"><?php echo $row['room']; ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <p> No exam routine found for your enrolled courses.</p>
                </div>
            <?php endif; ?>
            
            <!-- All Exams -->
            <div class="content-section">
                <h3> All Department Exams</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Section</th>
                                <th>Exam Date</th>
                                <th>Exam Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($exam = mysqli_fetch_assoc($all_exams_result)): ?>
                            <tr>
                                <td><?php echo $exam['course_code']; ?></td>
                                <td><?php echo $exam['course_title']; ?></td>
                                <td><?php echo $exam['section']; ?></td>
                                <td><?php echo $exam['exam_date']; ?></td>
                                <td><?php echo $exam['exam_time']; ?></td>
                                <td><?php echo $exam['room']; ?></td>
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