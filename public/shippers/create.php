<?php

$pageTitle = 'Thêm danh mục';
require_once '/var/www/src/config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $shipperName = trim($_POST['shipper_name'] ?? '');
    $phone = trim($_POST['Phone'] ?? ''); // Lấy giá trị Số điện thoại từ Form

    if ($shipperName === '') {
        $error = 'Tên nhân viên không được để trống.';
    } else {
        // Thêm cột Phone vào câu lệnh INSERT
        $sql = "
            INSERT INTO shippers (ShipperName, Phone)
            VALUES (?, ?)
        ";

        $stmt = $conn->prepare($sql);
        // Chuyển thành 'ss' (2 tham số kiểu chuỗi string)
        $stmt->bind_param('ss', $shipperName, $phone);

if ($stmt->execute()) {

    header('Location: /shippers/');
    exit;

} else {

    $error = 'Không thể thêm danh mục.';
}

$stmt->close();

    }
}

require_once '/var/www/src/includes/header.php';
require_once '/var/www/src/includes/navbar.php';

?>

<div class="container mt-4">

    <h2 class="mb-4">Thêm danh mục</h2>
    <?php if ($error !== ''): ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>

<?php endif; ?>

    <form method="post">

        <div class="mb-3">
            <label for="shipperName" class="form-label">
                Tên nhân viên
            </label>

            <input
                type="text"
                class="form-control"
                id="shipperName"
                name="shipper_name"
                required
            >
        </div>
        <div class="mb-3">
            <label for="Phone" class="form-label">
                Số điện thoại
            </label>

            <textarea
                class="form-control"
                id="Phone"
                name="Phone"
                rows="3"
                ><?= htmlspecialchars($_POST['Phone'] ?? '') ?></textarea>
        </div>


        <button type="submit" class="btn btn-primary">
            Lưu
        </button>

        <a href="/shippers/" class="btn btn-secondary">
            Hủy
        </a>

    </form>

</div>

<?php

require_once '/var/www/src/includes/footer.php';