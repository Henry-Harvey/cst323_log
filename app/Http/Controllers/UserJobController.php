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




class UserJobController extends Controller {
    
    
    public function onCreateUserJob(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            
            $this->validate($request, $vr->getHistoryRules());
            
            $title = $request->input('title');
            $company = $request->input('company');
            $years = $request->input('years');
            
            $job = new UserJobModel(0, $title, $company, $years, Session::get('sp')->getUser_id());              
            
            $bs = new UserJobBusinessService();
            
            $flag = $bs->create($job);
            
            if ($flag == 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
                return "/profile";
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to newJobHistory view");
                return view('newJobHistory');
            }
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
    
}