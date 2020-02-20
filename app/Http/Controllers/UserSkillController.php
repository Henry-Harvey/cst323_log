<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserSkillModel;
use App\Models\Services\Business\UserSkillBusinessService;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;




class UserSkillController extends Controller {
    
    
    
    public function onCreateUserSkill(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            
            $this->validate($request, $vr->getSkillRules());
            
            $skill = $request->input('skill');
            
            $userSkill = new UserSkillModel(0, $skill, Session::get('sp')->getUser_id());
            
            $bs = new UserSkillBusinessService();
            
            $flag = $bs->create($userSkill);
            
            if ($flag == 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
                $c = new AccountController();
                return $c->onGetProfile();
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to newJobHistory view");
                return view('newUserSkill');
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