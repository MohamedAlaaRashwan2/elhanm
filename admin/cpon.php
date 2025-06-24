<?php
session_start();
$title = "Manage Coupons";

if (isset($_SESSION['Admin'])) {
    include "init.php";

    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    if ($action == 'Manage') {
        // Fetch all coupons
        $stmt = $con->prepare("SELECT * FROM coupons");
        $stmt->execute();
        $coupons = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Coupons</h1>
        <div class="container">
            <a href="?action=Add" class="btn btn-primary">Add New Coupon</a>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Code</td>
                        <td>Discount</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $coupon) { ?>
                        <tr>
                            <td>
                                <?php echo $coupon['id']; ?>
                            </td>
                            <td>
                                <?php echo $coupon['code']; ?>
                            </td>
                            <td>
                                <?php echo $coupon['discount']; ?>%
                            </td>
                            <td>
                                <a href="?action=Edit&id=<?php echo $coupon['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="?action=Delete&id=<?php echo $coupon['id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    } elseif ($action == 'Add') { ?>
        <h1 class="text-center">Add Coupon</h1>
        <div class="container">
            <form action="?action=Insert" method="POST">
                <input type="text" name="code" class="form-control" placeholder="Coupon Code" required>
                <input type="number" name="discount" class="form-control" placeholder="Discount Percentage" required>
                <input type="submit" value="Add" class="btn btn-success">
            </form>
        </div>
    <?php
    } elseif ($action == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = $_POST['code'];
            $discount = $_POST['discount'];
            $stmt = $con->prepare("INSERT INTO coupons (code, discount) VALUES (?, ?)");
            $stmt->execute([$code, $discount]);
            header("Location: ?action=Manage");
        }
    } elseif ($action == 'Edit') {
        $id = $_GET['id'];
        $stmt = $con->prepare("SELECT * FROM coupons WHERE id = ?");
        $stmt->execute([$id]);
        $coupon = $stmt->fetch();
        ?>
        <h1 class="text-center">Edit Coupon</h1>
        <div class="container">
            <form action="?action=Update" method="POST">
                <input type="hidden" name="id" value="<?php echo $coupon['id']; ?>">
                <input type="text" name="code" class="form-control" value="<?php echo $coupon['code']; ?>" required>
                <input type="number" name="discount" class="form-control" value="<?php echo $coupon['discount']; ?>" required>
                <input type="submit" value="Update" class="btn btn-primary">
            </form>
        </div>
    <?php
    } elseif ($action == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $code = $_POST['code'];
            $discount = $_POST['discount'];
            $stmt = $con->prepare("UPDATE coupons SET code = ?, discount = ? WHERE id = ?");
            $stmt->execute([$code, $discount, $id]);
            header("Location: ?action=Manage");
        }
    } elseif ($action == 'Delete') {
        $id = $_GET['id'];
        $stmt = $con->prepare("DELETE FROM coupons WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: ?action=Manage");
    }
} else {
    header('Location: index.php');
    exit();
}
include $ft . "footer.php";
?>