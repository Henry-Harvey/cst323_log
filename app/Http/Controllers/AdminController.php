<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserModel;
use App\Models\Services\Business\AccountBusinessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\PostBusinessService;
use App\Models\Utility\ValidationRules;
use App\Models\Objects\PostModel;
use App\Models\Objects\PostSkillModel;

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
            $flag = $bs->getAllUsers();

            if ($flag == null) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
                return view('home');
            }

            $data = [
                'allUsers' => $flag
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to admin view");
            return view('admin')->with($data);
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

            if ($user -= null) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
                return view('home');
            }

            $data = [
                'user' => $user
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
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $user = $this->getUserFromId($request->input('idToDelete'));

        $bs = new AccountBusinessService();
        $flag = $bs->remove($user);

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
        $flag = $bs->toggleSuspension($user);

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . "with " . $flag);
        return $this->onGetAllUsers();
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

    public function onGetAllPosts()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $bs = new PostBusinessService();
            $flag = $bs->getAllPosts();

            if ($flag == null) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
                return view('home');
            }

            $data = [
                'allPosts' => $flag
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to jobPostings view");
            return view('jobPostings')->with($data);
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

    public function onCreatePost(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();

            $this->validate($request, $vr->getPostRules());

            $title = $request->input('title');
            $company = $request->input('company');
            $location = $request->input('location');
            $description = $request->input('description');

            $post = new PostModel(0, $title, $company, $location, $description);

            $postSkill_array = array();

            $skill1 = $request->input('skill1');
            $postSkill1 = new PostSkillModel(0, $skill1, 0);
            array_push($postSkill_array, $postSkill1);

            if ($request->input('skill2') != "") {
                $skill2 = $request->input('skill2');
                $postSkill2 = new PostSkillModel(0, $skill2, 0);
                array_push($postSkill_array, $postSkill2);
            }
            if ($request->input('skill3') != "") {
                $skill3 = $request->input('skill3');
                $postSkill3 = new PostSkillModel(0, $skill3, 0);
                array_push($postSkill_array, $postSkill3);
            }
            if ($request->input('skill4') != "") {
                $skill4 = $request->input('skill4');
                $postSkill4 = new PostSkillModel(0, $skill4, 0);
                array_push($postSkill_array, $postSkill4);
            }

            $post->setPostSkill_array($postSkill_array);

            $postBS = new PostBusinessService();

            $flag = $postBS->create($post);

            if ($flag == 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
                return $this->onGetAllPosts();
            } else {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to newPost view");
                return view('newPost');
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

    public function onTryDeletePost(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $post = $this->getPostFromId($request->input('idToDelete'));

        $data = [
            'postToDelete' => $post
        ];

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeletePost view");
        return view('tryDeletePost')->with($data);
    }

    public function onDeletePost(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $post = $this->getPostFromId($request->input('idToDelete'));

        $bs = new PostBusinessService();

        $flag = $bs->remove($post);

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllPosts();
    }

    public function onGetEditPost(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {

            $postToEdit = $this->getPostFromId($request->input('idToEdit'));

            $data = [
                'postToEdit' => $postToEdit
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editPost view");
            return view('editPost')->with($data);
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

    public function onEditPost(Request $request)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            $this->validate($request, $vr->getPostEditRules());

            $id = $request->input('id');
            $title = $request->input('title');
            $company = $request->input('company');
            $location = $request->input('location');
            $description = $request->input('description');

            $post = new PostModel($id, $title, $company, $location, $description);

            $postSkill_array = array();

            $skill1 = $request->input('skill1');
            $postSkill1 = new PostSkillModel(0, $skill1, 0);
            array_push($postSkill_array, $postSkill1);

            if ($request->input('skill2') != "") {
                $skill2 = $request->input('skill2');
                $postSkill2 = new PostSkillModel(0, $skill2, 0);
                array_push($postSkill_array, $postSkill2);
            }
            if ($request->input('skill3') != "") {
                $skill3 = $request->input('skill3');
                $postSkill3 = new PostSkillModel(0, $skill3, 0);
                array_push($postSkill_array, $postSkill3);
            }
            if ($request->input('skill4') != "") {
                $skill4 = $request->input('skill4');
                $postSkill4 = new PostSkillModel(0, $skill4, 0);
                array_push($postSkill_array, $postSkill4);
            }

            $post->setPostSkill_array($postSkill_array);

            $bs = new PostBusinessService();

            $flag = $bs->editPost($post);

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllPosts();
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

    private function getPostFromId($postid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialPost = new PostModel($postid, "", "", "", "");
        $bs = new PostBusinessService();
        $post = $bs->getPost($partialPost);

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        return $post;
    }
}
