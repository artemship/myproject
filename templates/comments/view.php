<?php if (!empty($comments)):
    foreach ($comments as $comment): ?>
        <div id="comment<?= $comment->getId(); ?>" class="comment">
            <img src="/img/avatars/default.svg" class="comment-avatar">
            <div class="comment-body">
                <div class="comment-body-title">
                    <?= $comment->getAuthor()->getNickName(); ?>
                    <?php if (!empty($user) && $comment->isEditable($user)): ?>
                        <div>
                            <a href="/articles/<?= $article->getId() ?>/comments/<?= $comment->getId() ?>/edit">
                                <img src="/img/buttons/edit.svg" height="18px"
                                     class="buttons"
                                     title="Редактировать">
                            </a>
                            &nbsp;&nbsp;
                            <a href="/articles/<?= $article->getId() ?>/comments/<?= $comment->getId() ?>/delete">
                                <img src="/img/buttons/delete.svg" height="18px"
                                     class="buttons"
                                     title="Удалить">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?= $comment->getParsedText(); ?>
            </div>
        </div>

    <?php endforeach;
endif; ?>