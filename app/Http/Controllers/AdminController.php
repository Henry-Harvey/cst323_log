<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserModel;
use App\Models\Services\Business\AccountBusinessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Services\Business\UserJobBusinessService;
use App\Models\Services\Business\UserSkillBusinessService;
use App\Models\Services\Business\UserEducationBusinessService;

class AdminController extends Controller
{

    /**
     * Creates a user business service
     * Calls the getAllUsers bs method
     * Sets a flag equal to the result
     * If the flag is not null, return the admin view and persist the list of users
     * If the flag is null, return the home view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetAllUsers()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $bs = new AccountBusinessService();
            
            // flag is array
            $flag = $bs->getAllUsers();

            if (empty($flag)) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Get All Users",
                    'back' => "home"
                ];
                return view('error')->with($data);
            }

            $data = [
                'allUsers_array' => $flag
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to allUsers view");
            return view('allUsers')->with($data);
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

    /**
     * Creates a user business service
     * Gets the user id from the request
     * Calls the getUser bs method, using the user id
     * Sets a flag equal to the result
     * If the flag is not null, return the profile view and persist the user
     * If the flag is null, return the home view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetOtherProfile(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $user = $this->getUserFromId($request->input('idToShow'));

            $jobBS = new UserJobBusinessService();
            $skillBS = new UserSkillBusinessService();
            $educationBS = new UserEducationBusinessService();
            
            // arrays may be empty, so dont check flags
            $userJob_array = $jobBS->getAllJobsForUser($user);
            $userSkill_array = $skillBS->getAllSkillsForUser($user);
            $userEducation_array = $educationBS->getAllEducationForUser($user);
            
            $data = [
                'user' => $user,
                'userJob_array' => $userJob_array,
                'userSkill_array' => $userSkill_array,
                'userEducation_array' => $userEducation_array
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to profile view");
            return view('profile')->with($data);
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

    /**
     * Takes in a request from admin view
     * Sets variable from the request input
     * Creates User object from the variable
     * Creates a user business service
     * Calls the getUser bs method, using the User object
     * Sets the result equal to user
     * Returns the tryDelete view and persist the user
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryDeleteUser(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $user = $this->getUserFromId($request->input('idToDelete'));

        $data = [
            'userToDelete' => $user
        ];

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDelete view");
        return view('tryDeleteUser')->with($data);
    }

    /**
     * Takes in a request from tryDelete view
     * Sets variable from the request input
     * Creates User object from the variable
     * Creates a user business service
     * Calls the getUser bs method, using the User object
     * Sets the result equal to user
     * Calls the remove bs method
     * Sets a flag equal to the result
     * Returns the admin view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeleteUser(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $user = $this->getUserFromId($request->input('idToDelete'));

        $bs = new AccountBusinessService();
        // flag is rows affected
        $flag = $bs->remove($user);
        
        if ($flag == 0) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Remove User",
                'back' => "getAllUsers"
            ];
            return view('error')->with($data);
        }

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllUsers();
    }

    public function onTryToggleSuspension(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $user = $this->getUserFromId($request->input('idToToggle'));

        $data = [
            'userToToggle' => $user
        ];

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryToggleSuspension view");
        return view('tryToggleSuspension')->with($data);
    }

    /**
     * Takes in a request from tryDelete view
     * Sets variable from the request input
     * Creates User object from the variable
     * Creates a user business service
     * Calls the getUser bs method, using the User object
     * Sets the result equal to user
     * Calls the toggleSuspendUser bs method
     * Sets a flag equal to the result
     * Returns the admin view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onToggleSuspension(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $user = $this->getUserFromId($request->input('idToToggle'));

        $bs = new AccountBusinessService();
        // flag is rows affected
        $flag = $bs->toggleSuspension($user);
        
        if ($flag == 0) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Toggle Suspend User",
                'back' => "getAllUsers"
            ];
            return view('error')->with($data);
        }

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . "with " . $flag);
        return $this->onGetAllUsers();
    }
    
    private function getUserFromSession()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $userid = Session::get('sp')->getUser_id();
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        $bs = new AccountBusinessService();
        
        // flag is either user or rows found
        $flag = $bs->getUser($partialUser);
        
        if (is_int($flag)) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get User",
                'back' => "home"
            ];
            return view('error')->with($data);
        }
        
        $user = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }
    
    private function getUserFromId($userid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        $bs = new AccountBusinessService();
        
        // flag is either user or rows found
        $flag = $bs->getUser($partialUser);
        
        if (is_int($flag)) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get User",
                'back' => "home"
            ];
            return view('error')->with($data);
        }
        
        $user = $flag;
        
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }

}
