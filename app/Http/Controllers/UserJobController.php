<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserJobModel;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\UserJobBusinessService;

class UserJobController extends Controller
{

    public function onCreateUserJob(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();

            $this->validate($request, $vr->getUserJobRules());

            $title = $request->input('title');
            $company = $request->input('company');
            $years = $request->input('years');

            $job = new UserJobModel(0, $title, $company, $years, Session::get('sp')->getUser_id());

            $bs = new UserJobBusinessService();

            // flag is rows affected
            $flag = $bs->createJob($job);

            if ($flag == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Job",
                    'back' => "createUserJob"
                ];
                return view('error')->with($data);
            }
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            $c = new AccountController();
            return $c->onGetProfile();
        } catch (ValidationException $e2) {
            throw $e2;
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }
    
    public function onGetEditUserJob(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            
            $userJobToEdit = $this->getUserJobFromId($request->input('idToEdit'));
            
            $data = [
                'userJobToEdit' => $userJobToEdit
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editUserJob view");
            return view('editUserJob')->with($data);
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }
    
    public function onEditUserJob(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserJobRules());
            
            $id = $request->input('id');
            $title = $request->input('title');
            $company = $request->input('company');
            $years = $request->input('years');
            
            $job = new UserJobModel($id, $title, $company, $years, Session::get('sp')->getUser_id());
            
            $bs = new UserJobBusinessService();
            
            // flag is rows affected
            $flag = $bs->editJob($job);
            if ($flag != 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit UserJob",
                    'back' => "getProfile"
                ];
                return view('error')->with($data);
            }
            
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            $c = new AccountController();
            return $c->onGetProfile();
        } catch (ValidationException $e2) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with validation error");
            throw $e2;
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }
    
    public function onTryDeleteUserJob(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        $job = $this->getUserJobFromId($request->input('idToDelete'));
        
        $data = [
            'jobToDelete' => $job
        ];
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUserJob view");
        return view('tryDeleteUserJob')->with($data);
    }
    
    public function onDeleteUserJob(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        $job = $this->getUserJobFromId($request->input('idToDelete'));
        
        $bs = new UserJobBusinessService();
        
        // flag is rows affected
        $flag = $bs->remove($job);
        
        if ($flag != 1) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete UserJob",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        $c = new AccountController();
        return $c->onGetProfile();
    }
       
    private function getUserJobFromId($jobid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialUserJob = new UserJobModel($jobid, "", "", "", "");
        $bs = new UserJobBusinessService();
        // flag is either UserJobModel or rows found
        $flag = $bs->getJob($partialUserJob);
        
        if (is_int($flag)) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Job",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        $job = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $job);
        return $job;
    }
    
}