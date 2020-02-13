<?php
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;
use App\Models\Objects\PostSkillModel;

class PostSkillDataService implements DataServiceInterface
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
    function create($postSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {

            $skill = $postSkill->getSkill();
            $post_id = $postSkill->getPost_id();

            $stmt = $this->db->prepare('INSERT INTO post_skills
                                        (SKILL, POST_ID)
                                        VALUES (:skill, :post_id)');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':post_id', $post_id);
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
    function read($postSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {
            $id = $postSkill->getId();
            $stmt = $this->db->prepare('SELECT * FROM post_skills
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
            $post_id = $result['POST_ID'];

            $postSkill = new PostSkillModel($id, $skill, $post_id);

            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill . " and " . $stmt->rowCount() . " row(s) found");
            return $postSkill;
        } catch (PDOException $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
    
    function readAllFor($post)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {
            $post_id = $post->getId();
            $stmt = $this->db->prepare('SELECT * FROM post_skills
                                        WHERE POST_ID = :post_id');
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }
            
            $postSkill_array = array();            
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $skill = $result['SKILL'];
                
                $postSkill = new PostSkillModel($id, $skill, $post_id);          
                array_push($postSkill_array, $postSkill);
            }     
            
            Log::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with PostSkillModel array and " . $stmt->rowCount() . " row(s) found");
            return $postSkill_array;
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
    function update($postSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {
            $skill = $postSkill->getSkill();
            $post_id = $postSkill->getPost_id();
            $id = $postSkill->getId();

            $stmt = $this->db->prepare('UPDATE post_skills
                                        SET SKILL = :skill,
                                            POST_ID = :post_id,
                                        WHERE ID = :id');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':post_id', $post_id);
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
    function delete($postSkill)
    {
        Log::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {
            $id = $postSkill->getId();

            $stmt = $this->db->prepare('DELETE FROM post_skills
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