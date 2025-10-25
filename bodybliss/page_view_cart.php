<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("basic_functions.php");
require_once "db_init.php";

$conn = db_connect();
e_RuntimeReport();

// Check if user is logged in
if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == 0) {
    header("Location: index.php?pid=login");
    exit();
}

$user_id = $_SESSION["user_id"];
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">🛒 Your Shopping Cart</h2>

    <?php
    $sql = "SELECT cart.id, cart.product_id, products.name, products.price, cart.quantity 
            FROM cart 
            JOIN products ON cart.product_id = products.id 
            WHERE cart.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;

    if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()):
            $subtotal = $row['price'] * $row['quantity'];
            $total += $subtotal;
            ?>
            <div class="cart-card mb-3 p-4 rounded shadow-sm d-flex justify-content-between align-items-center flex-wrap">
                <div class="cart-card-info">
                    <h5 class="mb-1"><?= htmlspecialchars($row['name']) ?></h5>
                    <p class="mb-1">Price: <?= number_format($row['price'], 2) ?> €</p>
                    <p class="mb-1">Quantity: <?= $row['quantity'] ?></p>
                    <p class="mb-0">Subtotal: <?= number_format($subtotal, 2) ?> €</p>
                </div>

                <div class="cart-card-actions mt-3 mt-md-0">
                    <?php if ($row['quantity'] > 1): ?>
                        <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-product-id="<?= $row['product_id'] ?>"
                        >
                            Remove
                        </button>
                    <?php else: ?>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                            <input type="hidden" name="action_type" value="remove_all">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="cart-summary-box mt-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-0">Total: <span><?= number_format($total, 2) ?> €</span></h4>
                <a href="index.php?pid=checkout" class="btn btn-grey btn-lg mt-3 mt-md-0">Proceed to Checkout</a>
            </div>
        </div>

    <?php else: ?>
        <div class="empty-cart-container text-center my-5">
            <div class="empty-cart-icon mb-4">
                <!-- Embedded cart icon (SVG) -->
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-cart-x text-muted" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 7A.5.5 0 0 1 13 13H4a.5.5 0 0 1-.49-.402L1.01 2H.5a.5.5 0 0 1-.5-.5Zm3.14 4 .94 4.5h8.28l1.2-5.5H3.14ZM5.5 14a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm7 1a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM8.354 4.646 9.707 6l-1.353 1.354a.5.5 0 1 0 .707.707L10.414 6.707l1.354 1.354a.5.5 0 0 0 .707-.707L11.121 6l1.354-1.354a.5.5 0 1 0-.707-.707L10.414 5.293 9.06 3.94a.5.5 0 1 0-.707.707Z"/>
                </svg>
            </div>
            <h4 class="fw-semibold">Your cart is empty</h4>
            <p class="text-muted">Looks like you haven't added anything yet.</p>
            <a href="index.php?pid=catalog" class="btn btn-back-catalog mt-3 px-4">
                <i class="bi bi-arrow-left me-2"></i> Back to Catalog
            </a>
        </div>


    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="remove_from_cart.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Remove item from cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you want to remove just one unit or remove the item completely?
                </div>
                <input type="hidden" name="product_id" id="modalProductId">
                <div class="modal-footer">
                    <button type="submit" name="action_type" value="remove_one" class="btn btn-warning">Remove 1</button>
                    <button type="submit" name="action_type" value="remove_all" class="btn btn-danger">Remove All</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-product-id');
        document.getElementById('modalProductId').value = productId;
    });
</script>
