<?php
require 'partials/_dbconnect.php';
$logged = false;
session_start();
if (!isset($_SESSION['loggedin']) or $_SESSION['loggedin'] != true) {
  header('location: login.php');
  exit;
} else {
  $logged = true;
}

$edited = false;
$inserted = false;
$delete = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['snoEdit'])){

    $sno = $_POST['snoEdit'];
    $title = $_POST['editTitle'];
    $description = $_POST['descriptionEdit'];

    $editSql = "UPDATE " .$_SESSION['table']. " SET `title` = '$title', `description` = '$description' WHERE `s.no` = '$sno'";
    $result = mysqli_query($conn, $editSql);

    if($result){
      $edited = true;
    }

  } else {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $insertSql = "INSERT INTO " . $_SESSION['table'] . " ( title, description, dt ) VALUES ( '$title', '$desc', current_timestamp())";
    $result = mysqli_query($conn, $insertSql);
    if ($result) {
      $inserted = true;
    }
  }
}

if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $deleteSql = "DELETE FROM " . $_SESSION['table'] . " WHERE `s.no` = '$sno'";
  $result = mysqli_query($conn, $deleteSql);
  if ($result) {
    $delete = true;
  }
}


?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Todo Notes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>

<body>
  <?php require 'partials/_nav.php'; ?>
  <?php require 'partials/_dbconnect.php'; ?>
  <?php
  if ($logged) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Congratulations!</strong> You have been logged in.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  }
  if ($inserted) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> Your note has been added.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  }
  if ($edited) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> Your note has been edited successfully.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  }
  if ($delete) {
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
              <strong>Success!</strong> Your note has been deleted successfully.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  }
  ?>

  <div class="container my-4">
    <h1 style="font-family: Verdana">Hello, <?php echo ucfirst($_SESSION['table']) ?></h1>
    <h2 style="font-family: Verdana;">Here to add your note.</h2> <br>
    <div class="tablecontainer">
      <form action="home.php" method="post">
        <div class="mb-3">
          <label for="title" class="form-label">Note Title</label>
          <input name="title" type="text" class="form-control" id="title">
        </div>
        <div class="mb-3">
          <label for="desc" class="form-label bold">Description</label>
          <textarea name="description" class="form-control" id="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Note</button>

      </form>
    </div>
    <br>
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">Sr.No.</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($logged) {
          $tableSql = "CREATE TABLE IF NOT EXISTS " . $_SESSION['table'] . "( `s.no` INT NOT NULL AUTO_INCREMENT ,`title` VARCHAR(30) NOT NULL,`description` TEXT NOT NULL , `dt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`s.no`))";

          $result = mysqli_query($conn, $tableSql);

          $readSql = "SELECT * FROM " . $_SESSION['table'];
          $result = mysqli_query($conn, $readSql);
          $sno = 0;
          while ($row = mysqli_fetch_assoc($result)) {
            $sno++;
            echo '<tr> 
                    <th scope="row">' . $sno . '</th>
                    <td>' . $row['title'] . '</td>
                    <td>' . $row['description'] . '</td>
                    
                    <td><button class="delete btn btn-sm btn-primary" id=d' . $row['s.no'] . '>Delete</button> 
                    <button type="button" class="edit btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" id=' . $row['s.no'] . '>Edit</button></td>
                    </tr>';
          }
        }


        ?>
      </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Note</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="home.php" method="post">
              <input type="hidden" name="snoEdit" id="snoEdit">
              <div class="mb-3">
                <label for="editTitle" class="form-label" id="editModalTitle">Note Title</label>
                <input name="editTitle" type="text" class="form-control" id="editTitle">
              </div>
              <div class="mb-3">
                <label for="descriptionEdit" class="form-label bold">Description</label>
                <textarea name="descriptionEdit" class="form-control" id="descriptionEdit" rows="3"></textarea>
              </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>\
  <script>
    let deletes = document.getElementsByClassName('delete')
    Array.from(deletes).forEach((element) => {
      element.addEventListener('click', (e) => {
        sno = e.target.id.substr(1, );
        if (confirm("Do you really want to delete this note?")) {
          window.location = `home.php?delete=${sno}`;
        }
      })
    })

    let edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach(element => {
      element.addEventListener('click', e => {
        let sno = e.target.id;
        let tr = e.target.parentNode.parentNode;
        let tds = tr.getElementsByTagName('td');
        let title = tds[0].innerText;
        let description = tds[1].innerText;
        document.getElementById('editTitle').value = title;
        document.getElementById('descriptionEdit').value = description;
        snoEdit.value = e.target.id;
      })
    })
  </script>
</body>

</html>
