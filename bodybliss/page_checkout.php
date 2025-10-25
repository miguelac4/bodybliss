<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout - BodyBliss</title>
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

<div class="checkout-container">
    <div class="checkout-card">
        <h2><span>💳</span> Cart Resume:</h2>
        <div id="cart-items" class="cart-items"></div>
        <div class="cart-total">
            <strong id="total-price">Total: 0.00€</strong>
        </div>
        <button id="checkout-btn" class="checkout-button">Complete Purchase</button>
    </div>

    <div id="payment-section" class="checkout-card" style="display:none;">
        <div class="payment-box" id="payment-box">
            <div class="payment-box-header">
                <div>
                    <strong>Credit Card</strong><br>
                    <small>Secure and encrypted</small>
                </div>
                <div class="card-icons">
                    <img src="imgs/credit_card/visa.png" alt="Visa">
                    <img src="imgs/credit_card/mastercard.png" alt="MasterCard">
                    <img src="imgs/credit_card/discover.png" alt="Discover">
                    <img src="imgs/credit_card/american_express.png" alt="Amex">
                </div>
            </div>
        </div>

        <div id="card-form" class="payment-form" style="display:none;">
            <input type="text" id="name_on_card" placeholder="Card Name" required>
            <input type="text" id="card_number" placeholder="Card Number" required>
            <input type="text" id="expirate_date" placeholder="MM/AA" required>
            <input type="text" id="cvc_cvv" placeholder="CVC/CVV" required>
            <button id="pay-btn" class="checkout-button">Pay</button>
        </div>
    </div>

</div>



<script>
    let products = [];
    let total = 0;

    function loadCart() {
        fetch('api/cart_products.php?user_id=<?php echo $user_id; ?>')
            .then(res => res.json())
            .then(data => {
                products = data;
                const container = document.getElementById('cart-items');
                container.innerHTML = "";
                total = 0;

                data.forEach(p => {
                    container.innerHTML += `<p>${p.name} x${p.quantity} = ${p.subtotal.toFixed(2)}€</p>`;
                    total += parseFloat(p.subtotal);
                });

                document.getElementById('total-price').innerText = "Total: " + total.toFixed(2) + "€";

                // Activate and desativate button with products
                const checkoutBtn = document.getElementById('checkout-btn');
                if (products.length === 0) {
                    checkoutBtn.disabled = true;
                } else {
                    checkoutBtn.disabled = false;
                }
            });
    }


    document.getElementById('checkout-btn').addEventListener('click', () => {
        document.getElementById('payment-section').style.display = 'block';
    });

    document.getElementById('pay-btn').addEventListener('click', () => {
        const payload = {
            card_number: document.getElementById('card_number').value.replace(/\s+/g, ''),
            expirate_date: document.getElementById('expirate_date').value,
            cvc_cvv: document.getElementById('cvc_cvv').value,
            name_on_card: document.getElementById('name_on_card').value,
            user_id: <?php echo $user_id; ?>
        };

        fetch('api/checkout.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.href = "page_checkout_success.php";
                } else if (res.type === "invalid_card") {
                    const modal = new bootstrap.Modal(document.getElementById('invalidCardModal'));
                    modal.show();
                } else {
                    alert("Error: " + (res.message || "Unexpected error."));
                }
            });

    });

    loadCart();

    document.getElementById('payment-box').addEventListener('click', () => {
        const form = document.getElementById('card-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });

    // Format card number
    document.getElementById('card_number').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '').substring(0, 16); // only numbers, max=16
        let formatted = value.replace(/(.{4})/g, '$1 ').trim(); // space between each 4 numbers
        e.target.value = formatted;
    });

    // Format date
    document.getElementById('expirate_date').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '').substring(0, 4); // only 4 numbers
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        e.target.value = value;
    });

    // Format CVC
    document.getElementById('cvc_cvv').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) {
            value = value.substring(0, 3);
        }
        e.target.value = value;
    });

</script>

</body>
</html>
