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

            $flag = $bs->register($u);

            if ($flag == 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to login view");
                return view('login');
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to register view");
                return view('register');
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

            $flag = $bs->login($c);

            if (is_int($flag)) {
                if ($flag == - 1) {
                    $data = [
                        'errorMsg' => "Account suspended"
                    ];
                    Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to loginFailed view. Account suspended");
                    return view('loginFailed')->with($data);
                }
                $data = [
                    'errorMsg' => "Account nout found"
                ];
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to loginFailed view. Account not found");
                return view('loginFailed')->with($data);
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

            if ($user != null) {
                $data = [
                    'user' => $user
                ];
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to profile view");
                return view('profile')->with($data);
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
                return view('home');
            }
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

            if ($user != null) {
                $data = [
                    'user' => $user
                ];
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editProfile view");
                return view('editProfile')->with($data);
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
                return view('home');
            }
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
    public function onEditUser(Request $request)
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

            $flag = $bs->editUser($u);

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            if ($flag > 0) {

                $sp = Session::get('sp');

                $sp->setFirst_name($first_name);

                Session::put('sp', $sp);

                return $this->onGetProfile();
            } else {
                return $this->onGetEditProfile();
            }
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
        $user = $bs->getUser($partialUser);
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }

    private function getUserFromId($userid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        $bs = new AccountBusinessService();
        $user = $bs->getUser($partialUser);
        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }
}
