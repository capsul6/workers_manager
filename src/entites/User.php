<?php


class User
{
    private $id;
    private $login;
    private $password;
    private $email;
    private $name;
    private $surname;
    private $image;
    private $tellNumber;
    private $position;
    private $rank;
    private $outside_id;
    private $dateOfBirth;
    private $image_file_name;
    private $permission_type;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getTellNumber()
    {
        return $this->tellNumber;
    }

    /**
     * @param mixed $tellNumber
     */
    public function setTellNumber($tellNumber): void
    {
        $this->tellNumber = $tellNumber;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param mixed $rank
     */
    public function setRank($rank): void
    {
        $this->rank = $rank;
    }

    /**
     * @return mixed
     */
    public function getOutsideId()
    {
        return $this->outside_id;
    }

    /**
     * @param mixed $outside_id
     */
    public function setOutsideId($outside_id): void
    {
        $this->outside_id = $outside_id;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getImageFileName()
    {
        return $this->image_file_name;
    }

    /**
     * @param mixed $image_file_name
     */
    public function setImageFileName($image_file_name): void
    {
        $this->image_file_name = $image_file_name;
    }

    /**
     * @return mixed
     */
    public function getPermissionType()
    {
        return $this->permission_type;
    }

    /**
     * @param mixed $permission_type
     */
    public function setPermissionType($permission_type): void
    {
        $this->permission_type = $permission_type;
    }

}