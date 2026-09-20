<?php

$pageTitle = 'Sửa danh mục';

require_once '/var/www/src/config/database.php';

$error = '';

$shipperID = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($shipperID <= 0) {
    die('Mã đơn không hợp lệ.');
}

/*
 * Đọc dữ liệu hiện tại của danh mục
 */
$sql = "
    SELECT
        ShipperID,
        ShipperName,
        Phone
    FROM shippers
    WHERE ShipperID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $shipperID);
$stmt->execute();

$result = $stmt->get_result();
$shipper = $result->fetch_assoc();

$stmt->close();

if (!$shipper) {
    die('Không tìm thấy mã đơn.');
}


/*
 * Xử lý khi người dùng gửi form
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $shipperName = trim($_POST['shipper_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($shipperName === '') {

        $error = 'Tên nhân viên không được để trống.';

    } else {

        $sql = "
            UPDATE shippers
            SET
                ShipperName = ?,
                Phone = ?
            WHERE ShipperID = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            'ssi',
            $shipperName,
            $phone,
            $shipperID
        );

        if ($stmt->execute()) {

            header('Location: /admin/shippers/');
            exit;

        } else {

            $error = 'Không thể cập nhật danh mục.';
        }

        $stmt->close();
    }
}

require_once '/var/www/src/includes/admin/header.php';
require_once '/var/www/src/includes/navbar.php';

?>

<div class="container mt-4">

    <h2 class="mb-4">Sửa danh mục</h2>

    <?php if ($error !== ''): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="post">

        <div class="mb-3">

            <label class="form-label">
                Số điện thoại
            </label>
            

            <input
                type="text"
                class="form-control"
                id="phone"
                name="phone"
                value="<?= htmlspecialchars($_POST['phone'] ?? $shipper['Phone'] ?? '') ?>"
            >

        </div>

        <div class="mb-3">

            <label for="shipperName" class="form-label">
                Tên nhân viên
            </label>

            <input
                type="text"
                class="form-control"
                id="shipperName"
                name="shipper_name"
                value="<?= htmlspecialchars(
                    $_POST['shipper_name']
                    ?? $shipper['ShipperName']
                ) ?>"
                required
            >

        </div>

        <button type="submit" class="btn btn-warning">
            Cập nhật
        </button>

        <a href="/admin/shippers/" class="btn btn-secondary">
            Hủy
        </a>

    </form>

</div>

<?php

require_once '/var/www/src/includes/footer.php';

$conn->close();