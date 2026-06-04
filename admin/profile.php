<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

$user_id = $_SESSION['user_id'];

// Fetch current user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle Profile Update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $name = trim($_POST['name'] ?? '');
        $image_name = $user['profile_image'];
        
        // Validation
        if(strlen($name) < 3 || strlen($name) > 50 || !preg_match("/^[A-Za-z\s]+$/", $name)) {
            $message = "Invalid name format. Only letters and spaces, 3 to 50 characters.";
            $messageType = "error";
        } else {
            $upload_ok = true;
            
            // Handle Profile Image Upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['profile_image']['tmp_name'];
                $file_name = basename($_FILES['profile_image']['name']);
                $file_size = $_FILES['profile_image']['size'];
                
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
                        // Delete old profile image if it exists and is not default.png
                        if ($user['profile_image'] !== 'default.png' && file_exists("../uploads/" . $user['profile_image'])) {
                            unlink("../uploads/" . $user['profile_image']);
                        }
                        
                        // 4. Secure Renaming
                        $secure_name = "profile_" . $user_id . "_" . bin2hex(random_bytes(8)) . '.' . $ext;
                        $upload_dir = '../uploads/';
                        
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }
                        
                        if (move_uploaded_file($file_tmp, $upload_dir . $secure_name)) {
                            $image_name = $secure_name;
                        } else {
                            $message = "Error saving uploaded image.";
                            $messageType = "error";
                            $upload_ok = false;
                        }
                    }
                }
            }
            
            if ($upload_ok) {
                // Update users record
                $upd_stmt = $conn->prepare("UPDATE users SET name = ?, profile_image = ? WHERE id = ?");
                $upd_stmt->bind_param("ssi", $name, $image_name, $user_id);
                if($upd_stmt->execute()) {
                    // Update session
                    $_SESSION['name'] = $name;
                    $message = "Profile updated successfully.";
                    $messageType = "success";
                    
                    // Refresh local user variables
                    $user['name'] = $name;
                    $user['profile_image'] = $image_name;
                } else {
                    $message = "Error updating profile details.";
                    $messageType = "error";
                }
                $upd_stmt->close();
            }
        }
    }
}

$active_page = 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - SIMS</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Admin Profile</h2>
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

            <div style="max-width: 600px; background: var(--white); padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow); margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <?php
                    $img_src = ($user['profile_image'] !== 'default.png' && file_exists("../uploads/" . $user['profile_image'])) ? "../uploads/" . $user['profile_image'] : 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=random';
                    ?>
                    <img src="<?php echo $img_src; ?>" alt="Admin Profile Photo" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-color);">
                    <h3 style="margin-top: 1rem;"><?php echo xss_clean($user['name']); ?></h3>
                    <p style="color: var(--secondary-color); font-size: 0.9rem;"><?php echo xss_clean(ucfirst($user['role'])); ?> Account</p>
                </div>

                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Username</label>
                        <input type="text" value="<?php echo xss_clean($user['username']); ?>" disabled style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px; background:#f4f6f9; color:#888;">
                        <span style="font-size:0.75rem; color:#888;">Username cannot be modified.</span>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Email Address</label>
                        <input type="email" value="<?php echo xss_clean($user['email']); ?>" disabled style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px; background:#f4f6f9; color:#888;">
                        <span style="font-size:0.75rem; color:#888;">Email address cannot be modified.</span>
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Display Name</label>
                        <input type="text" name="name" required value="<?php echo xss_clean($user['name']); ?>" style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Country</label>
                        <input type="text" value="<?php echo xss_clean($user['country'] ?: 'N/A'); ?>" disabled style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px; background:#f4f6f9; color:#888;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Gender</label>
                        <input type="text" value="<?php echo xss_clean($user['gender'] ?: 'N/A'); ?>" disabled style="width:100%; padding:0.6rem; border:1px solid #ddd; border-radius:4px; background:#f4f6f9; color:#888;">
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label style="display:block; font-weight:500; margin-bottom:0.4rem;">Change Avatar (Max 2MB, JPG/PNG/GIF)</label>
                        <input type="file" name="profile_image" accept="image/*" style="width:100%; padding:0.5rem; margin-top:0.3rem;">
                    </div>

                    <button type="submit" style="background: var(--accent-color); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; width: 100%; font-weight: 600;">Save Changes</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
