<?php

class List_of_acquainted
{
private $id;
private $article_id;
private $user_name;
private $date_of_acquainted;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * @param mixed $article_id
     */
    public function setArticleId($article_id): void
    {
        $this->article_id = $article_id;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     */
    public function setUserName($user_name): void
    {
        $this->user_name = $user_name;
    }

    /**
     * @return mixed
     */
    public function getDateOfAcquainted()
    {
        return $this->date_of_acquainted;
    }

    /**
     * @param mixed $date_of_acquainted
     */
    public function setDateOfAcquainted($date_of_acquainted): void
    {
        $this->date_of_acquainted = $date_of_acquainted;
    }
}