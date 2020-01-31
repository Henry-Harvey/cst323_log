<?php
namespace App\Models\Services\Data;

interface DataServiceInterface
{
    /**
     * Takes in an object
     * Connects to the database
     * Creates and executes a sql statement
     * Returns a boolean indicating success or failure
     *
     * @param newModel		object to be created
     * @return 	{@link Boolean}		boolean for success
     */
    function create($newModel);
    
    /**
     * Takes in an int id
     * Connects to the database
     * Creates and executes a sql statement
     * Sets an object equal to the result set
     * Returns the object
     *
     * @param id	int to find the object
     * @return 	{@link Object}		object that is found
     */
    function read($id);
    
    /**
     * Connects to the database
     * Creates and executes a sql statement
     * Sets a list of objects equal to the result set
     * Returns the list
     *
     * @return 	{@link List}		list of all objects
     */
    function readAll();
    
    /**
     * Takes in an object
     * Connects to the database
     * Creates and executes a sql statement
     * Returns a boolean indicating success or failure
     *
     * @param updatedModel		object to be updated
     * @return 	{@link Boolean}		boolean for success
     */
    function update($updatedModel);
    
    /**
     * Takes in an object id
     * Connects to the database
     * Creates and executes a sql statement
     * Returns a boolean indicating success or failure
     *
     * @param id		id of object to be deleted
     * @return 	{@link Boolean}		boolean for success
     */
    function delete($id);
}

