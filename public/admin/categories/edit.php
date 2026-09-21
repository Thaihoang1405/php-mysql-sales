<?php

$pageTitle = 'Sửa mặc hàng';

require_once '/var/www/src/config/database.php';

$error = '';

$categoryID = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($categoryID <= 0) {
    die('Mã mặc hàng không hợp lệ.');
}

/*
 * Đọc dữ liệu hiện tại của danh mục
 */
$sql = "
    SELECT
        CategoryID,
        CategoryName,
        Description
    FROM categories
    WHERE CategoryID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $categoryID);
$stmt->execute();

$result = $stmt->get_result();
$category = $result->fetch_assoc();

$stmt->close();

if (!$category) {
    die('Không tìm thấy mặc hàng.');
}


/*
 * Xử lý khi người dùng gửi form
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryName = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($categoryName === '') {

        $error = 'Tên mặc hàng không được để trống.';

    } else {

        $sql = "
            UPDATE categories
            SET
                CategoryName = ?,
                Description = ?
            WHERE CategoryID = ?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            'ssi',
            $categoryName,
            $description,
            $categoryID
        );

        if ($stmt->execute()) {

            header('Location: /admin/categories/');
            exit;

        } else {

            $error = 'Không thể cập nhật mặc hàng.';
        }

        $stmt->close();
    }
}

require_once '/var/www/src/includes/admin/header.php';
require_once '/var/www/src/includes/admin/navbar.php';

?>

<div class="container mt-4">

    <h2 class="mb-4">Sửa mặc hàng</h2>

    <?php if ($error !== ''): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="post">

        <div class="mb-3">

            <label class="form-label">
                Mã mặc hàng
            </label>

            <input
                type="text"
                class="form-control"
                value="<?= $category['CategoryID'] ?>"
                disabled
            >

        </div>

        <div class="mb-3">

            <label for="categoryName" class="form-label">
                Tên mặc hàng
            </label>

            <input
                type="text"
                class="form-control"
                id="categoryName"
                name="category_name"
                value="<?= htmlspecialchars(
                    $_POST['category_name']
                    ?? $category['CategoryName']
                ) ?>"
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
            ><?= htmlspecialchars(
                $_POST['description']
                ?? $category['Description']
                ?? ''
            ) ?></textarea>

        </div>

        <button type="submit" class="btn btn-warning">
            Cập nhật
        </button>

        <a href="/admin/categories/" class="btn btn-secondary">
            Hủy
        </a>

    </form>

</div>

<?php

require_once '/var/www/src/includes/admin/footer.php';

$conn->close();