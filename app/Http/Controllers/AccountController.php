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
     * Controller method that takes in a request
     * Sets the data from the request to variables
     * Creates a Calculation object from the variables
     * Creates an associative array with the object
     * Returns result view and pushes the data array
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

            $bs = new UserBusinessService();

            $flag = $bs->register($c, $u);

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
                
                Session::put('user_id', $flag->getId());
                Session::put('role', $flag->getRole());
                
                return view('home');
            } else {
                return view('login');
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

    public function onLogout()
    {   
        Session::forget('user_id');
        Session::forget('role');
        return view('login');
    }
}
