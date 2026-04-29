<?php
include('includes/config.php');
include('includes/auth.php');
$userId = $_SESSION['user_id'];
$message = "";

$enrolled_courses = array();
$courses_sql = "SELECT DISTINCT course_code FROM enroll WHERE userId='$userId'";
$courses_result = mysqli_query($conn, $courses_sql);
while($course = mysqli_fetch_assoc($courses_result)) {
    $enrolled_courses[] = $course['course_code'];
}

if(isset($_POST['upload'])) {
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $resource_type = mysqli_real_escape_string($conn, $_POST['resource_type']);
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_error = $_FILES['file']['error'];
    
    if(!in_array($course_code, $enrolled_courses)) {
        $message = " You are not enrolled in this course!";
    } elseif($file_error !== 0) {
        $message = " File upload error!";
    } elseif($file_size > 5000000) { 
        $message = " File is too large (max 5MB)";
    } else {
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('pdf', 'doc', 'docx', 'txt', 'jpg', 'png', 'jpeg');
        
        if(!in_array($file_ext, $allowed_ext)) {
            $message = " Only PDF, DOC, DOCX, TXT, JPG, PNG files are allowed!";
        } else {
    
            $upload_dir = "uploads/";
            if(!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $course_dir = $upload_dir . $course_code . "/";
            if(!is_dir($course_dir)) {
                mkdir($course_dir, 0777, true);
            }
            
            $type_dir = $course_dir . $resource_type . "/";
            if(!is_dir($type_dir)) {
                mkdir($type_dir, 0777, true);
            }
            
            $unique_name = $course_code . "_" . $resource_type . "_" . time() . "." . $file_ext;
            $destination = $type_dir . $unique_name;
        
            if(move_uploaded_file($file_tmp, $destination)) {

                $folder_name = $course_code . "/" . $resource_type;
                $file_path = $destination;
                
                $sql = "INSERT INTO resource (Folder_name, File_name, File_path, UploaderId, resource_type, Approve) 
                        VALUES ('$folder_name', '$unique_name', '$file_path', '$userId', '$resource_type', 'no')";
                
                if(mysqli_query($conn, $sql)) {
                    $message = " Resource uploaded successfully! Waiting for admin approval.";
                } else {
                    $message = " Error saving to database!";
                    unlink($destination); 
                }
            } else {
                $message = " Error uploading file!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resource - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <h1>Upload Resource</h1>
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
            <a href="exam_routine.php" class="nav-link">Exam Routine</a>
            <a href="tasks.php" class="nav-link">✓ Tasks</a>
            <a href="upload_resource.php" class="nav-link active">Upload Resource</a>
        </nav>
        
        <!-- Content -->
        <div class="content">
            <div class="content-header">
                <h2>Upload New Resource</h2>
            </div>
            
            <?php if(!empty($message)): ?>
                <div class="alert-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if(empty($enrolled_courses)): ?>
                <div class="alert-message">
                     You are not enrolled in any courses. 
                    <a href="courses.php">Enroll in courses</a> first to upload resources.
                </div>
            <?php else: ?>
                <div class="form-container">
                    <form method="POST" enctype="multipart/form-data" class="upload-form">
                        <div class="form-group">
                            <label>Select Course:</label>
                            <select name="course_code" required>
                                <option value="">-- Select Course --</option>
                                <?php foreach($enrolled_courses as $course): ?>
                                <option value="<?php echo $course; ?>"><?php echo $course; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Resource Type:</label>
                            <select name="resource_type" required>
                                <option value="">-- Select Type --</option>
                                <option value="Notes">Class Notes</option>
                                <option value="MidTermQuestions">Mid Term Questions</option>
                                <option value="MidTermSolutions">Mid Term Solutions</option>
                                <option value="FinalQuestions">Final Questions</option>
                                <option value="FinalSolutions">Final Solutions</option>
                                <option value="Assignment">Assignment</option>
                                <option value="Lab">Lab Report</option>
                                <option value="Slides">Presentation Slides</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Select File:</label>
                            <div class="file-upload">
                                <input type="file" name="file" id="file" required 
                                       accept=".pdf,.doc,.docx,.txt,.jpg,.png,.jpeg">
                                <label for="file" class="file-label">
                                    <span> Choose File </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="upload" class="btn btn-primary btn-block">
                                Upload Resource
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- User's Upload History -->
                <div class="content-section">
                    <h3>Your Upload History</h3>
                    <?php
                    $history_sql = "SELECT * FROM resource 
                                    WHERE UploaderId='$userId' 
                                    ORDER BY id DESC LIMIT 10";
                    $history_result = mysqli_query($conn, $history_sql);
                    
                    if(mysqli_num_rows($history_result) > 0): ?>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Course</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                       
                            </tr>
                            </thead>
                             <tbody>
                             <?php while($history = mysqli_fetch_assoc($history_result)): ?>
                             <tr>
                              <td><?php echo $history['File_name']; ?></td>
                                <td><?php echo explode('/', $history['Folder_name'])[0]; ?></td>
                                 <td><?php echo $history['resource_type']; ?></td>
                                     <td>
                                      <span class="status-badge <?php echo $history['Approve']; ?>">
                                           <?php echo ucfirst($history['Approve']); ?>
                                         </span>
                                    </td>
                                       
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No upload history found.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
