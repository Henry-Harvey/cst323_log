<?php
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;
use App\Models\Objects\UserSkillModel;

class UserSkillDataService implements DataServiceInterface
{

    private $db = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     *
     * @see DataServiceInterface create
     */
    function create($userSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {

            $skill = $userSkill->getSkill();
            $user_id = $userSkill->getUser_id();

            $stmt = $this->db->prepare('INSERT INTO user_skills
                                        (SKILL, USER_ID)
                                        VALUES (:skill, :user_id)');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) affected");
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * @see DataServiceInterface read
     */
    function read($userSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {
            $id = $userSkill->getId();
            $stmt = $this->db->prepare('SELECT * FROM user_skills
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $skill = $result['SKILL'];
            $user_id = $result['USER_ID'];

            $userSkill = new UserSkillModel($id, $skill, $user_id);

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill . " and " . $stmt->rowCount() . " row(s) found");
            return $userSkill;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
    
    function readAllFor($user)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        try {
            $user_id = $user->getId();
            $stmt = $this->db->prepare('SELECT * FROM user_skills
                                        WHERE USER_ID = :user_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }
            
            $userSkill_array = array();            
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $skill = $result['SKILL'];
                
                $userSkill = new UserSkillModel($id, $skill, $user_id);          
                array_push($userSkill_array, $userSkill);
            }     
            
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserSkillModel array and " . $stmt->rowCount() . " row(s) found");
            return $userSkill_array;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    // not implemented
    function readAll()
    {}

    /**
     *
     * @see DataServiceInterface update
     */
    function update($userSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {
            $skill = $userSkill->getSkill();
            $user_id = $userSkill->getUser_id();
            $id = $userSkill->getId();

            $stmt = $this->db->prepare('UPDATE user_skills
                                        SET SKILL = :skill,
                                            USER_ID = :user_id,
                                        WHERE ID = :id');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) affected");
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * @see DataServiceInterface delete
     */
    function delete($userSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {
            $id = $userSkill->getId();

            $stmt = $this->db->prepare('DELETE FROM user_skills
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) affected");
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
}