<?php
include('includes/config.php');
include('includes/auth.php');

$userId = $_SESSION['user_id'];
$enrolled_courses = array();
$courses_sql = "SELECT DISTINCT course_code FROM enroll WHERE userId='$userId'";
$courses_result = mysqli_query($conn, $courses_sql);
while($course = mysqli_fetch_assoc($courses_result)) {
    $enrolled_courses[] = $course['course_code'];
}

$resources = array();
if(!empty($enrolled_courses)) {
    $conditions = array();
    foreach($enrolled_courses as $course) {
        $conditions[] = "r.Folder_name LIKE '$course/%'";
    }
    $where_clause = implode(" OR ", $conditions);
    
    $sql = "SELECT r.*, u.name as uploader_name 
            FROM resource r 
            JOIN users u ON r.UploaderId = u.userId 
            WHERE r.Approve='yes' 
            AND ($where_clause) 
            ORDER BY r.Folder_name, r.File_name";
    
    $result = mysqli_query($conn, $sql);
    
    while($row = mysqli_fetch_assoc($result)) {
        $course_code = explode('/', $row['Folder_name'])[0];
        $resources[$course_code][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Resources - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <h1>📁 Course Resources</h1>
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
            <a href="resources.php" class="nav-link active">Resources</a>
            <a href="exam_routine.php" class="nav-link">Exam Routine</a>
            <a href="tasks.php" class="nav-link">✓ Tasks</a>
            <a href="upload_resource.php" class="nav-link">Upload Resource</a>
        </nav>
        
        <!-- Content -->
        <div class="content">
            <div class="content-header">
                <h2>Available Resources</h2>

            </div>
            
            <?php if(empty($enrolled_courses)): ?>
                <div class="alert-message">
                     You are not enrolled in any courses. 
                    <a href="courses.php">Enroll in courses</a> to view resources.
                </div>
            <?php elseif(empty($resources)): ?>
                <div class="no-data">
                    <p>📭 No resources available for your enrolled courses yet.</p>
                    <a href="upload_resource.php" class="btn btn-primary">Be the first to upload</a>
                </div>
            <?php else: ?>
                <?php foreach($resources as $course_code => $course_resources): ?>
                <div class="course-section">
                    <div class="course-header">
                        <h3>📘 <?php echo $course_code; ?></h3>
                        <span class="badge"><?php echo count($course_resources); ?> files</span>
                    </div>
                    
                    <div class="resources-grid">
                        <?php foreach($course_resources as $resource): ?>
                        <div class="resource-card">
                            <div class="resource-icon">
                                <?php 
                                $ext = pathinfo($resource['File_name'], PATHINFO_EXTENSION);
                                if(in_array($ext, ['pdf'])) echo '📄';
                                elseif(in_array($ext, ['doc', 'docx'])) echo '📝';
                                elseif(in_array($ext, ['jpg', 'png', 'jpeg'])) echo '🖼️';
                                else echo '📎';
                                ?>
                            </div>
                            <div class="resource-info">
                                <h4><?php echo $resource['File_name']; ?></h4>
                                <p class="resource-type">Type: <?php echo $resource['resource_type']; ?></p>
                                <p class="resource-uploader">Uploaded by: <?php echo $resource['uploader_name']; ?></p>
                            </div>
                            <div class="resource-actions">
                                <a href="<?php echo $resource['File_path']; ?>" 
                                   target="_blank" 
                                   class="btn btn-primary btn-small">
                                   Download
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>