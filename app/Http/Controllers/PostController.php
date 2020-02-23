<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\PostBusinessService;
use App\Models\Utility\ValidationRules;
use App\Models\Objects\PostModel;
use App\Models\Objects\PostSkillModel;

class PostController extends Controller
{

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

            // flag is rows affected
            $flag = $postBS->createPost($post);

            if ($flag != 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Post",
                    'back' => "newPost"
                ];
                return view('error')->with($data);
            }

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllPosts();
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

    public function onGetAllPosts()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $bs = new PostBusinessService();
            
            // flag is array
            $flag = $bs->getAllPosts();

            if (empty($flag)) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Post",
                    'back' => "newPost"
                ];
                return view('error')->with($data);
            }

            $data = [
                'allPosts' => $flag
            ];
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to allJobPostings view");
            return view('allJobPostings')->with($data);
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
            //$this->validate($request, $vr->getPostEditRules());

            $id = $request->input('id');
            $title = $request->input('title');
            $company = $request->input('company');
            $location = $request->input('location');
            $description = $request->input('description');

            $post = new PostModel($id, $title, $company, $location, $description);

            $postSkill_array = array();

            $skill1 = $request->input('skill1');
            $postSkill1 = new PostSkillModel(0, $skill1, $id);
            array_push($postSkill_array, $postSkill1);

            if ($request->input('skill2') != "") {
                $skill2 = $request->input('skill2');
                $postSkill2 = new PostSkillModel(0, $skill2, $id);
                array_push($postSkill_array, $postSkill2);
            }
            if ($request->input('skill3') != "") {
                $skill3 = $request->input('skill3');
                $postSkill3 = new PostSkillModel(0, $skill3, $id);
                array_push($postSkill_array, $postSkill3);
            }
            if ($request->input('skill4') != "") {
                $skill4 = $request->input('skill4');
                $postSkill4 = new PostSkillModel(0, $skill4, $id);
                array_push($postSkill_array, $postSkill4);
            }

            $post->setPostSkill_array($postSkill_array);

            $bs = new PostBusinessService();

            // flag is rows affected
            $flag = $bs->editPost($post);
            if ($flag != 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit Post",
                    'back' => "getJobPostings"
                ];
                return view('error')->with($data);
            }

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

        // flag is rows affected
        $flag = $bs->remove($post);
        if ($flag != 1) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete Post",
                'back' => "getJobPostings"
            ];
            return view('error')->with($data);
        }
        

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllPosts();
    }

    private function getPostFromId($postid)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $partialPost = new PostModel($postid, "", "", "", "");
        $bs = new PostBusinessService();
        
        // flag is either PostModel or rows found
        $flag = $bs->getPost($partialPost);
        
        if (is_int($flag)) {
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Post",
                'back' => "home"
            ];
            return view('error')->with($data);
        }
        
        $post = $flag;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        return $post;
    }
}
