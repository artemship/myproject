<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;

class CommentsController extends AbstractController
{
    public function add(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST['text'])) {
            $comment = Comment::create($this->user->getId(), $articleId, $_POST);
            header('Location: /articles/' . $articleId . '#comment' . $comment->getId(), true, 302);
            exit();
        }

        header('Location: /articles/' . $articleId, true, 302);
        exit();
    }

    public function edit(int $articleId, int $commentId): void
    {
        $article = Article::getById($articleId);
        $comment = Comment::getById($commentId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($comment === null) {
            throw new NotFoundException();
        }

//        Обязательная проверка на принадлежность комментария к статье
        if ($comment->getArticleId() !== $articleId) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin() && ($this->user->getId() !== $comment->getAuthor()->getId())) {
            throw new ForbiddenException('Редактировать комментарии могут только их авторы или администраторы');
        }

        if (!empty($_POST['text'])) {
            $comment->update($_POST['text']);
            header('Location: /articles/' . $articleId . '#comment' . $commentId, true, 302);
            exit();
        }
        $this->view->renderHtml('comments/edit.php', [
            'article' => $article,
            'comment' => $comment
        ]);
    }

    public function delete(int $articleId, int $commentId): void
    {
        $article = Article::getById($articleId);
        $comment = Comment::getById($commentId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($comment === null) {
            throw new NotFoundException();
        }

//        Обязательная проверка на принадлежность комментария к статье
        if ($comment->getArticleId() !== $articleId) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin() && ($this->user->getId() !== $comment->getAuthor()->getId())) {
            throw new ForbiddenException('Удалять комментарии могут только их авторы или администраторы');
        }

        $comment->delete();
        header('Location: /articles/' . $articleId, true, 302);
        exit();
    }


}