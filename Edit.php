
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">
<?php

require_once "connection.php";

if(isset($_REQUEST['update_id']))
{
    try
    {
        $id = $_REQUEST['update_id']; //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
        $select_stmt = $db->prepare('SELECT * FROM tbl_file WHERE id =:id'); //sql select query
        $select_stmt->bindParam(':id',$id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);
    }
    catch(PDOException $e)
    {
        $e->getMessage();
    }

}

if(isset($_REQUEST['btn_update']))
{
    try
    {
        $name =$_REQUEST['txt_name']; //textbox name "txt_name"

        $image_file = $_FILES["txt_file"]["name"];
        $type  = $_FILES["txt_file"]["type"]; //file name "txt_file"
        $size  = $_FILES["txt_file"]["size"];
        $temp  = $_FILES["txt_file"]["tmp_name"];

        $path="upload/".$image_file; //set upload folder path

        $directory="upload/"; //set upload folder path for update time previous file remove and new file upload for next use

        if($image_file)
        {
            if($type=="image/jpg" || $type=='image/jpeg' || $type=='image/png' || $type=='image/gif') //check file extension
            {
                if(!file_exists($path)) //check file not exist in your upload folder path
                {
                    if($size < 5000000) //check file size 5MB
                    {
                        unlink($directory.$row['image']); //unlink function remove previous file
                        move_uploaded_file($temp, "upload/" .$image_file); //move upload file temperory directory to your upload folder
                    }
                    else
                    {
                        $errorMsg="Your File To large Please Upload 5MB Size"; //error message file size not large than 5MB
                    }
                }
                else
                {
                    $errorMsg="File Already Exists...Check Upload Folder"; //error message file not exists your upload folder path
                }
            }
            else
            {
                $errorMsg="Upload JPG, JPEG, PNG & GIF File Formate.....CHECK FILE EXTENSION"; //error message file extension
            }
        }
        else
        {
            $image_file=$row['image']; //if you not select new image than previous image sam it is it.
        }

        if(!isset($errorMsg))
        {
            $update_stmt=$db->prepare('UPDATE tbl_file SET name=:name_up, image=:file_up WHERE id=:id'); //sql update query
            $update_stmt->bindParam(':name_up',$name);
            $update_stmt->bindParam(':file_up',$image_file); //bind all parameter
            $update_stmt->bindParam(':id',$id);

            if($update_stmt->execute())
            {
                $updateMsg="File Update Successfully......."; //file update success message
                header("refresh:3;index.php"); //refresh 3 second and redirect to index.php page
            }
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

}
?>
<form method="post" class="form-horizontal" enctype="multipart/form-data">

    <div class="form-group">
        <label class="col-sm-3 control-label">Name</label>
        <div class="col-sm-6">
            <input type="text" name="txt_name" class="form-control" value="<?php echo $name; ?>" required/>
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-3 control-label">File</label>
        <div class="col-sm-6">
            <input type="file" name="txt_file" class="form-control" value="<?php echo $image; ?>"/>
            <p><img src="upload/<?php echo $image; ?>" height="100" width="100" /></p>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9 m-t-15">
            <input type="submit"  name="btn_update" class="btn btn-primary" value="Update">
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </div>
    </div>

</form>
