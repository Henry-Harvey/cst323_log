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


class UserEducationController extends Controller {
    
   
    
    public function onCreateUserEducation(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            
            $this->validate($request, $vr->getEducationRules());
            
            $school = $request->input('school');
            $degree = $request->input('degree');
            $years = $request->input('years');
            
            $education = new UserEducationModel(0, $school, $degree, $years, Session::get('sp')->getUser_id());
            
            $bs = new UserEducationBusinessService();

            $flag = $bs->create($education);
            
            if ($flag == 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
                $c = new AccountController();
                return $c->onGetProfile();
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to newJobHistory view");
                return view('newUserEducation');
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