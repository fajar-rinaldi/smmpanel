<?php
require_once '../../../mainconfig.php';

$id = $_GET['id'];
if(!isset($id))
    die('No direct script access allowed!');
$query = mysqli_query($db, "SELECT * FROM information WHERE id = '$id'");
$q = mysqli_fetch_assoc($query); 
?>

<form method="POST">
<div class="row">
      <div class="col-12 mt-2">
            <blockquote class="blockquote pl-1 border-left-primary border-left-3">
                <p class="mb-0">Apakah anda yakin untuk menghapus news ini?</p>
                <hr>
        <div class="form-group">
        <label>Content</label>
        <textarea class="form-control" name="content" rows="7" readonly><?= html_entity_decode($q['content']); ?></textarea>
    </div>
           </blockquote>    
        </div>

<div class="col-md-6 col-12 mt-2 text-center">
            <button type="button" class="btn btn-block btn-danger" data-dismiss="modal">BACK</button>
        </div><div class="col-md-6 col-12 mt-2 mb-2 text-center">
            <button type="submit" name="delete" class="btn btn-block btn-success">DELETE</button>
        </div></div>
        </form>
