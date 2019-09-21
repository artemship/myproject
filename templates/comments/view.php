<?php if (!empty($comments)):
    foreach ($comments as $comment): ?>
        <div id="comment<?= $comment->getId(); ?>">
            <div><?= $comment->getAuthor()->getNickName(); ?></div>
            <div><?= $comment->getParsedText(); ?></div>
            <?php if (!empty($user) && $comment->isEditable($user)): ?>
                <a href="/articles/<?= $article->getId() ?>/comments/<?= $comment->getId() ?>/edit">Редактировать</a>
                | <a href="/articles/<?= $article->getId() ?>/comments/<?= $comment->getId() ?>/delete">Удалить</a>
            <?php endif; ?>
        </div>
        <br>
    <?php endforeach;
endif; ?>