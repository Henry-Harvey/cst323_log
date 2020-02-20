<?php
namespace App\Models\Utility;

class ValidationRules
{

    public function getRegistrationRules()
    {
        $rules = [
            'username' => 'Required | Between:1,15',
            'password' => 'Required | Between:1,15',
            'firstname' => 'Required | Between:1,15',
            'lastname' => 'Required | Between:1,15',
            'location' => 'Required | Between:1,15',
            'summary' => 'Required | Between:1,15'
        ];
        return $rules;
    }

    public function getLoginRules()
    {
        $rules = [
            'username' => 'Required | Between:1,15',
            'password' => 'Required | Between:1,15'
        ];
        return $rules;
    }
    
    public function getProfileEditRules()
    {
        $rules = [
            'id' => 'Required',
            'firstname' => 'Required | Between:1,15',
            'lastname' => 'Required | Between:1,15',
            'location' => 'Required | Between:1,15',
            'summary' => 'Required | Between:1,15',
            'role' => 'Required',
            'credentials_id' => 'Required'
        ];
        return $rules;
    }
    
    public function getPostRules()
    {
        $rules = [
            'title' => 'Required | Between:1,15',
            'company' => 'Required | Between:1,15',
            'location' => 'Required | Between:1,15',
            'description' => 'Required | Between:1,15',
            'skill1' => 'Required | Between:1,15',
        ];
        return $rules;
    }
    
    public function getJobHistoryRules()
    {
        $rules = [
            'title' => 'Required | Between:1,50',
            'company' => 'Required | Between:1,50',
            'years' => 'Required | Between:1,50'
        ];
        return $rules;
    }
    
    public function getEducationRules()
    {
        $rules = [
            'school' => 'Required | Between:1,50',
            'degree' => 'Required | Between:1,50',
            'years' => 'Required | Between:1,50'
        ];
        return $rules;
    }
    
    public function getSkillRules()
    {
        $rules = [
            'skill' => 'Required | Between:1,50'
        ];
        return $rules;
    }
}

