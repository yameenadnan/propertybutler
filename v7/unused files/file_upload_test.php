<?php 

echo $path = '../UploadImages/bms_uploads/atten_captures/';    
//mkdir($path);
/*mkdir($path.'property_docs/');
mkdir($path.'task_uploads/');*/

if(isset($_FILES) && count($_FILES)) {  
    //mkdir($path.date('m.d.Y').'/');
    move_uploaded_file($_FILES['webcam']['tmp_name'], $path.date('m.d.Y').'/'.$_FILES['webcam']['name']);
}   
?><!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lolkittens" />

	<title>File Upload Test</title>
</head>

<body>

<form role="form" id="task_new" action="" method="post" enctype="multipart/form-data">
<input type="file" name="webcam" /> <br /><br />
<input type="submit" value="Submit" />

</form>

</body>
</html>