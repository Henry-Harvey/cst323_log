<?php
namespace App\Models\Services\Business;

use Illuminate\Support\Facades\Log;
use App\Models\Services\Data\UserEducationDataService;
use App\Models\Utility\DatabaseModel;


class UserEducationBusinessService
{
     // use this one
    function create($newUserEducation)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserEducation);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserEducationDataService($db);

        $flag = $ds->create($newUserEducation);

        $db = null;
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function getAllEducationForUser($user)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
       
        $ds = new UserEducationDataService($db);
        
        $flag = $ds->readAllFor($user);
        
        if (is_int($flag)) {
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }
        
        $userEducation_array = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($userEducation_array));
        return $userEducation_array;
    }
}

