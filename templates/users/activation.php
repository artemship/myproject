<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <?php if (!empty($error)): ?>
            <h1>Не удалось активировать аккаунт</h1>
            <?= $error ?>
        <?php else: ?>
            <h1>Активация аккаунта прошла успешно!</h1>
        <?php endif; ?>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>