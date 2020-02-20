<?php
namespace App\Models\Services\Business;

use App\Models\Services\Data\UserSkillDataService;
use App\Models\Utility\DatabaseModel;
use Illuminate\Support\Facades\Log;


class UserSkillBusinessService
{
    // use this one
    function create($newUserSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserSkill);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserSkillDataService($db);
        
        $flag = $ds->create($newUserSkill);
        
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
        
        $flag = $ds->readAllFor($user);
        
        if (is_int($flag)) {
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }
        
        $userSkill_array = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($userSkill_array));
        return $userSkill_array;
    }
}

