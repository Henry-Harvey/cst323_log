<?php
namespace App\Models\Services\Business;

use App\Models\Utility\DatabaseModel;
use Illuminate\Support\Facades\Log;
use App\Models\Services\Data\PostDataService;
use App\Models\Services\Data\PostSkillDataService;

class PostBusinessService
{

    /**
     * Takes in a user model to be created
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the checkUsername() method from the ds
     * If it returns 0, calls the create() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the create() method
     *
     * @param
     *            newUser user to be registered
     * @return {@link Integer} number of row(s) affected
     */
    function createPost($newPost)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newPost);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // flag is either -1 (rows affected != 1), or insertID
        $flag = $postDS->create($newPost);

        if ($flag == - 1) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $insertId = $flag;

        foreach ($newPost->getPostSkill_array() as $postSkills) {
            $postSkills->setPost_id($insertId);
            // flag is rows affected
            $flag2 = $postSkillDS->create($postSkills);
            if ($flag2 != 1) {
                Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
                return $flag2;
            }
        }

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 1");
        return 1;
    }

    /**
     * Takes in a user model to search for
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the read() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the read() method
     *
     * @param
     *            searchUser user to be searched for
     * @return {@link UserModel} user that was found
     */
    function getPost($partialPost)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialPost);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        $flag = $postDS->read($partialPost);

        if (is_int($flag)) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $post = $flag;

        $flag2 = $postSkillDS->readAllFor($post);

        if (is_int($flag2)) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        $postSkill_array = $flag2;

        $post->setPostSkill_array($postSkill_array);

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        return $post;
    }

    /**
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the readAll() method from the ds
     * Sets the database equal to null
     * Returns the result of the readAll() method
     *
     * @return {@link Array} array of users found
     */
    function getAllPosts()
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        $flag = $postDS->readAll();

        if (is_int($flag)) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $posts_array = $flag;

        foreach ($posts_array as $post) {
            $flag2 = $postSkillDS->readAllFor($post);

            if (is_int($flag2)) {
                Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
                return $flag2;
            }

            $postSkill_array = $flag2;

            $post->setPostSkill_array($postSkill_array);
        }

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with PostModel array");
        return $posts_array;
    }

    /**
     * Takes in a user model to update
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the update() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the update() method
     *
     * @param
     *            updatedUser user to be updated
     * @return {@link Integer} number of row(s) affected
     */
    function editPost($updatedPost)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedPost);

        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        $db->beginTransaction();

        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // flag is rows affected
        $flag = $postDS->update($updatedPost);
        
        // reset skills
        // flag is rows affected
        $flag2 = $postSkillDS->deleteAllFor($updatedPost);
        
        foreach ($updatedPost->getPostSkill_array() as $postSkills) {
            // flag is rows affected
            $flag3 = $postSkillDS->create($postSkills);
            if ($flag3 != 1) {
                Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
                return $flag3;
            }
        }
        
        if($flag == 0 && $flag2 == 0){
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 0");
            return 0;
        }

        $db->commit();
        
        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 1");
        return 1;
    }

    /**
     * Takes in a user model to delete
     * Creates a new database model
     * Gets the database from the model
     * Creates a user data service with the database
     * Calls the delete() method from the ds with the user model as the parameter
     * Sets the database equal to null
     * Returns the result of the delete() method
     *
     * @param
     *            deleteUser user to be deleted
     * @return {@link Integer} number of row(s) affected
     */
    function remove($partialPost)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialPost);

        $Database = new DatabaseModel();
        $db = $Database->getDb();

        $db->beginTransaction();

        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        foreach ($partialPost->getPostSkill_array() as $postSkill) {
            // flag is rows affected
            $flag = $postSkillDS->delete($postSkill);
            if ($flag != 1) {
                Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
                return $flag;
            }
        }

        // flag is rows affected
        $flag2 = $postDS->delete($partialPost);

        if ($flag2 != 1) {
            Log::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        $db->commit();

        $db = null;

        Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
        return $flag;
    }
}



 
 
