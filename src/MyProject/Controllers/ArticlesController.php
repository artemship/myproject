<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;

class ArticlesController extends AbstractController
{

    public function view(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        $reflector = new \ReflectionObject($article);
        $properties = $reflector->getProperties();
        $propertiesNames = [];
        foreach ($properties as $property) {
            $propertiesNames[] = $property->getName();
        }

        if ($this->user === null) {
            $isEditable = false;
        } else {
            $isEditable = ($this->user->isAdmin() || ($this->user->getId() === $article->getAuthorId()));
        }

        $comments = Comment::findAllByColumn('article_id', $articleId);
        $amountComments = !empty($comments) ? count($comments) : 0;

        $this->view->renderHtml('articles/view.php', [
            'article' => $article,
            'isEditable' => $isEditable,
            'amountComments' => $amountComments,
            'comments' => $comments
        ]);
    }

    public function create(): void
    {
        $author = User::getById(1);
        $article = new Article();
        $article->setAuthor($author);
        $article->setName('Новое название статьи');
        $article->setText('Новый текст статьи');
        $article->save();
        $article->delete();
        var_dump($article);
    }

    public function add(): void
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin() && !$this->user->isConfirmed()) {
            throw new ForbiddenException('Статьи могут добавлять только администраторы или подтвержденные пользователи');
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit;
        }

        $this->view->renderHtml('articles/add.php');
    }

    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        $isEditable = ($this->user->isAdmin() || ($this->user->getId() === $article->getAuthorId()));

        if (!$isEditable) {
            throw new ForbiddenException('Статьи могут редактировать только авторы или администраторы ');
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', [
                    'error' => $e->getMessage(),
                    'article' => $article
                ]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function delete(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        $article->delete();
        var_dump($article);
    }

}