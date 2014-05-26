<?php
include('error_codes.php');
  /*********************************************************************************************************
  * Class : BlackJack
  * Author : Gagandeep Kaur
  * Description : Add the face value of two cards input by the user and prints the result
  ***********************************************************************************************************/
class blackjack{
  /**
  * Private all cards face values array ( setup from ini)
  **/
 private $cardValueArray;
  /**
  * Private suits array ( setup from ini)
  **/
 private $suitsArray;
  /**
  * Private first user input card array 
  * firstCardArray[suit] & firstCardArray[faceValue]
  **/
 private $firstCardArray;
 /**
  * Private second user input card array 
  * secCardArray[suit] & secCardArray[faceValue]
  **/
 private $secCardArray;
 /**
  * Setup when any error occurred
  **/
 public  $errorOccured = '';
 /**
  * INI files to fetch cards suits and face value array
  **/
 private  $iniFile = "cards.ini";
 /**
  * Minimum length of user card input for Validation
  **/
 public $minCardlen = 2;
 /**
  * Maximum length of user card input for Validation
  **/
 public $maxCardlen = 3;
  
   /**
  * Constructor
  * Parse INI file and setup the suits and facce value array of the cards
  * Display the result of calculated face value OR Error if happened
  **/
  function __construct($firstCard,$secCard) {
     $this->errorOccured = '';
     if(!$this->parseINI()){
	   die("Error $this->errorOccured occurred!");
	 } 
	 if($total = $this->calculate($firstCard,$secCard)){
	    $this->displayResult($total);
	 }else{
	    $this->displayError();
	 }
  }
  
  
  /**
  * Function: parseINI
  * Parameters: 
  * Return: Boolean
  * Description: Parse INI file and setup the suits and facce value array of the cards
  **/
 private function parseINI(){
 	if(file_exists($this->iniFile)){
     	$iniContents=parse_ini_file($this->iniFile,true);
	 	if(isset($iniContents['facevalue']) && isset($iniContents['suits'])){
		 	$this->cardValueArray = $iniContents['facevalue'];
		 	$this->suitsArray = $iniContents['suits'];
		 	return true;
	 	}else{
	     	$this->errorOccured = ERR_RETRIEVE_INI_FILE;
		 	return false;
	 	}
  	}else{
		$this->errorOccured = ERR_INI_FILE_NOT_EXIST;
		return false;
	}
 }
 
 /**
  * Function: findFaceValue
  * Parameters: card user input  For eg 2H means 2 of hearts
  * Return: Array OR False on failure
  * Description: separate the suit and face value from user input
  **/ 
  private function findFaceValue($card){
     $cardArr = array();
     $len = strlen($card);
	 if($len < $this->minCardlen || $len > $this->minCardlen){
	   $this->errorOccured = ERR_WRONG_CARD_VALUE;
	   return false;
	 }
	 $cardArr['suit'] = substr($card, -1); 
	 $face = substr($card, -$len, ($len-1)); 
	 if(isset($this->cardValueArray[$face])){
	 	$cardArr['faceValue'] = $this->cardValueArray[$face];
	 }else{
	 	$this->errorOccured = ERR_WRONG_CARD_VALUE;
		return false;
	 }
	 
	 return $cardArr;
  }
 
  /**
  * Function: calculate
  * Parameters: firstCard & second Card of User
  * Return: Integer( total of face value) OR false on failure
  * Description: Add the facevalue of two cards
  **/ 
  private function calculate($firstCard,$secCard){
     if($this->findFaceValue($firstCard) && $this->findFaceValue($secCard)){
		 $this->firstCardArray=$this->findFaceValue($firstCard);
		 $this->secCard=$this->findFaceValue($secCard);
		 $total= $this->firstCardArray['faceValue'] + $this->secCard['faceValue'];
		 return $total;
	 }else{
	 	 return false;
	 }
	 
  }
  
  /**
  * Function: displayResult
  * Parameters: Integer - result 
  * Return: HTML string
  * Description: to display the result on screen
  **/ 
  private function displayResult($result){
	   echo "Total is : ".$result."<br>";
	}
  
   /**
  * Function: displayError
  * Parameters: 
  * Return: HTML string
  * Description: to display the Error on screen
  **/ 
  private function displayError(){
		 echo "Error $this->errorOccured occurred!";
   }

}//ec

