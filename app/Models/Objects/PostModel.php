<?php
namespace App\Models\Objects;
//This model is for containing the information for people to log in to the site

// product class
class PostModel
{

    private $id;

    private $title;

    private $company;

    private $location;
    
    private $description;
    
    // array of objects not in constructor
    private $postSkill_array;
    
    function __construct($id, $title, $company, $location, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->company = $company;
        $this->location = $location;
        $this->description = $description;
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

    public function getLocation()
    {
        return $this->location;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPostSkill_array()
    {
        return $this->postSkill_array;
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

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setPostSkill_array($postSkill_array)
    {
        $this->postSkill_array = $postSkill_array;
    }

    public function __toString()
    {
        if($this->postSkill_array != null){
            return "Post| ID: " . $this->id . " Title: " . $this->title . " Company: " . $this->company . " Location: " . $this->location . " Description: " . $this->description . " PostSkill_array " . implode("||", $this->postSkill_array);;            
        }
        return "Post| ID: " . $this->id . " Title: " . $this->title . " Company: " . $this->company . " Location: " . $this->location . " Description: " . $this->description;
    }

}