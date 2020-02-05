<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Services\Business\UserBusinessService;
use App\Models\UserModel;
use App\Models\CredentialsModel;

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
        Log::info("Entering AccountController.onRegister()");
        try {
            $username = $request->input('username');
            $password = $request->input('password');

            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');

            $c = new CredentialsModel(0, $username, $password);

            $u = new UserModel(0, $first_name, $last_name, $location, $summary, 0, 0);
            $u->setCredentials($c);

            $bs = new UserBusinessService();

            $flag = $bs->register($u);

            Log::info("Exiting AccountController.onRegister() with " . $flag);
            if ($flag == 1) {
                return view('login');
            } else {
                return view('register');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
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
        Log::info("Entering AccountController.onLogin()");
        try {
            $username = $request->input('username');
            $password = $request->input('password');

            $c = new CredentialsModel(0, $username, $password);

            $bs = new UserBusinessService();

            $flag = $bs->login($c);

            Log::info("Exiting AccountController.onLogin() with " . $flag);
            if ($flag != null) {

                if(is_int($flag)){
                    $data = [
                        'errorMsg' => "Account suspended"
                    ];
                    return view('loginFailed')->with($data);
                }
                Session::put('user_id', $flag->getId());
                Session::put('role', $flag->getRole());

                return view('home');
            } else {
                $data = [
                    'errorMsg' => "Account does not exist"
                ];
                return view('loginFailed')->with($data);
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
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
        Log::info("Entering AccountController.onLogout()");
        Session::forget('user_id');
        Session::forget('role');
        Log::info("Exiting AccountController.onLogout()");
        return view('login');
    }

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
        Log::info("Entering AccountController.onGetAllUsers()");
        try {
            $bs = new UserBusinessService();

            $flag = $bs->getAllUsers();

            Log::info("Exiting AccountController.onGetAllUsers()");
            if ($flag != null) {
                $data = [
                    'allUsers' => $flag
                ];
                return view('admin')->with($data);
            } else {
                return view('home');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
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
        Log::info("Entering AccountController.onGetProfile()");
        try {
            $bs = new UserBusinessService();
            
            $userid = Session::get('user_id');
            
            $user = new UserModel($userid, "", "", "", "", 0, 0);
            
            $flag = $bs->getUser($user);
            
            Log::info("Exiting AccountController.onGetProfile()");
            if ($flag != null) {
                $data = [
                    'user' => $flag
                ];
                return view('profile')->with($data);
            } else {
                return view('home');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }
    
    public function onGetOtherProfile(Request $request)
    {
        Log::info("Entering AccountController.onGetOtherProfile()");
        try {
            $bs = new UserBusinessService();
            
            $userid = $request->input('idToShow');
            
            $user = new UserModel($userid, "", "", "", "", 0, 0);
            
            $flag = $bs->getUser($user);
            
            Log::info("Exiting AccountController.onGetProfile()");
            if ($flag != null) {
                $data = [
                    'user' => $flag
                ];
                return view('profile')->with($data);
            } else {
                return view('home');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }
    
    public function onGetEditProfile()
    {
        Log::info("Entering AccountController.onGetEditProfile()");
        try {
            $bs = new UserBusinessService();
            
            $userid = Session::get('user_id');
            
            $user = new UserModel($userid, "", "", "", "", 0, 0);
            
            $flag = $bs->getUser($user);
            
            Log::info("Exiting AccountController.onGetEditProfile()");
            if ($flag != null) {
                $data = [
                    'user' => $flag
                ];
                return view('editProfile')->with($data);
            } else {
                return view('home');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }
    
    public function onEdit(Request $request){
        
        try {
            $id = $request->input('id');
            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');         
            
            $u = new UserModel($id, $first_name, $last_name, $location, $summary, 0, 0);
            
            $bs = new UserBusinessService();
            
            $flag = $bs->editUser($u);
            
            if ($flag > 0) {
                return $this->onGetProfile();
            } else {
                return $this->onGetEditProfile();
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
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
        Log::info("Entering AccountController.onTryDeleteUser()");
        $idToDelete = $request->input('idToDelete');
        $userToDelete = new UserModel($idToDelete, "", "", "", "", 0, 0);

        $bs = new UserBusinessService();

        $user = $bs->getUser($userToDelete);

        $data = [
            'userToDelete' => $user
        ];
        Log::info("Exiting AccountController.onTryDeleteUser()");
        return view('tryDelete')->with($data);
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
        Log::info("Entering AccountController.onDeleteUser()");
        $idToDelete = $request->input('idToDelete');
        $userToDelete = new UserModel($idToDelete, "", "", "", "", 0, 0);
        
        $bs = new UserBusinessService();
        
        $user = $bs->getUser($userToDelete);
        
        $flag = $bs->remove($user);
        Log::info("Exiting AccountController.onDeleteUser() with" . $flag);
        return $this->onGetAllUsers();
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
    public function onToggleSuspendUser(Request $request)
    {
        Log::info("Entering AccountController.onToggleSuspendUser()");
        $idToToggle = $request->input('idToToggle');
        $userToToggle = new UserModel($idToToggle, "", "", "", "", 0, 0);

        $bs = new UserBusinessService();

        $user = $bs->getUser($userToToggle);

        $flag = $bs->toggleSuspendUser($user);

        Log::info("Exiting AccountController.onToggleSuspendUser() with " . $flag);
        return $this->onGetAllUsers();
    }
    
}
