<?php
namespace App\Http\Controllers;

use App\Models\Objects\CredentialsModel;
use App\Models\Objects\UserModel;
use App\Models\Services\Business\AccountBusinessService;
use App\Models\Utility\SecurityPrinciple;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\UserJobBusinessService;
use App\Models\Services\Business\UserSkillBusinessService;
use App\Models\Services\Business\UserEducationBusinessService;

class AccountController extends Controller
{

    /**
     * Takes in a request from register form
     * Sets variables from the request inputs
     * Creates Credentials and User objects from the variables
     * Creates a user business service
     * Calls the register bs method, using the Credentials and User objects
     * Sets a flag equal to the result
     * If one row was inserted, return the login view
     * Else return the register view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onRegister(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getRegistrationRules());

            $username = $request->input('username');
            $password = $request->input('password');

            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');

            $c = new CredentialsModel(0, $username, $password, 0);

            $u = new UserModel(0, $first_name, $last_name, $location, $summary, 0, 0);
            $u->setCredentials($c);

            $bs = new AccountBusinessService();

            // flag is rows affected
            $flag = $bs->register($u);

            if ($flag == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Register",
                    'back' => "register"
                ];
                return view('error')->with($data);
            }
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to login view");
            return view('login');
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

    /**
     * Takes in a request from login form
     * Sets variables from the request inputs
     * Creates Credentials object from the variables
     * Creates a user business service
     * Calls the login bs method, using the Credentials object
     * Sets a flag equal to the result
     * If the flag is not null and is an int, the user was suspended. Return the loginFailed view and send the error message
     * If the flag is not null and is not an int, set the user id and role sessions. Return the home view
     * If the flag is null, the credentials dont match the database. Return the loginFailed view and send the error message
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onLogin(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getLoginRules());

            $username = $request->input('username');
            $password = $request->input('password');

            $c = new CredentialsModel(0, $username, $password, 0);

            $bs = new AccountBusinessService();

            // flag is either user or rows affected or -1
            $flag = $bs->login($c);

            if (is_int($flag)) {
                if ($flag == - 1) {
                    Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                    $data = [
                        'process' => "(Suspended) Login",
                        'back' => "login"
                    ];
                    return view('error')->with($data);
                }
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Login",
                    'back' => "login"
                ];
                return view('error')->with($data);
            }

            $sp = new SecurityPrinciple($flag->getId(), $flag->getFirst_name(), $flag->getRole());
            Session::put('sp', $sp);

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
            return view('home');
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

    /**
     * Removes all sessions
     * Return the login view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onLogout()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        Session::forget('sp');
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to login view");
        return view('login');
    }

    /**
     * Creates a user business service
     * Gets the user id from the session
     * Calls the getUser bs method, using the user id
     * Sets a flag equal to the result
     * If the flag is not null, return the profile view and persist the user
     * If the flag is null, return the home view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetProfile()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $user = $this->getUserFromSession();

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
     * Creates a user business service
     * Gets the user id from the session
     * Calls the getUser bs method, using the user id
     * Sets a flag equal to the result
     * If the flag is not null, return the edit profile view and persist the user
     * If the flag is null, return the home view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditProfile()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $user = $this->getUserFromSession();

            $data = [
                'user' => $user
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editProfile view");
            return view('editProfile')->with($data);
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
     * Sets variables equal to request inputs
     * Creates a user model from the variables
     * Creates a user business service
     * Calls the editUser bs method, using the user
     * Sets a flag equal to the result
     * If the flag is not null, return the profile view and persist the user
     * If the flag is null, return the edit profile view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditProfile(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getProfileEditRules());

            $id = $request->input('id');
            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');
            $role = $request->input('role');
            $credentials_id = $request->input('credentials_id');

            $u = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);

            $bs = new AccountBusinessService();

            // flag is rows affected
            $flag = $bs->editUser($u);
            if ($flag == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit User",
                    'back' => "getEditProfile"
                ];
                return view('error')->with($data);
            }

            $sp = Session::get('sp');
            $sp->setFirst_name($first_name);
            Session::put('sp', $sp);

            return $this->onGetProfile();
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
