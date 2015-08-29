<?php  

if(isset($_FILES['file01']) ){
	$fullName01 = isset($_FILES['file01']) ? $_FILES['file01']['tmp_name'] : '';   
	$fullName02 = isset($_FILES['file02']) ? $_FILES['file02']['tmp_name'] : '';   
	$mergePO =  new MergeTwoPoFile;  
	$mergePO->intIDLen01 = strlen($mergePO->msgID); 
	$mergePO->intIDLen02 = strlen($mergePO->msgValue);  
	$strFile01 =  $mergePO->readFileToDictionary($fullName01) ; //"filename.csv"     
	//update 2nd file... 
	$strFile02 =  $mergePO->updateFileFromDictionary($fullName02) ; //"filename.csv"  
	
	
	echo "<br/>* Dictionary...<br/>";
	var_dump($mergePO->intIDLen01); 
	var_dump($mergePO->intIDLen02); 
	var_dump($mergePO->arrDict); 
	echo "<br/>* Content of File01...<br/>";
	var_dump($strFile01); 
	echo "<br/>* Content of File02...<br/>";
	var_dump($strFile02); 

	 

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

	public $msgID = 'msgid';  
	public $msgValue = 'msgstr';  
	public $intIDLen01 = 0;  //strlen($this->msgID);  
	public $intIDLen02 = 0; //strlen($this->msgValue);  
	public $arrDict = array(); 
	
	
	/*
	 * updateFileFromDictionary...  
	 * 
	 * Check if the msgID is existing, replace the relevant msgValue right-below; 
	 * NOT add new-msgID from dictionary. 
	 * Some features:
	 * + Check ID; 
	 * + Find inside dictionary; 
	 * + Remove entire matching line; then Insert chars, not replace;  
	 * + Support plural: "msgid_plural"; string:  msgstr[0] "", msgstr[1] "" ... 
	 *
	 * @author: phong.nguyen@egany.com 20150828 
	 * @return: true 
	 */ 
	public function updateFileFromDictionary($fullName) {   
		
		// Create the file handler
		$file = fopen($fullName, "rb") or exit("Unable to open file!");  // r+ == read+write; NOT: rb, wb,  
		// $file = fopen($fullName, "w+") or exit("Unable to open file!");   
		
		$fileContent = ''; 
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
				// var_dump($line01); 
				// var_dump('line02'); 
				// var_dump($line02); 
				$boCheckValueInfo = false;  // don't check empty value  
				$arrPair = $this->findPairIDValue($line01, $line02, $boCheckValueInfo);  
				
				if ( isset($arrPair) ) { 
					$key = $arrPair[$this->msgID];  
					if ( isset($this->arrDict[$key]) )
					{  
						// replace current line (=== replace: msgstr... )  
						$line_current = 'msgstr "'. $this->arrDict[$key] . '" ' . PHP_EOL;  
						 
					}
				}
				else { 
					//nothing 
					// $intIgnore = 0;  
				}
				// fwrite($file,$line);
			}
			
			
			$fileContent .= $line_current ;  // current line always msgstr if findPairIDValue != null 
			
		} // END: while   
		
		// Close the file handler
		fclose($file);
		
		
		// move_uploaded_file($fullName, 'upload_files/tmp.po');  
		DEFINE('TMP_FILENAME', 'upload_files/tmp.po'); 
		// $tmpFileName = ; 
		file_put_contents(TMP_FILENAME, '');  // empty file PO  
		$file = fopen(TMP_FILENAME, 'wb', 1);  
		fwrite($file, $fileContent);  
		fclose($file); 
		
		// //download to client  
		
		// header("Content-disposition: attachments;filename=merge_two_po_files.po");  
		// header("Content-disposition: attachments;filename=merge_two_po_files.po");  
		
		require_once('download_to_client.php'); 
		
		  
		return $fileContent; 
		
	}
	
	
	/*
	 * readFileToDictionary
	 *
	 * @author: phong.nguyen@egany.com 20150828 
	 * @return: string fileContent | create array $this->arrDict like dictionary  
	 */
	public function readFileToDictionary($fullName) {  
				
		// ref:http://stackoverflow.com/questions/4894817/read-and-write-to-the-same-file  
		$file = fopen($fullName, "r+") or exit("Unable to open file!"); 
		$fileContent = ''; 
		$count = 0; 
		$line01 = ''; 
		$line02 = ''; 
		$intIgnore = 0;  // LOCs for ignoring  
		while (!feof($file)) {
			$count++; 
			$line_current = fgets($file);
			$intIgnore-- ; // ==== $intIgnore - 1;   
			$fileContent .= $line_current; 
			
			//don't check 2 first lines 
			if ( $count > 1 && $intIgnore < 0 ) {   
				$line01 = $line02;  
				$line02 = $line_current; 
				
				
				$boCheckValueInfo = true; //check empty Value... then compose Dictionary  
				$arrPair = $this->findPairIDValue($line01, $line02, $boCheckValueInfo);  
				
				if ( isset($arrPair) ) { 
					// // 
					// var_dump($arrPair);  
					$this->arrDict[$arrPair[$this->msgID]] = $arrPair[$this->msgValue];  
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
		 
		fclose($file); 
		
		return $fileContent;  
	}
	
	
	/*
	 * findPairIDValue... 
	 * condition msgid: 
	 * + be 5 first chars; 
	 * + remove first line.   
	 * + cut off: [empty space], '', "" at front&rear 
	 *
	 * @author: phong.nguyen@egany.com 20150828 
	 * @param: string line01
	 * @param: string line02
	 * @param: boolean boCheckLine02Empty 
	 */
	public function findPairIDValue($line01, $line02, $boCheckLine02Empty) { 
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
		
		//check info 
		$boCheckIDInfo = ($strID == $this->msgID) && ($strIDContent != false) && ($strIDContent != '');  
		$boCheckValueInfo = ($strValue == $this->msgValue)  && ( $boCheckLine02Empty ? ($strValueContent != false) && ($strValueContent != '') : true);  
		
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




