
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

if(isset($_REQUEST['delete_id']))
{
    // select image from db to delete
    $id=$_REQUEST['delete_id']; //get delete_id and store in $id variable

    $select_stmt= $db->prepare('SELECT * FROM tbl_file WHERE id =:id'); //sql select query
    $select_stmt->bindParam(':id',$id);
    $select_stmt->execute();
    $row=$select_stmt->fetch(PDO::FETCH_ASSOC);
    unlink("upload/".$row['image']); //unlink function permanently remove your file

    //delete an orignal record from db
    $delete_stmt = $db->prepare('DELETE FROM tbl_file WHERE id =:id');
    $delete_stmt->bindParam(':id',$id);
    $delete_stmt->execute();

    header("Location:index.php");
}

?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th>Name</th>
        <th>File</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $select_stmt=$db->prepare("SELECT * FROM tbl_file"); //sql select query
    $select_stmt->execute();
    while($row=$select_stmt->fetch(PDO::FETCH_ASSOC))
    {
        ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><img src="upload/<?php echo $row['image']; ?>" width="100px" height="60px"></td>
            <td><a href="edit.php?update_id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a></td>
            <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>