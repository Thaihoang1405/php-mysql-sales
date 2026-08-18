<?php

$pageTitle = 'Thêm danh mục';
require_once '/var/www/src/config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $shipperName = trim($_POST['shipper_name'] ?? '');


    if ($shipperName === '') {

        $error = 'Tên danh mục không được để trống.';

    } else {

        $sql = "
    INSERT INTO shippers
        (ShipperName)
    VALUES
        (?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    's',
    $shipperName
);

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
                Mô tả
            </label>

            <textarea
                class="form-control"
                id="Phone"
                name="Phone"
                rows="3"
                <?= htmlspecialchars($_POST['Phone'] ?? '') ?>
            ></textarea>
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