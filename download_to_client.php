<?php  
//ref: http://stackoverflow.com/questions/11262338/how-to-download-a-file-through-my-server-to-the-client-php

ob_start();
// put some business here 
$file = 'upload_files/tmp.po';  
if(file_exists($file))
{
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=merge_two_po_files.po'); // basename('tmp.po')  
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();

	readfile($file); 
	exit;
}

