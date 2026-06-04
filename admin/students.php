<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

// Handle Delete
if(isset($_GET['delete'])) {
    // CSRF verification on GET parameter delete
    $csrf_token = $_GET['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $id = intval($_GET['delete']);
        
        // Find existing image to delete if it's not default.png
        $img_stmt = $conn->prepare("SELECT image FROM students WHERE id = ?");
        $img_stmt->bind_param("i", $id);
        $img_stmt->execute();
        $img_res = $img_stmt->get_result();
        if ($img_res && $img_res->num_rows > 0) {
            $student_img = $img_res->fetch_assoc()['image'];
            if ($student_img !== 'default.png' && file_exists("../uploads/" . $student_img)) {
                unlink("../uploads/" . $student_img);
            }
        }
        $img_stmt->close();
        
        // Delete student record
        $del_stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $del_stmt->bind_param("i", $id);
        if($del_stmt->execute()) {
            $message = "Student deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting student.";
            $messageType = "error";
        }
        $del_stmt->close();
    }
}

// Handle Add Student
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $student_id = trim($_POST['student_id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $course_id = intval($_POST['course_id'] ?? 0);
        $image_name = 'default.png';
        
        // Handle Secure File Upload
        $upload_ok = true;
        if (isset($_FILES['student_image']) && $_FILES['student_image']['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['student_image']['tmp_name'];
            $file_name = basename($_FILES['student_image']['name']);
            $file_size = $_FILES['student_image']['size'];
            
            // 1. Validate File Size (Max 2MB)
            if ($file_size > 2 * 1024 * 1024) {
                $message = "Error: Image size exceeds 2MB limit.";
                $messageType = "error";
                $upload_ok = false;
            } else {
                // 2. Validate Extension
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                
                // 3. Verify MIME Type
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->file($file_tmp);
                $allowed_mimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
                if (!in_array($ext, $allowed_exts) || !in_array($mime_type, $allowed_mimes)) {
                    $message = "Error: Invalid image format. Only JPG, JPEG, PNG, and GIF allowed.";
                    $messageType = "error";
                    $upload_ok = false;
                } else {
                    // 4. Secure Renaming (uuid-like random text)
                    $secure_name = bin2hex(random_bytes(16)) . '.' . $ext;
                    $upload_dir = '../uploads/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $dest_path = $upload_dir . $secure_name;
                    if (move_uploaded_file($file_tmp, $dest_path)) {
                        $image_name = $secure_name;
                    } else {
                        $message = "Error: Failed to save uploaded image.";
                        $messageType = "error";
                        $upload_ok = false;
                    }
                }
            }
        }
        
        if($upload_ok) {
            // Validation of other fields
            if(empty($student_id) || empty($name) || empty($email) || $course_id <= 0) {
                $message = "All fields are required.";
                $messageType = "error";
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Invalid email format.";
                $messageType = "error";
            } else {
                // Prepared statement to insert student
                $ins_stmt = $conn->prepare("INSERT INTO students (student_id, name, email, course_id, image) VALUES (?, ?, ?, ?, ?)");
                $ins_stmt->bind_param("sssis", $student_id, $name, $email, $course_id, $image_name);
                
                if($ins_stmt->execute()) {
                    $message = "Student added successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error: Something went wrong. Make sure Student ID is unique.";
                    $messageType = "error";
                }
                $ins_stmt->close();
            }
        }
    }
}

$active_page = 'students';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Manage Students</h2>
            <div class="user-info">
                <span><?php echo xss_clean($_SESSION['name']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($message): ?>
                <div style="background: <?php echo $messageType == 'error' ? '#f8d7da; color: #721c24;' : '#d4edda; color: #155724;'; ?> padding: 10px; border-radius: 4px; margin-bottom: 1rem;">
                    <?php echo xss_clean($message); ?>
                </div>
            <?php endif; ?>

            <div style="display:flex; gap:2rem; flex-wrap: wrap;">
                <!-- Add Student Form -->
                <div style="flex: 1; min-width: 300px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Add New Student</h3>
                    <form method="POST" action="" enctype="multipart/form-data" style="margin-top: 1rem;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="add_student" value="1">
                        
                        <div style="margin-bottom: 1rem;">
                            <label>Student ID</label>
                            <input type="text" name="student_id" required placeholder="e.g. STU-1003" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Name</label>
                            <input type="text" name="name" required placeholder="Full Name" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Email</label>
                            <input type="email" name="email" required placeholder="student@example.com" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Course</label>
                            <select name="course_id" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                                <option value="">Select Course</option>
                                <?php
                                $c_res = $conn->query("SELECT * FROM courses");
                                while($c = $c_res->fetch_assoc()) {
                                    echo "<option value='".$c['id']."'>".xss_clean($c['name'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label>Profile Image (Max 2MB, JPG/PNG/GIF)</label>
                            <input type="file" name="student_image" accept="image/*" style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                        </div>
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; width: 100%;">Add Student</button>
                    </form>
                </div>

                <!-- Students List -->
                <div style="flex: 2; min-width: 500px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Student List</h3>
                    <div style="overflow-x: auto; margin-top: 1rem;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $s_res = $conn->query("SELECT s.*, c.name as course_name FROM students s LEFT JOIN courses c ON s.course_id = c.id");
                                while($row = $s_res->fetch_assoc()):
                                    $img_src = ($row['image'] !== 'default.png' && file_exists("../uploads/" . $row['image'])) ? "../uploads/" . $row['image'] : 'https://ui-avatars.com/api/?name=' . urlencode($row['name']) . '&background=random';
                                ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo $img_src; ?>" alt="Student Photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td><?php echo xss_clean($row['student_id']); ?></td>
                                    <td><?php echo xss_clean($row['name']); ?></td>
                                    <td><?php echo xss_clean($row['course_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $row['id']; ?>&csrf_token=<?php echo generate_csrf_token(); ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
