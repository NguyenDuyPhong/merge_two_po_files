<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		 
		<link rel="stylesheet" href="assets/bootstrap-3.3.2/css/bootstrap.css" />
		<title>Merge two PO files</title>
    </head> 
	<body>  
	
    <div class="container">
    <div class="header">
		<h1>Merge two PO files</h1>
        <p>Finding common line [msgid] in two files and replace [msgstr] the next line</p>    
        <p>This's solution for the same problem, <a href="http://stackoverflow.com/questions/21567173/find-common-line-in-two-files-and-replace-the-next-line-of-first-file-with-the-n" target="_blank">see here </a> </p>    
		
	</div>
    <div class="row "> 
		<div class="col-md-12">
		<hr class=""/>
		<p>English : Hello world! </p>
		<p>Chinese Text : &#24744;&#22909;&#19990;&#30028; </p>
        <p>Indian text : &#2361;&#2375;&#2354;&#2379; &#2357;&#2367;&#2358;&#2381;&#2357; </p> 
		</div>
	</div>
	
	<!-- form action for merging files  -->
	<!-- // http://stackoverflow.com/questions/10694050/how-does-one-upload-a-txt-file-in-php-and-have-it-read-line-by-line-on-another -->
	<form action="../source/merge_two_po_files.php" method="post" enctype="multipart/form-data" >
    <div class="row ">   
        <div class="col-md-12">
			<hr class=""/>
		</div>
        <div class="col-md-12">
			<p>Choose any files for working</p>  
		</div>
        <div class="col-md-12">
			<div class="row ">   
			<div class="col-md-2">
				<p>Choose PO file 1st</p>
			</div>
			<div class="col-md-8">
				<input type="file" name="file01"></input> 
			</div>
			</div>
		</div>
        <div class="col-md-12"> 
			<div class="row ">   
			<div class="col-md-2">
				<p>Choose PO file 2nd</p>
			</div>
			<div class="col-md-8">
				<input type="file" name="file02"></input> 
			</div> 
			</div> 
		</div>
		
        <div class="col-md-12"> 
			<input type="submit" value="Submit"></input> 
		</div>
		
	</div> <!-- END: row --> 
	</form> 
	
	
    <div class="row footer">
	
		<div class="col-md-12">
			<hr class=""/>
        <p>Author: Nguyễn Duy Phong 
		</p>   
		<ul>   
			<li><a href="https://facebook.com/NguyenDuyPhong">Facebook</a></li>
			<li><a href="https://plus.google.com/+duyphongnguyen88">Google+</a></li>
			<li><a href="#">LinkedIn</a></li>
			<li><a href="#">Twitter</a></li>
			<li><a href="http://nguyenduyphong88.blogspot.com">Blog</a></li> 
		</ul>    
		<p>Copyright © 2015 EGANY Ltd. ; Powered by <a href="http://egany.com" target="_blank">EGANY.com</a></p>

		</div>
	</div> <!-- END: footer -->
	
	</div> <!-- END: container -->
         
		
		
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="../../dist/js/bootstrap.min.js"></script>
    </body>
</html>