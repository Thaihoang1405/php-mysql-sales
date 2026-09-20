<?php

$pageTitle = 'Thêm nhà cung cấp';
require_once '/var/www/src/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $supplierName = trim($_POST['supplierName'] ?? '');
    $contactName  = trim($_POST['ContactName'] ?? '');
    $address      = trim($_POST['Address'] ?? '');

    if ($supplierName === '') {
        $error = 'Tên nhà cung cấp không được để trống.';
    } else {
        /*
         * Thêm dữ liệu vào bảng suppliers
         */
        $sql = "
            INSERT INTO suppliers (supplierName, ContactName, Address)
            VALUES (?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $supplierName, $contactName, $address);

        if ($stmt->execute()) {
            header('Location: /admin/suppliers/');
            exit;
        } else {
            $error = 'Không thể thêm nhà cung cấp.';
        }

        $stmt->close();
    }
}

require_once '/var/www/src/includes/admin/header.php';
require_once '/var/www/src/includes/admin/navbar.php';

?>

<div class="container mt-4">

    <h2 class="mb-4">Thêm nhà cung cấp</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <!-- Tên nhà cung cấp -->
        <div class="mb-3">
            <label for="supplierName" class="form-label">
                Tên nhà cung cấp <span class="text-danger">*</span>
            </label>
            <input
                type="text"
                class="form-control"
                id="supplierName"
                name="supplierName"
                required
                value="<?= htmlspecialchars($_POST['supplierName'] ?? '') ?>"
                placeholder="Nhập tên nhà cung cấp"
            >
        </div>

        <!-- Người liên hệ -->
        <div class="mb-3">
            <label for="contactName" class="form-label">
                Người liên hệ
            </label>
            <input
                type="text"
                class="form-control"
                id="contactName"
                name="ContactName"
                value="<?= htmlspecialchars($_POST['ContactName'] ?? '') ?>"
                placeholder="Nhập tên người liên hệ"
            >
        </div>

        <!-- Địa chỉ -->
        <div class="mb-3">
            <label for="address" class="form-label">
                Địa chỉ
            </label>
            <textarea
                class="form-control"
                id="address"
                name="Address"
                rows="3"
                placeholder="Nhập địa chỉ nhà cung cấp"
            ><?= htmlspecialchars($_POST['Address'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Lưu
        </button>

        <a href="/admin/suppliers/" class="btn btn-secondary">
            Hủy
        </a>

    </form>

</div>

<?php

require_once '/var/www/src/includes/admin/footer.php';
$conn->close();