<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

// Handle Add Category
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (empty($name)) {
            $message = "Category Name is required.";
            $messageType = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $description);
            if ($stmt->execute()) {
                $message = "Category added successfully.";
                $messageType = "success";
            } else {
                $message = "Error adding category. Category Name might already exist.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

// Handle Delete Category
if(isset($_GET['delete'])) {
    $csrf_token = $_GET['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $id = intval($_GET['delete']);
        
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Category deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting category.";
            $messageType = "error";
        }
        $stmt->close();
    }
}

$active_page = 'categories';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Category Management</h2>
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
                <!-- Add Category Form -->
                <div style="flex: 1; min-width: 300px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Add New Category</h3>
                    <form method="POST" action="" style="margin-top: 1rem;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="add_category" value="1">
                        
                        <div style="margin-bottom: 1rem;">
                            <label>Category Name</label>
                            <input type="text" name="name" required placeholder="e.g. Science, Commerce, Arts" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label>Description</label>
                            <textarea name="description" placeholder="Optional category description" rows="4" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px; font-family:inherit;"></textarea>
                        </div>
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; width: 100%;">Add Category</button>
                    </form>
                </div>

                <!-- Category List -->
                <div style="flex: 2; min-width: 500px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Category List</h3>
                    <div style="overflow-x: auto; margin-top: 1rem;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cat_res = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                                while($row = $cat_res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><strong><?php echo xss_clean($row['name']); ?></strong></td>
                                    <td><?php echo xss_clean($row['description'] ?: 'No description'); ?></td>
                                    <td>
                                        <a href="?delete=<?php echo $row['id']; ?>&csrf_token=<?php echo generate_csrf_token(); ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this category? Courses under this category will have their category cleared.');">Delete</a>
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
