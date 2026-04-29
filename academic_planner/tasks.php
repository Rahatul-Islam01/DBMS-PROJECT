<?php
include('includes/config.php');
include('includes/auth.php');
$userId = $_SESSION['user_id'];
$message = "";

if(isset($_POST['add_task'])) {
    $task_type = mysqli_real_escape_string($conn, $_POST['task_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "INSERT INTO tasks (userId, task_type, description, status) 
            VALUES ('$userId', '$task_type', '$description', 'incomplete')";
    
    if(mysqli_query($conn, $sql)) {
        $message = " Task added successfully!";
    } else {
        $message = " Error adding task!";
    }
}

if(isset($_POST['update_status'])) {
    $task_id = mysqli_real_escape_string($conn, $_POST['task_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "UPDATE tasks SET status='$status' WHERE id='$task_id' AND userId='$userId'");
    $message = " Task status updated!";
}

// Delete task
if(isset($_GET['delete'])) {
    $task_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM tasks WHERE id='$task_id' AND userId='$userId'");
    $message = "Task deleted!";
    header("Location: tasks.php?msg=" . urlencode($message));
    exit();
}

if(isset($_GET['msg'])) {
    $message = $_GET['msg'];
}

$tasks_sql = "SELECT * FROM tasks WHERE userId='$userId' ORDER BY 
             CASE WHEN status='incomplete' THEN 1 ELSE 2 END, id DESC";
$tasks_result = mysqli_query($conn, $tasks_sql);

$total_tasks = mysqli_num_rows($tasks_result);
$completed_tasks = 0;
$pending_tasks = 0;

while($task = mysqli_fetch_assoc($tasks_result)) {
    if($task['status'] == 'complete') {
        $completed_tasks++;
    } else {
        $pending_tasks++;
    }
}
mysqli_data_seek($tasks_result, 0); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks - Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <h1>✓ Task Management</h1>
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
            <a href="resources.php" class="nav-link"> Resources</a>
            <a href="exam_routine.php" class="nav-link"> Exam Routine</a>
            <a href="tasks.php" class="nav-link active">✓ Tasks</a>
        </nav>
        
        <!-- Content -->
        <div class="content">
            <?php if(!empty($message)): ?>
                <div class="alert-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <!-- Task Stats -->
            <div class="task-stats">
                <div class="stat-box">
                    <h3>Total Tasks</h3>
                    <p class="stat-number"><?php echo $total_tasks; ?></p>
                </div>
                <div class="stat-box">
                    <h3>Pending</h3>
                    <p class="stat-number" style="color: #ff9800;"><?php echo $pending_tasks; ?></p>
                </div>
                <div class="stat-box">
                    <h3>Completed</h3>
                    <p class="stat-number" style="color: #4caf50;"><?php echo $completed_tasks; ?></p>
                </div>
            </div>
            
            <!-- Add Task Form -->
            <div class="content-section">
                <h2>➕ Add New Task</h2>
                <form method="POST" class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Task Type:</label>
                            <select name="task_type" required>
                                <option value="">Select Type</option>
                                <option value="Assignment">Assignment</option>
                                <option value="CT">Class Test</option>
                                <option value="HW">Homework</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Project">Project</option>
                                <option value="Presentation">Presentation</option>
                                <option value="Lab Report">Lab Report</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description:</label>
                            <input type="text" name="description" required 
                                   placeholder="">
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="add_task" class="btn btn-primary">
                                Add Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Task List -->
            <div class="content-section">
                <h2>Your Tasks</h2>
                <?php if($total_tasks > 0): ?>
                    <div class="tasks-list">
                        <?php while($task = mysqli_fetch_assoc($tasks_result)): ?>
                        <div class="task-item <?php echo $task['status']; ?>">
                            <div class="task-info">
                                <div class="task-type">
                                    <?php 
                                    switch($task['task_type']) {
                                        case 'Assignment': echo ''; break;
                                        case 'CT': echo ''; break;
                                        case 'HW': echo ''; break;
                                        case 'Quiz': echo ''; break;
                                        case 'Project': echo ''; break;
                                        default: echo '';
                                 }
                                  ?>
                                 <?php echo $task['task_type']; ?>
                            </div>
                            <div class="task-desc">
                                <h4><?php echo $task['description']; ?></h4>
                                <span class="task-status <?php echo $task['status']; ?>">
                                     <?php echo ucfirst($task['status']); ?>
                                  </span>
                              </div>
                         </div>
                         <div class="task-actions">
                             <!-- Update Status Form -->
                             <form method="POST" class="status-form">
                               <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                 <select name="status" class="status-select" 
                                 onchange="this.form.submit()">
                                <option value="incomplete" <?php echo $task['status']=='incomplete'?'selected':''; ?>>Incomplete</option>
                                 <option value="complete" <?php echo $task['status']=='complete'?'selected':''; ?>>Complete</option>
                                 </select>
                                <input type="submit" name="update_status" value="Update" style="display: none;">
                              </form>
                                
                            <a href="tasks.php?delete=<?php echo $task['id']; ?>" 
                                 class="btn btn-danger btn-small"
                                 onclick="return confirm('Delete this task?')">
                                  Delete
                            </a>
                        </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <p>📭 No tasks found. Add your first task above!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
       
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>