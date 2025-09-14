<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['admin'])) {
    die("Access Denied. <a href='admin_login.php'>Login as Admin</a>");
}

// Approve or Reject Action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        mysqli_query($conn, "UPDATE users SET status='approved' WHERE id=$id");
    } elseif ($action === 'reject') {
        mysqli_query($conn, "UPDATE users SET status='rejected' WHERE id=$id");
    }
}

// Fetch Pending Users
$result = mysqli_query($conn, "SELECT * FROM users WHERE status='pending'");
?>

<h2>Admin Panel - Pending Approvals</h2>
<table border="1" cellpadding="10">
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Aadhar</th>
    <th>License File</th>
    <th>Action</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?= $row['name'] ?></td>
      <td><?= $row['email'] ?></td>
      <td><?= $row['aadhar'] ?></td>
      <td><a href="<?= $row['license_file'] ?>" target="_blank">View License</a></td>
      <td>
        <a href="admin_panel.php?action=approve&id=<?= $row['id'] ?>">✅ Approve</a> |
        <a href="admin_panel.php?action=reject&id=<?= $row['id'] ?>">❌ Reject</a>
      </td>
    </tr>
  <?php } ?>
</table>
