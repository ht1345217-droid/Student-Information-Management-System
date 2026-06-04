<?php
require_once 'auth_check.php';

$message = '';
$messageType = '';

// Handle Add Item
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $item_name = trim($_POST['item_name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $quantity = intval($_POST['quantity'] ?? 0);
        $status = trim($_POST['status'] ?? 'Available');
        
        if (empty($item_name) || empty($type) || $quantity < 0) {
            $message = "All fields are required. Quantity must be non-negative.";
            $messageType = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO inventory (item_name, type, quantity, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $item_name, $type, $quantity, $status);
            if ($stmt->execute()) {
                $message = "Inventory item added successfully.";
                $messageType = "success";
            } else {
                $message = "Error adding inventory item.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

// Handle Update Quantity/Status
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_item'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $id = intval($_POST['item_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        
        if ($id > 0 && $quantity >= 0 && in_array($status, ['Available', 'Unavailable', 'Maintenance'])) {
            $stmt = $conn->prepare("UPDATE inventory SET quantity = ?, status = ? WHERE id = ?");
            $stmt->bind_param("isi", $quantity, $status, $id);
            if ($stmt->execute()) {
                $message = "Inventory item updated successfully.";
                $messageType = "success";
            } else {
                $message = "Error updating inventory item.";
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

// Handle Delete Item
if(isset($_GET['delete'])) {
    $csrf_token = $_GET['csrf_token'] ?? '';
    if(!verify_csrf_token($csrf_token)) {
        $message = "Security verification failed. Invalid CSRF token.";
        $messageType = "error";
    } else {
        $id = intval($_GET['delete']);
        
        $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Inventory item deleted successfully.";
            $messageType = "success";
        } else {
            $message = "Error deleting inventory item.";
            $messageType = "error";
        }
        $stmt->close();
    }
}

$active_page = 'inventory';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - SIMS Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Inventory Management</h2>
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
                <!-- Add Inventory Form -->
                <div style="flex: 1; min-width: 300px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Add Campus Asset</h3>
                    <form method="POST" action="" style="margin-top: 1rem;">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="add_item" value="1">
                        
                        <div style="margin-bottom: 1rem;">
                            <label>Item Name</label>
                            <input type="text" name="item_name" required placeholder="e.g. Acer Monitors, Chemistry Beakers" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Type / Category</label>
                            <select name="type" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                                <option value="Electronics">Electronics</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Books">Books</option>
                                <option value="Stationery">Stationery</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label>Quantity</label>
                            <input type="number" min="0" name="quantity" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label>Status</label>
                            <select name="status" required style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ddd; border-radius:4px;">
                                <option value="Available">Available</option>
                                <option value="Unavailable">Unavailable</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; width: 100%;">Add Asset</button>
                    </form>
                </div>

                <!-- Inventory List -->
                <div style="flex: 2; min-width: 500px; background: var(--white); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow);">
                    <h3>Asset & Inventory List</h3>
                    <div style="overflow-x: auto; margin-top: 1rem;">
                        <table style="width:100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $inv_res = $conn->query("SELECT * FROM inventory ORDER BY item_name ASC");
                                while($row = $inv_res->fetch_assoc()):
                                    $badge_class = 'badge-success';
                                    if($row['status'] == 'Unavailable') $badge_class = 'badge-danger';
                                    if($row['status'] == 'Maintenance') $badge_class = 'badge-warning';
                                ?>
                                <tr>
                                    <td><strong><?php echo xss_clean($row['item_name']); ?></strong></td>
                                    <td><?php echo xss_clean($row['type']); ?></td>
                                    <td>
                                        <form method="POST" action="" style="margin:0; padding:0; display:inline-flex; align-items:center; gap:0.25rem;">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="update_item" value="1">
                                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="0" style="width:60px; padding:0.25rem; text-align:center; border:1px solid #ddd; border-radius:4px;">
                                            <select name="status" style="padding: 0.25rem; font-size: 0.85rem; border-radius:4px; border:1px solid #ddd; display:none;">
                                                <option value="Available" <?php echo $row['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                                                <option value="Unavailable" <?php echo $row['status'] == 'Unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                                                <option value="Maintenance" <?php echo $row['status'] == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                            </select>
                                            <button type="submit" class="action-btn btn-edit" style="padding:0.25rem 0.5rem;"><i class="fa-solid fa-save"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <!-- Form to update status inline -->
                                        <form method="POST" action="" style="margin:0; padding:0; display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                            <input type="hidden" name="update_item" value="1">
                                            <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="quantity" value="<?php echo $row['quantity']; ?>">
                                            <select name="status" onchange="this.form.submit()" style="padding: 0.25rem; font-size: 0.85rem; border-radius:4px; border:1px solid #ddd;">
                                                <option value="Available" <?php echo $row['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                                                <option value="Unavailable" <?php echo $row['status'] == 'Unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                                                <option value="Maintenance" <?php echo $row['status'] == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="?delete=<?php echo $row['id']; ?>&csrf_token=<?php echo generate_csrf_token(); ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this asset?');">Delete</a>
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
