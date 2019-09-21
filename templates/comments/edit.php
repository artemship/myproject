<?php include __DIR__ . '/../header.php'; ?>
    <form action="/articles/<?= $article->getId() ?>/comments/<?= $comment->getId() ?>/edit" method="post">
        <label for="text">Изменить комментарий:</label><br>
        <textarea name="text" id="text" rows="5" cols="80"><?= $comment->getText() ?? '' ?></textarea><br>
        <input type="submit" value="Изменить">
    </form>
<?php include __DIR__ . '/../footer.php'; ?>