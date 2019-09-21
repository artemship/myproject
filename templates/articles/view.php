<?php include __DIR__ . '/../header.php'; ?>
    <h1><?= $article->getName() ?></h1>
    <p><?= $article->getParsedText() ?></p>
    <i>Автор статьи: <?= $article->getAuthor()->getNickname() ?></i>
<?php if ($isEditable): ?>
    <p><a href="/articles/<?= $article->getId() ?>/edit">Редактировать</a></p>
<?php endif; ?>
    <hr>
    <div>Комментарии (<?= $amountComments ?>)
        <br><br>
        <?php include __DIR__ . '/../comments/view.php'; ?>
        <?php if (empty($user)): ?>
            Для добавления комментариев нужно <a href="/users/login">Войти на сайт</a>
        <?php else: ?>
            <form action="/articles/<?= $article->getId() ?>/comments" method="post">
                <label for="text">Добавить комментарий:</label><br>
                <textarea name="text" id="text" rows="5" cols="80"><?= $_POST['text'] ?? '' ?></textarea><br>
                <input type="submit" value="Отправить">
            </form>
        <?php endif; ?>

    </div>
<?php include __DIR__ . '/../footer.php'; ?>