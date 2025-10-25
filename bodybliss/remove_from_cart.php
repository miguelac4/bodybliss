    <?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }


    include_once("basic_functions.php");
    require_once "db_init.php";

    $conn = db_connect();
    e_RuntimeReport();

    $pid = "";
    var_dump($_POST);

    // verify if user is log in
    if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0){
        $user_id = $_SESSION['user_id'];  // get the current user
        $product_id = intval($_POST['product_id']);

        $action = $_POST['action_type'];

        $sql = "SELECT cart.id, cart.product_id, products.name, products.price, cart.quantity
                FROM cart
                JOIN products ON cart.product_id = products.id
                WHERE cart.user_id = ? AND cart.product_id = ?
";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
             // If action of model = remove one
            if ($action == "remove_one") {
                $new_quantity = $row["quantity"] - 1;

                $sql_update = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("iii", $new_quantity, $user_id, $product_id);
                $stmt_update->execute();

            // If action of model = remove all
            } elseif ($action == "remove_all"){

                $sql_update = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ii", $user_id, $product_id);
                $stmt_update->execute();
            }
        }

    }
    header("Location: index.php?pid=view_cart");
    exit();
