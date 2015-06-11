<?php

class DBConnection
{
	private $dbHost;
	private $dbUsername;
	private $dbPassword;
	private $dbName;
	private $dbConnection;


	public function __construct($dbHost, $dbUsername, $dbPassword, $dbName)
	{
		$this->dbHost					= $dbHost;
		$this->dbUsername				= $dbUsername;
		$this->dbPassword 				= $dbPassword;
		$this->dbName					= $dbName;

        //echo $this->dbHost;
        //echo $this->dbUsername;
        //echo $this->dbPassword;
        //echo $this->dbName;
	}

	public function openConnection()
	{
		$this->dbConnection = @new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName) or die();
		
		if ($this->dbConnection->connect_errno) {
			die("<p class='errorMsg'>Es konnte keine Verbindung zur Datenbank aufgebaut, versuchen Sie es zu einem sp&auml;teren Zeitpunkt erneut.<br>". $this->dbConnection->connect_errno ."</p>");
		}

		//echo "Verbindung steht";
		//return $dbConnection;
        return;
	}

	public function sendSqlQuery(&$ref_sqlQuery)
	{
		$sqlResult = $this->dbConnection->query($ref_sqlQuery);

		/*
		echo '<pre>';
		var_dump($sqlResult);
		echo '</pre>';
		*/

		if(!$sqlResult)
		{
			die("<p class='errorMsg'>Fehler bei der Datenbank Anfrage.<br>". $this->dbConnection->error ."</p>");
		}

		return $sqlResult;
	}

    public function sendUpdateSqlQuery(&$ref_sqlQuery)
    {
        $sqlResult = $this->dbConnection->query($ref_sqlQuery);

        /*
        echo '<pre>';
        var_dump($sqlResult);
        echo '</pre>';
        */

        if(!$sqlResult)
        {
            die("<p class='errorMsg'>Fehler bei der Datenbank Anfrage.<br>". $this->dbConnection->error ."</p>");
        }

        return $this->dbConnection->affected_rows;
    }

	public function closeConnection()
	{
		$this->dbConnection->close();
		//echo "Verbindung zu";

        return;
	}




	public function sqlInjectionStopper(&$ref_userInput)
	{
		return $this->dbConnection->real_escape_string($ref_userInput);
	}

    public function getDBConnection()
    {
        return $this->dbConnection;
    }
}

?>