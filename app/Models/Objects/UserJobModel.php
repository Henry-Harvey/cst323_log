<?php
namespace App\Models\Objects;

class UserJobModel
{

    private $id;

    private $title;
    
    private $company;
    
    private $years;
    
    private $user_id;
    
    function __construct($id, $title, $company, $years, $user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->company = $company;
        $this->years = $years;
        $this->user_id = $user_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getYears()
    {
        return $this->years;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setYears($years)
    {
        $this->years = $years;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function __toString()
    {
        return "UserJob| ID: " . $this->id . " Title: " . $this->title . " Company: " . $this->company . " Years: " . $this->years . " User_id " . $this->user_id;
    }

}