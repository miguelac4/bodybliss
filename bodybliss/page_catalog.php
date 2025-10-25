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

<?php
require_once "db_init.php";
$conn = db_connect();
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;
?>
<div class="page-catalog">
<?php if (isset($_GET['q']) || isset($_GET['category']) || isset($_GET['min_price']) || isset($_GET['max_price'])): ?>
    <div class="container mt-4">
        <h3>Search results for: <em><?= htmlspecialchars($searchTerm) ?></em></h3>
        <h5>
            Results for:
            <?= htmlspecialchars($searchTerm) ?> |
            Category: <?= $category ?: "All" ?> |
            Price: <?= $minPrice ?>€ - <?= $maxPrice ?>€
        </h5>
        <div class="row">
            <?php
            $sql = "SELECT * FROM products WHERE 1";
            $params = [];
            $types = "";

            // Search term
            if (!empty($searchTerm)) {

                $sql .= " AND (name LIKE ? OR description LIKE ?)";
                $likeTerm = "%$searchTerm%";
                $params[] = $likeTerm;
                $params[] = $likeTerm;
                $types .= "ss";
            }

            // Category
            if (!empty($category)) {
                $sql .= " AND category = ?";
                $params[] = $category;
                $types .= "s";
            }

            // Min price
            if ($minPrice > 0) {
                $sql .= " AND price >= ?";
                $params[] = $minPrice;
                $types .= "d";
            }

            // Max price
            if ($maxPrice > 0) {
                $sql .= " AND price <= ?";
                $params[] = $maxPrice;
                $types .= "d";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();


            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="imgs/<?= $row['image'] ?>" class="catalog-img" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                                <p><strong><?= number_format($row['price'], 2) ?> €</strong></p>

<!--                                Cart Button-->
                                <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn page-catalog-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endwhile; else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Category Box 1 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="imgs/velas.jpg" class="catalog-img" alt="Category 1 Image">
                    <div class="card-body">
                        <h5 class="card-title">Artisanal Candles</h5>
                        <p class="card-text">Handcrafted with care, our artisanal candles combine natural ingredients and unique scents to create a warm, inviting atmosphere in any space.</p>
                        <a href="?pid=velas" class="btn btn-secondary">View</a>
                    </div>
                </div>
            </div>

            <!-- Category Box 2 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="imgs/massagens.jpg" class="catalog-img" alt="Category 2 Image">
                    <div class="card-body">
                        <h5 class="card-title">Therapeutic Massages</h5>
                        <p class="card-text">Relax, restore, and rejuvenate with our therapeutic massages designed to relieve tension, reduce stress, and promote overall well-being.</p>
                        <a href="?pid=massagens" class="btn btn-secondary">View</a>
                    </div>
                </div>
            </div>

            <!-- Category Box 3 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="imgs/hobbyists.webp" class="catalog-img" alt="Category 3 Image">
                    <div class="card-body">
                        <h5 class="card-title">Jesmonite Creations</h5>
                        <p class="card-text">Modern and eco-friendly, our Jesmonite creations blend style and durability—perfect for unique home décor and artistic expression.</p>
                        <a href="?pid=jesmonite" class="btn btn-secondary">View</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

<?php endif; ?>

