<?php

$pageTitle = 'Quản lý nhân viên giao hàng';

require_once '/var/www/src/config/database.php';

$sql = "
    SELECT
        ShipperID,
        ShipperName,
        Phone

    FROM shippers
    ORDER BY ShipperID
";

$result = $conn->query($sql);

require_once '/var/www/src/includes/header.php';
require_once '/var/www/src/includes/navbar.php';

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Quản lý nhân viên</h2>

        <a href="#" class="btn btn-primary">
            Thêm nhân viên mới
        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>Mã nhân viên</th>
                    <th>Tên nhân viên</th>
                    <th>số điện thoại</th>
                    <th>Chỉnh sửa</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($shipper = $result->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?= $shipper['ShipperID'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($shipper['ShipperName']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($shipper['Phone'] ?? '') ?>
                    </td>

                    <td>

                        <a href="#" class="btn btn-sm btn-warning">
                            Sửa
                        </a>

                        <form
    action="/shippers/delete.php"
    method="post"
    class="d-inline"
    onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');"
>
    <input
        type="hidden"
        name="id"
        value="<?= $shipper['ShipperID'] ?>"
    >

    <button
        type="submit"
        class="btn btn-sm btn-danger"
    >
        Xóa
    </button>
</form>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

require_once '/var/www/src/includes/footer.php';

$conn->close();