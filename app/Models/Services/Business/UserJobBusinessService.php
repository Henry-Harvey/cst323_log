<?php
namespace App\Models\Services\Business;

use Illuminate\Support\Facades\Log;
use App\Models\Utility\DatabaseModel;
use App\Models\Services\Data\UserJobDataService;

class UserJobBusinessService
{

    function createJob($newUserJob)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newUserJob);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $ds = new UserJobDataService($db);

        // flag is rows affected
        $flag = $ds->create($newUserJob);

        $db = null;
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function getJob($partialJob)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialJob);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserJobDataService($db);
        
        // flag is UserJob model or rows found
        $flag = $ds->read($partialJob);
        
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
        
        // flag is array of UserJob models
        $flag = $ds->readAllFor($user);            
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
        return $flag;
    }
    
    function editJob($updatedJob)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedJob);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserJobDataService($db);
        
        // flag is rows affected
        $flag = $ds->update($updatedJob);
        
        $db = null;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
    
    function remove($partialJob)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialJob);
        
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $ds = new UserJobDataService($db);
        
        // flag is rows affected
        $flag = $ds->delete($partialJob);
        
        $db = null;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $flag;
    }
}