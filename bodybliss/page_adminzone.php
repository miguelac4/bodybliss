<h2 style="text-align: center; margin-top: 20px;">ADMIN ZONE</h2>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            position: relative;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Overlay com imagem e fade à esquerda */
        body::after {
            content: "";
            position: fixed;
            top: 0;
            right: 0;
            width: 50vw;
            height: 100vh;
            background: linear-gradient(to left, rgba(255,255,255,0) 0%, rgba(245,245,245,1) 70%),
            url('imgs/background_profile.jpg') no-repeat center center;
            background-size: cover;
            z-index: -1;
        }
    </style>
</head>
<?php
require_once "db_init.php";
$conn = db_connect();
e_RuntimeReport();

// Fetch users with role 'client' or 'vip'
$sql = "SELECT id, name, profile_pic FROM users WHERE role IN ('client', 'vip')";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table border="1" cellpadding="10" style="width: 80%; text-align: center; margin: 30px auto; border-collapse: collapse;">';
    echo '<thead style="background-color: #f2f2f2;">';
    echo '<tr><th>Name</th><th>Profile Picture</th><th>Action</th></tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';

        // Display Name
        echo '<td style="font-weight: bold;">' . htmlspecialchars($row['name']) . '</td>';

        // Display Profile Picture (or default)
        $profilePic = !empty($row['profile_pic']) ? $row['profile_pic'] : 'nullprofile.jpg';
        echo '<td>';
        echo '<img src="uploads_profile/' . htmlspecialchars($profilePic) . '" alt="Profile Pic" width="80" style="border-radius: 10px;">';
        echo '</td>';

        // Delete Button (calls admin_delete_profile_pic.php)
        echo '<td>';
        echo '<form action="admin_delete_profile_pic.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this profile picture?\');">';
        echo '<input type="hidden" name="user_id" value="' . (int)$row['id'] . '">';
        echo '<button type="submit" style="background-color: #840606; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">❌ Delete</button>';
        echo '</form>';
        echo '</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p style="text-align: center; margin-top: 20px;">No users found.</p>';
}
?>
