<?php

$pageTitle = 'Sửa nhà cung cấp';

require_once '/var/www/src/config/database.php';

$error = '';

$supplierID = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($supplierID <= 0) {
    die('Mã nhà cung cấp không hợp lệ.');
}

/*
 * 1. Đọc dữ liệu hiện tại từ Database
 */
$sql = "
    SELECT
        supplierID,
        supplierName,
        ContactName,
        Address,
        City,
        PostalCode,
        Country,
        Phone
    FROM suppliers
    WHERE supplierID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $supplierID);
$stmt->execute();

$result = $stmt->get_result();
$supplier = $result->fetch_assoc();
$stmt->close();

if (!$supplier) {
    die('Không tìm thấy nhà cung cấp.');
}

/*
 * 2. Xử lý khi người dùng bấm nút Cập nhật (POST)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lấy dữ liệu từ Form gửi lên
    $supplierName = trim($_POST['supplierName'] ?? '');
    $contactName  = trim($_POST['ContactName'] ?? '');
    $address      = trim($_POST['Address'] ?? '');
    
    // Giữ nguyên các giá trị cũ nếu không sửa
    $city         = $supplier['City'] ?? '';
    $postalCode   = $supplier['PostalCode'] ?? '';
    $country      = $supplier['Country'] ?? '';
    $phone        = $supplier['Phone'] ?? '';

    if ($supplierName === '') {
        $error = 'Tên nhà cung cấp không được để trống.';
    } else {
        $sql = "
            UPDATE suppliers
            SET
                supplierName = ?,
                ContactName = ?,
                Address = ?,
                City = ?,
                PostalCode = ?,
                Country = ?,
                Phone = ?
            WHERE supplierID = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sssssssi',
            $supplierName,
            $contactName,
            $address,
            $city,
            $postalCode,
            $country,
            $phone,
            $supplierID
        );

        if ($stmt->execute()) {
            header('Location: /admin/suppliers/');
            exit;
        } else {
            $error = 'Không thể cập nhật nhà cung cấp.';
        }

        $stmt->close();
    }
}

require_once '/var/www/src/includes/admin/header.php';
require_once '/var/www/src/includes/admin/navbar.php';

?>

<div class="container mt-4">

    <h2 class="mb-4">Sửa nhà cung cấp</h2>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <!-- Mã nhà cung cấp -->
        <div class="mb-3">
            <label class="form-label">Mã nhà cung cấp</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($supplier['supplierID']) ?>" disabled>
        </div>

        <!-- Tên nhà cung cấp -->
        <div class="mb-3">
            <label class="form-label">Tên nhà cung cấp <span class="text-danger">*</span></label>
            <input type="text" name="supplierName" class="form-control" value="<?= htmlspecialchars($_POST['supplierName'] ?? $supplier['supplierName'] ?? '') ?>">
        </div>

        <!-- Người liên hệ -->
        <div class="mb-3">
            <label class="form-label">Người liên hệ</label>
            <input type="text" name="ContactName" class="form-control" value="<?= htmlspecialchars($_POST['ContactName'] ?? $supplier['ContactName'] ?? '') ?>">
        </div>

        <!-- Địa chỉ -->
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <textarea name="Address" class="form-control" rows="3"><?= htmlspecialchars($_POST['Address'] ?? $supplier['Address'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-warning">
            Cập nhật
        </button>

        <a href="/admin/suppliers/" class="btn btn-secondary">
            Hủy
        </a>

    </form>

</div>

<?php

require_once '/var/www/src/includes/admin/footer.php';
$conn->close();