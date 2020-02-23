<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserEducationModel;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\UserEducationBusinessService;

class UserEducationController extends Controller
{

    public function onCreateUserEducation(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();

            $this->validate($request, $vr->getUserEducationRules());

            $school = $request->input('school');
            $degree = $request->input('degree');
            $years = $request->input('years');

            $education = new UserEducationModel(0, $school, $degree, $years, Session::get('sp')->getUser_id());

            $bs = new UserEducationBusinessService();

            // flag is rows affected
            $flag = $bs->createEducation($education);

            if ($flag == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Education",
                    'back' => "createUserEducation"
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
    
    public function onGetEditUserEducation(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            
            $userEducationToEdit = $this->getUserEducationFromId($request->input('idToEdit'));
            
            $data = [
                'userEducationToEdit' => $userEducationToEdit
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editUserEducation view");
            return view('editUserEducation')->with($data);
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
    
    public function onEditUserEducation(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserEducationRules());
            
            $id = $request->input('id');
            $school = $request->input('school');
            $degree = $request->input('degree');
            $years = $request->input('years');
            
            $education = new UserEducationModel($id, $school, $degree, $years, Session::get('sp')->getUser_id());
            
            $bs = new UserEducationBusinessService();
            
            // flag is rows affected
            $flag = $bs->editEducation($education);
            if ($flag != 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit UserEducation",
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
    
    public function onTryDeleteUserEducation(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        $education = $this->getUserEducationFromId($request->input('idToDelete'));
        
        $data = [
            'educationToDelete' => $education
        ];
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUserEducation view");
        return view('tryDeleteUserEducation')->with($data);
    }
    
    public function onDeleteUserEducation(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        $education = $this->getUserEducationFromId($request->input('idToDelete'));
        
        $bs = new UserEducationBusinessService();
        
        // flag is rows affected
        $flag = $bs->remove($education);
        
        if ($flag != 1) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete UserEducation",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        $c = new AccountController();
        return $c->onGetProfile();
    }
    
    private function getUserEducationFromId($educationid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialUserEducation = new UserEducationModel($educationid, "", "", "", "");
        $bs = new UserEducationBusinessService();
        // flag is either UserEducationModel or rows found
        $flag = $bs->getEducation($partialUserEducation);
        
        if (is_int($flag)) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Education",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        $education = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $education);
        return $education;
    }
}