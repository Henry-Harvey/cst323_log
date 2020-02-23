<?php
namespace App\Models\Services\Business;

use Illuminate\Support\Facades\Log;
use App\Models\Services\Data\UserEducationDataService;
use App\Models\Utility\DatabaseModel;


class UserEducationBusinessService
{

    function createEducation($newUserEducation)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserEducation);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserEducationDataService($db);

        // flag is rows affected
        $flag = $ds->create($newUserEducation);

        $db = null;
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function getEducation($partialEducation)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialEducation);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserEducationDataService($db);
        
        // flag is UserEducation model or rows found
        $flag = $ds->read($partialEducation);
        
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
        
        // flag is array of UserEducation models
        $flag = $ds->readAllFor($user);       
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
        return $flag;
    }
    
    function editEducation($updatedEducation)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedEducation);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserEducationDataService($db);
        
        // flag is rows affected
        $flag = $ds->update($updatedEducation);           
        
        $db = null;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function remove($partialEducation)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialEducation);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserEducationDataService($db);
        
        // flag is rows affected
        $flag = $ds->delete($partialEducation);
        
        $db = null;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}

