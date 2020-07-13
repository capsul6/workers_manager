<?php

class Article
{
    private $id;
    private $file_location;
    private $posted_date;
    private $description;
    private $list_of_acquainted;

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
    public function getFileLocation()
    {
        return $this->file_location;
    }

    /**
     * @param mixed $file_location
     */
    public function setFileLocation($file_location): void
    {
        $this->file_location = $file_location;
    }

    /**
     * @return mixed
     */
    public function getPostedDate()
    {
        return $this->posted_date;
    }

    /**
     * @param mixed $posted_date
     */
    public function setPostedDate($posted_date): void
    {
        $this->posted_date = $posted_date;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getListOfAcquainted()
    {
        return $this->list_of_acquainted;
    }

    /**
     * @param mixed $list_of_acquainted
     */
    public function setListOfAcquainted($list_of_acquainted): void
    {
        $this->list_of_acquainted = $list_of_acquainted;
    }
}