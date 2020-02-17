<?php
namespace App\Models\Objects;

class UserSkillModel
{

    private $id;

    private $skill;
    
    private $user_id;
    
    function __construct($id, $skill, $user_id)
    {
        $this->id = $id;
        $this->skill = $skill;
        $this->user_id = $user_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSkill()
    {
        return $this->skill;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setSkill($skill)
    {
        $this->skill = $skill;
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function __toString()
    {
        return "UserSkill| ID: " . $this->id . " Skill: " . $this->skill . " User_id " . $this->user_id;
    }

}