<?php

$pageTitle = 'Thêm nhân viên';

require_once '/var/www/src/includes/header.php';
require_once '/var/www/src/includes/navbar.php';

?>

<div class="container mt-4">

    <h2 class="mb-4">Thêm nhân viên</h2>
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
            <label for="description" class="form-label">
                Mô tả
            </label>

            <textarea
                class="form-control"
                id="description"
                name="description"
                rows="3"
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            Lưu
        </button>

        <a href="/shipper/" class="btn btn-secondary">
            Hủy
        </a>

    </form>

</div>

<?php