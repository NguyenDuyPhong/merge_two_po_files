<?php  

// var_dump($_FILES);   //ok,  form...enctype="multipart/form-data"   
  // ["file01"]=>
  // array(5) {
    // ["name"]=>
    // string(32) "vi_VN-ghr - 03okHEADER-little.po"
    // ["type"]=>
    // string(24) "application/octet-stream"
    // ["tmp_name"]=>
    // string(66) "D:\Data_IT\Servers-Web_DBMS\xampp-win32-1.8.2-1-VC9\tmp\php2BD.tmp"
    // ["error"]=>
    // int(0)
    // ["size"]=>
    // int(940)
  // }
// var_dump($_REQUEST);  // = get + post  
// var_dump($_GET);  
// var_dump($_POST);  
// var_dump($_SERVER); 
if(isset($_FILES['file01']) ){
	$fullName01 = $_FILES['file01']['tmp_name'];  
	$mergeFunctions =  new MergeTwoPoFile;  
	$mergeFunctions->intIDLen01 = strlen($mergeFunctions->msgID); 
	$mergeFunctions->intIDLen02 = strlen($mergeFunctions->msgValue); 
	$strFile01 =  $mergeFunctions->readFile($fullName01) ; //"filename.csv"  
	
	
	echo "<br/>* Content of File01...<br/>";
	var_dump($strFile01); 

	 

}
 


/*
 * Support any functions: find common line in two files and replace the next line of first file 
 * with the next line of other file. 
 * Do something cool for solving problem that Que. had raised  (the same mind, exactly) 
 * http://stackoverflow.com/questions/21567173/find-common-line-in-two-files-and-replace-the-next-line-of-first-file-with-the-n
 *  
 * @author: phong.nguyen@egany.com 20150828 
 */
class MergeTwoPoFile 
{
	/*
	 * readFile
	 *
	 * @author: phong.nguyen@egany.com 20150828 
	 */
	public function readFile($fullName) {  
				
		// ref:http://stackoverflow.com/questions/4894817/read-and-write-to-the-same-file  
		$file = fopen($fullName, "r+") or exit("Unable to open file!");
		$strFileContent = ';'; 
		$count = 0; 
		$line01 = ''; 
		$line02 = ''; 
		$intIgnore = 0;  // LOCs for ignoring  
		while (!feof($file)) {
			$count++; 
			$line_current = fgets($file);
			$intIgnore-- ; // ==== $intIgnore - 1;   
			
			//don't check 2 first lines 
			if ( $count > 1 && $intIgnore < 0 ) {   
				$line01 = $line02;  
				$line02 = $line_current;
				$strFileContent .= $line02;  
				
				$arrPair = self::findPairKeyIDValue($line01, $line02); 
				
				if ( isset($arrPair) ) { 
					// // 
					// var_dump($arrPair);  
					$this->arrDict = array_merge($this->arrDict, $arrPair); 
					//ignore next-line 
					$intIgnore = 1;  
				}
				else {
					//nothing 
					// $intIgnore = 0;  
				}
				// fwrite($file,$line);
			}
			
		} // END: while  
		
// var_dump($arrPair); 
var_dump($this->arrDict); 
		fclose($file); 
		
		return $strFileContent; 
	}
	
	public $msgID = 'msgid';  
	public $msgValue = 'msgstr';  
	public $intIDLen01 = 0;  //strlen($this->msgID);  
	public $intIDLen02 = 0; //strlen($this->msgValue);  
	public $arrDict = array(); 
	
	/*
	 * findPairKeyIDValue... 
	 * condition msgid: 
	 * + be 5 first chars; 
	 * + remove first line.   
	 * + cut off: [empty space], '', "" at front&rear 
	 *
	 * @author: phong.nguyen@egany.com 20150828 
	 */
	public function findPairKeyIDValue($line01, $line02) {   
		$arrPair = array( 
			$this->msgID => '',
			$this->msgValue => '',  
		); 
		
		//find postion 2#
		// $position_num  = strrpos($line01,$this->msgID);   
		// var_dump($position_num);
		//find ID info. 
		$strID = substr($line01, 0, $this->intIDLen01); 
		$strIDContent = substr($line01, $this->intIDLen01, strlen($line01));  
		$strIDContent = $this->trimStringMoreClean($strIDContent);  
		//find value info. 
		$strValue = substr($line02, 0, $this->intIDLen02);  
		$strValueContent = substr($line02, $this->intIDLen02, strlen($line02));  
		$strValueContent = $this->trimStringMoreClean($strValueContent);   
		// var_dump($strID); 
		// var_dump($strIDContent); 
		$boCheckIDInfo = $strID == $this->msgID && $strIDContent != false && $strIDContent != '';  
		$boCheckValueInfo = $strValue == $this->msgValue && $strValueContent != false && $strValueContent != '' ;
		
		if ( $boCheckIDInfo && $boCheckValueInfo ) { // ($position_num != false){  
			//cut the string
			// $strKey = substr($line01,$position_num, strlen($line01));   
			$arrPair[$this->msgID] = $strIDContent;   
			$arrPair[$this->msgValue] = $strValueContent;   
			
			//proccessing msgstr 
		} 
		else 
			$arrPair = null; 
		return $arrPair; 
	}
	
	public function trimStringMoreClean($strValue){ 
		
		//cut off "", 'empty'...
		$strValue = str_replace('"', '', $strValue);   
		$strValue = trim($strValue);   
		
		return $strValue; 
		
	}
	
	


}




