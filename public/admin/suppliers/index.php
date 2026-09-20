<?php

$pageTitle = 'Danh sách nhà cung cấp';

require_once '/var/www/src/config/database.php';

/*
 * Lấy danh sách nhà cung cấp (Đã thêm dấu phẩy sau ContactName)
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
    ORDER BY supplierID
";

$result = $conn->query($sql);

require_once '/var/www/src/includes/admin/header.php';
require_once '/var/www/src/includes/admin/navbar.php';

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Danh sách nhà cung cấp</h2>

        <a href="/admin/suppliers/create.php" class="btn btn-primary">
            Thêm nhà cung cấp
        </a>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên nhà cung cấp</th>
                    <th>Địa chỉ</th>
                    <th>Người liên hệ</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($supplier = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($supplier['supplierID'] ?? $supplier['SupplierID'] ?? '') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($supplier['supplierName'] ?? $supplier['SupplierName'] ?? '') ?>
                        </td>

                        <!-- Sửa Description thành Address -->
                        <td>
                            <?= htmlspecialchars($supplier['Address'] ?? $supplier['address'] ?? '') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($supplier['ContactName'] ?? $supplier['contactName'] ?? '') ?>
                        </td>

                        <td>

                            <a href="/admin/suppliers/edit.php?id=<?= $supplier['supplierID'] ?? $supplier['SupplierID'] ?>" class="btn btn-sm btn-warning">
                                Sửa
                            </a>

                            <form
                                action="/admin/suppliers/delete.php"
                                method="post"
                                class="d-inline"
                                onsubmit="return confirm('Bạn có chắc muốn xóa nhà cung cấp này?');"
                            >
                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $supplier['supplierID'] ?? $supplier['SupplierID'] ?>"
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
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Chưa có dữ liệu nhà cung cấp.</td>
                </tr>
            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

require_once '/var/www/src/includes/admin/footer.php';

$conn->close();