<?php include __DIR__ . '/../header.php'; ?>
    <div class="article-title">
        <h1><?= $article->getName() ?></h1>
        <div>
            <?php if ($isEditable): ?>
                <a href="/articles/<?= $article->getId() ?>/edit">
                    <img src="/img/buttons/edit.svg" height="18px"
                         class="buttons"
                         title="Редактировать">
                </a>
                &nbsp;&nbsp;
                <a href="/articles/<?= $article->getId() ?>/delete">
                    <img src="/img/buttons/delete.svg" height="18px"
                         class="buttons"
                         title="Удалить">
                </a>
            <?php endif; ?>
        </div>
    </div>
    <p><?= $article->getParsedText() ?></p>
    <i>Автор статьи: <?= $article->getAuthor()->getNickname() ?></i>

    <hr>
    <div>Комментарии (<?= $amountComments ?>)
        <?php include __DIR__ . '/../comments/view.php'; ?>
        <br>
        <?php if (empty($user)): ?>
            Для добавления комментариев нужно <a href="/users/login">Войти на сайт</a>
        <?php else: ?>
            <form action="/articles/<?= $article->getId() ?>/comments" method="post">
                <label for="text">Добавить комментарий:</label><br>
                <textarea name="text" id="text" rows="5" cols="88"><?= $_POST['text'] ?? '' ?></textarea><br>
                <input type="submit" value="Отправить">
            </form>
        <?php endif; ?>

    </div>
<?php include __DIR__ . '/../footer.php'; ?>