<!-- DIV to Show All Pictures -->
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            position: relative;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* Overlay with image and fade */
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

<div>
    <h2 style="text-align: center; margin-top: 20px;">Repository Images</h2>
    <?php
    require_once "db_init.php";
    $conn = db_connect();

    // Fetch all images from the repository table
    $sql = "SELECT file_name FROM repository ORDER BY upload_date DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        echo '<div class="gallery">';
        while ($row = $result->fetch_assoc()):
            echo '<div class="image-container">';
            echo '<img src="uploads_repository/' . htmlspecialchars($row['file_name']) . '" alt="Repository Image">';
            ?>
            <a href="uploads_repository/<?=$row['file_name']?>" download="<?=$row['file_name']?>">
                <img src="imgs/download_button.png" alt="Download Image" style="width:30px" >
                <?php
                $role = isset($_SESSION["role"]) ? $_SESSION["role"] : "guest";
                if($role === "admin"):
                ?>
                    <a href="delete_file.php?file=<?php echo urlencode($row['file_name']); ?>"
                       onclick="return confirm('Are you sure you want to delete this file?');">
                        Delete
                    </a>
                <?php endif;?>
            </a>

            <?php
            echo '</div>';
        endwhile;
        echo '</div>';
        else:
        echo "<p>No images uploaded yet.</p>";
    endif;
    ?>
</div>

<!-- DIV to submite more Pictures -->
<div>

    <form action="upload_repository.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="repository_pic" accept="image/*" required>
        <button type="submit" name="upload">Add Images</button>
    </form>

</div>