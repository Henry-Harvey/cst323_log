<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserSkillDataService;
use App\Models\Utility\DatabaseModel;
use Illuminate\Support\Facades\Log;


class UserSkillBusinessService
{
    function createSkill($newUserSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserSkill);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserSkillDataService($db);
        
        // flag is rows affected
        $flag = $ds->create($newUserSkill);
        
        $db = null;
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function getSkill($partialSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialSkill);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserSkillDataService($db);
        
        // flag is UserJob model or rows found
        $flag = $ds->read($partialSkill);
        
        $db = null;
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function getAllSkillsForUser($user)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserSkillDataService($db);
        
        // flag is array of UserSkill models
        $flag = $ds->readAllFor($user);
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
        return $flag;
    }
    
    function editSkill($updatedSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedSkill);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserSkillDataService($db);
        
        // flag is rows affected
        $flag = $ds->update($updatedSkill);
        
        $db = null;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function remove($partialSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialSkill);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserSkillDataService($db);
        
        // flag is rows affected
        $flag = $ds->delete($partialSkill);
        
        $db = null;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}

