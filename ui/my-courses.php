<?php
// Trang khóa học của tôi

$page_title = "Khóa học của tôi";

require __DIR__ . '/includes/header.php';
?>

<section class="oe-container">

    <h1>Khóa học của tôi</h1>

    <p>
        Theo dõi tiến trình học tập và tiếp tục các khóa học đang học.
    </p>

    <div class="oe-grid">

        <div class="oe-card">

            <h3>English Basic</h3>

            <p>Tiến trình học tập</p>

            <progress value="70" max="100"></progress>

            <p><strong>70%</strong> hoàn thành</p>

            <a href="unit.php?unit_id=1" class="oe-btn">
                Tiếp tục học
            </a>

        </div>

        <div class="oe-card">

            <h3>English Communication</h3>

            <p>Tiến trình học tập</p>

            <progress value="45" max="100"></progress>

            <p><strong>45%</strong> hoàn thành</p>

            <a href="unit.php?unit_id=2" class="oe-btn">
                Tiếp tục học
            </a>

        </div>

        <div class="oe-card">

            <h3>English Grammar</h3>

            <p>Tiến trình học tập</p>

            <progress value="20" max="100"></progress>

            <p><strong>20%</strong> hoàn thành</p>

            <a href="unit.php?unit_id=3" class="oe-btn">
                Tiếp tục học
            </a>

        </div>

    </div>

</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
