<header class="sticky-header">
    <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php"><img class="logo" src="imgs/logo.jpg" alt="logo" height="10"></a>

            <!-- Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Categories Dropdown -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <?php
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        include_once("basic_functions.php");
                        require_once "db_init.php";
                        $conn = db_connect();
                        e_RuntimeReport();

                        // Define variable
                        $user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 0;
                        //$pid = isset($_GET["pid"]) ? $_GET["pid"] : null;



                        ?>


                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownCategories">
                            <li><a class="dropdown-item" href="?pid=velas">Velas Artesanais</a></li>
                            <li><a class="dropdown-item" href="?pid=massagens">Massagens Terapeuticas</a></li>
                            <li><a class="dropdown-item" href="?pid=jesmonite">Criações Artesanais em Jesmonite</a></li>
                        </ul>
                    </li>
                </ul>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home <span</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?pid=about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?pid=catalog">Catalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?pid=contact">Contact</a>
                        </li>
                        <?php
                        $role = isset($_SESSION["role"]) ? $_SESSION["role"] : "guest";

                        if ($role === "vip" || $role === "admin"):
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?pid=repository">Repository (VIP)</a>
                            </li>
                        <?php endif; ?>
                        <?php
                        $role = isset($_SESSION["role"]) ? $_SESSION["role"] : "guest";

                        if ($role === "admin"):
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?pid=adminzone">Admin Zone⚙️</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- SEARCH BAR + FILTERS TOGETHER -->
                <form id="searchForm" method="GET" action="index.php" class="search-bar-wrapper position-relative">
                    <input type="hidden" name="pid" value="catalog">

                    <!-- Search bar -->
                    <div class="search-bar-form">
                        <input
                                type="text"
                                class="search-input"
                                name="q"
                                placeholder="Search for products..."
                                aria-label="Search"
                                id="searchInput"
                                autocomplete="off"
                        >
                        <button type="submit" class="search-btn" id="searchBtn" disabled>
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Filter dropdown -->
                    <div class="dropdown-menu p-3 shadow-sm search-dropdown" id="searchFilters">
                        <div class="mb-2">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All</option>
                                <option value="velas">Candles</option>
                                <option value="massagens">Massages</option>
                                <option value="jesmonite">Jesmonite</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Min Price (€)</label>
                            <input type="number" step="0.01" name="min_price" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Max Price (€)</label>
                            <input type="number" step="0.01" name="max_price" class="form-control">
                        </div>
                    </div>
                </form>

<!--                Script to disable search without writing or filtering-->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const input = document.getElementById("searchInput");
                        const category = document.querySelector("select[name='category']");
                        const min = document.querySelector("input[name='min_price']");
                        const max = document.querySelector("input[name='max_price']");
                        const searchBtn = document.getElementById("searchBtn");

                        function validateFields() {
                            const hasText = input.value.trim() !== "";
                            const hasCategory = category.value.trim() !== "";
                            const hasMin = min.value.trim() !== "";
                            const hasMax = max.value.trim() !== "";

                            // Enable if at least one field has value
                            searchBtn.disabled = !(hasText || hasCategory || hasMin || hasMax);
                        }

                        input.addEventListener("input", validateFields);
                        category.addEventListener("change", validateFields);
                        min.addEventListener("input", validateFields);
                        max.addEventListener("input", validateFields);

                        // Initial state check
                        validateFields();
                    });
                </script>

<!--                Script to cart panel-->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const cartIcon = document.getElementById("cartIcon");
                        const cartPanel = document.getElementById("cartPanel");

                        // Show panel in hover
                        cartIcon.addEventListener("mouseenter", () => {
                            cartPanel.classList.add("show");
                        });

                        // Hide when mouse is not in painel or icon
                        [cartIcon, cartPanel].forEach(el => {
                            el.addEventListener("mouseleave", () => {
                                setTimeout(() => {
                                    if (!cartPanel.matches(':hover') && !cartIcon.matches(':hover')) {
                                        cartPanel.classList.remove("show");
                                    }
                                }, 100);
                            });
                        });
                    });
                </script>



                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const searchInput = document.getElementById("searchInput");
                        const searchDropdown = document.getElementById("searchFilters");

                        searchInput.addEventListener("focus", () => {
                            searchDropdown.style.display = "block";
                        });

                        document.addEventListener("click", (e) => {
                            if (!e.target.closest(".search-bar-wrapper")) {
                                searchDropdown.style.display = "none";
                            }
                        });
                    });
                </script>
                <div class="d-flex align-items-center">
                    <!-- Profile Button -->
                    <?php if($user_id === 0):?>
                        <a href="?pid=login" class="header-icon bi-box-arrow-left"></a>

                    <?php
                    else:
                        ?>
                        <a href="?pid=account" class="header-icon bi-person-square"></a>
                        <a href="logout.php" class="header-icon bi-box-arrow-right"></a>
                        <div class="dropdown d-flex align-items-center">
                            <a href="#" class="bi bi-basket-fill header-icon dropdown-toggle" id="cartIcon"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>

                            <div id="cartPanel" class="cart-floating-panel">
                                <?php
                                $total = 0;

                                if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] != 0) {
                                    $user_id = $_SESSION["user_id"];

                                    // Fetch products in the cart
                                    $sql = "SELECT products.name, products.price, cart.quantity 
                    FROM cart 
                    JOIN products ON cart.product_id = products.id 
                    WHERE cart.user_id = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $subtotal = $row['price'] * $row['quantity'];
                                            $total += $subtotal;

                                            echo '<div class="d-flex justify-content-between align-items-center mb-2">';
                                            echo '<div>';
                                            echo '<strong>' . htmlspecialchars($row['name']) . '</strong><br>';
                                            echo '<small>Qty: ' . $row['quantity'] . ' | Price: ' . $row['price'] . '€</small>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p class="text-center">Your cart is empty!</p>';
                                    }
                                } else {
                                    echo '<p class="text-center">Login to view cart!</p>';
                                }
                                ?>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-center"><strong>Total: <?php echo number_format($total, 2); ?> €</strong></a>


                                <div class="dropdown-divider"></div>

                                <div class="cart-actions mt-3 text-center">
                                    <a href="index.php?pid=view_cart" class="cart-btn">🛒 View Cart</a>
                                    <a href="index.php?pid=checkout" class="cart-btn btn-checkout">💳 Checkout</a>
                                </div>
                            </div>
                        </div>

                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </nav>

</header>

<!--        <div class="topicons">-->
<!--            <a href="https://www.facebook.com/"><i class="fa-brands fa-square-facebook"></i></a>-->
<!--            <a href="https://www.instagram.com/"><i class="fa-brands fa-square-instagram"></i></a>-->
<!--            <a href="https://twitter.com/"><i class="fa-brands fa-square-x-twitter"></i></a>-->
<!--            <a href="https://www.linkedin.com/"><i class="fa-brands fa-linkedin"></i></a>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
