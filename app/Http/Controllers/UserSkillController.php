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

class UserSkillController extends Controller
{

    public function onCreateUserSkill(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();

            $this->validate($request, $vr->getUserSkillRules());

            $skill = $request->input('skill');

            $userSkill = new UserSkillModel(0, $skill, Session::get('sp')->getUser_id());

            $bs = new UserSkillBusinessService();

            // flag is rows affected
            $flag = $bs->createSkill($userSkill);

            if ($flag == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Skill",
                    'back' => "createUserSkill"
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
    
    public function onGetEditUserSkill(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            
            $userSkillToEdit = $this->getUserSkillFromId($request->input('idToEdit'));
            
            $data = [
                'userSkillToEdit' => $userSkillToEdit
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editUserSkill view");
            return view('editUserSkill')->with($data);
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
    
    public function onEditUserSkill(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserSkillRules());
            
            $id = $request->input('id');
            $skill_ = $request->input('skill');
            
            $skill = new UserSkillModel($id, $skill_, Session::get('sp')->getUser_id());
            
            $bs = new UserSkillBusinessService();
            
            // flag is rows affected
            $flag = $bs->editSkill($skill);
            if ($flag != 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit UserSkill",
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
    
    public function onTryDeleteUserSkill(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        $skill = $this->getUserSkillFromId($request->input('idToDelete'));
        
        $data = [
            'skillToDelete' => $skill
        ];
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUserSkill view");
        return view('tryDeleteUserSkill')->with($data);
    }
    
    public function onDeleteUserSkill(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        $skill = $this->getUserSkillFromId($request->input('idToDelete'));
        
        $bs = new UserSkillBusinessService();
        
        // flag is rows affected
        $flag = $bs->remove($skill);
        
        if ($flag != 1) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete UserSkill",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        $c = new AccountController();
        return $c->onGetProfile();
    }
    
    private function getUserSkillFromId($skillid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialUserSkill = new UserSkillModel($skillid, "", "", "", "");
        $bs = new UserSkillBusinessService();
        // flag is either UserSkillModel or rows found
        $flag = $bs->getSkill($partialUserSkill);
        
        if (is_int($flag)) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Skill",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        $skill = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $skill);
        return $skill;
    }
}