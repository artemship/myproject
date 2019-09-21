<?php

namespace MyProject\Models\Comments;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;

class Comment extends ActiveRecordEntity
{
    /** @var int */
    protected $userId;
    /** @var int */
    protected $articleId;
    /** @var string */
    protected $text;
    /** @var string */
    protected $createdAt;

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->userId);
    }

    protected static function getTableName(): string
    {
        return 'comments';
    }

    public function isEditable(User $user): bool
    {
        return $user->isAdmin() || $this->getUserId() === $user->getId();
    }

    public static function create(int $userId, int $articleId, array $fields): Comment
    {
//        if (empty($fields['text'])) {
//            throw new InvalidArgumentException('Не передан текст статьи');
//        }

        $comment = new Comment();
        $comment->setUserId($userId);
        $comment->setArticleId($articleId);
        $comment->setText($fields['text']);
        $comment->save();

        return $comment;
    }

    public function update(string $text): Comment
    {
        $this->setText($text);
        $this->save();

        return $this;
    }

    public function getParsedText(): string
    {
        $parser = new \Parsedown();
        return $parser->text($this->getText());
    }


}