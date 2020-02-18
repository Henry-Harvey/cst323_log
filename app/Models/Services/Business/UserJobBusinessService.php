<?php
namespace App\Models\Services\Business;

use Illuminate\Support\Facades\Log;
use App\Models\Utility\DatabaseModel;
use App\Models\Services\Data\UserJobDataService;

class UserJobBusinessService
{
    // use this one
    function create($newUserJob)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserJob);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserJobDataService($db);

        $flag = $ds->create($newUserJob);

        $db = null;
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function getAllJobsForUser($user)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
       
        $ds = new UserJobDataService($db);
        
        $flag = $ds->readAllFor($user);
        
        if (is_int($flag)) {
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }
        
        $userJob_array = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($userJob_array));
        return $userJob_array;
    }
}