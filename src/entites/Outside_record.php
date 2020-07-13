<?php
class Outside_record {

private $id;
private $date_come;
private $date_return;
private $outside_type;
private $worker_id;

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
    public function getDateCome()
    {
        return $this->date_come;
    }

    /**
     * @param mixed $date_come
     */
    public function setDateCome($date_come): void
    {
        $this->date_come = $date_come;
    }

    /**
     * @return mixed
     */
    public function getDateReturn()
    {
        return $this->date_return;
    }

    /**
     * @param mixed $date_return
     */
    public function setDateReturn($date_return): void
    {
        $this->date_return = $date_return;
    }

    /**
     * @return mixed
     */
    public function getOutsideType()
    {
        return $this->outside_type;
    }

    /**
     * @param mixed $outside_type
     */
    public function setOutsideType($outside_type): void
    {
        $this->outside_type = $outside_type;
    }

    /**
     * @return mixed
     */
    public function getWorkerId()
    {
        return $this->worker_id;
    }

    /**
     * @param mixed $worker_id
     */
    public function setWorkerId($worker_id): void
    {
        $this->worker_id = $worker_id;
    }


}