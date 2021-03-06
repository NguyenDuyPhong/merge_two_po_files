<?php  
//ref: http://stackoverflow.com/questions/11262338/how-to-download-a-file-through-my-server-to-the-client-php

ob_start();
// put some business here 
// $TMP_FILENAME = '../web/upload_files/tmp.po';  
if(file_exists(TMP_FILENAME))
{
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=merge_two_po_files.po'); // basename('tmp.po')  
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize(TMP_FILENAME));
	ob_clean();

	readfile(TMP_FILENAME); 
	exit;
}

