<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services\Business\UserBusinessService;
use App\Models\User;

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
    public function register(Request $request)
    {
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $username = $request->input('username');
        $password = $request->input('password');

        $u = new User(0, $firstname, $lastname, $username, $password, 0);
        $bs = new UserBusinessService();

        if ($bs->newUser($u)) {
            return view('login');
        } else {
            return view('register');
        }
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $bs = new UserBusinessService();
        if($bs->login($username, $password)){
            return view('home');          
        }
        else{
            return view('login');
        }

    }

    // not used yet
    public function logout()
    {
        $bs = new UserBusinessService();
        $bs->logout();
        return view('login');
    }
}
