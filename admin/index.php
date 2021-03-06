<?php require_once '../includes/connection.php' ?>
<?php
  $ques=0;
  $uq = 0;
  $up = 0;
  if(isset($_GET['update_question'])) {
    $uq = 1;
  } elseif(isset($_GET['update_player'])) {
    $up = 1;
  }
?>

<?php
  // Update question
  if(isset($_GET['uq']) && $_GET['uq'] == 1) {
    $id = $_POST['id'];
    $content = addslashes(trim($_POST['content']));
    $query = "UPDATE questions SET content = '{$content}' ";
    $query .= "WHERE id = {$id}";
    mysqli_query($connection, $query);
    if(mysqli_affected_rows($connection)) {
      $success = "Question Updated Successfully";
    }
  }
?>

<?php
  // Update player
  if(isset($_GET['up']) && $_GET['up'] == 1) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $score = $_POST['score'];
    $query = "UPDATE players SET name = '{$name}', score = {$score} ";
    $query .= "WHERE id = {$id}";
    mysqli_query($connection, $query);
    if(mysqli_affected_rows($connection)) {
      $success = "Player Updated Successfully";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>Admin | Ilm</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">ILM</a>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li <?php if((isset($_GET['ques']) && $_GET['ques'] == 1) || !isset($_GET['player']) && !$up) {echo 'class="active"'; $ques=1;}?> ><a href="<?php echo $_SERVER['PHP_SELF'] . '?ques=1' ?>">Questions <span class="sr-only">(current)</span></a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li <?php if((isset($_GET['player']) && $_GET['player'] == 1) || $up) echo 'class="active"';?>><a href="<?php echo $_SERVER['PHP_SELF'] . '?player=1' ?>">Players</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <?php if(isset($success)) {
              echo "<p>{$success}</p>";
            }
          ?>
          <h2 class="sub-header">
            <?php if($ques && !$uq) echo "Questions";
                  elseif(!$ques && !$up) echo "Players";
                  elseif($uq) echo 'Update Question';
                  elseif($up) echo "Update Player";
            ?>
          </h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <?php if($ques && !$uq) { ?>
                <?php
                  $query = "SELECT * FROM questions";
                  $result = mysqli_query($connection, $query);
                  $num =  mysqli_num_rows($result);
                ?>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Content</th>
                    <th>Edit</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for($i=1; $i<=$num; $i++) { ?>
                      <tr>
                        <form action="<?php echo $_SERVER['PHP_SELF']. '?update_question=1' ?>" method="post">
                          <?php $q = mysqli_fetch_array($result, MYSQLI_ASSOC); ?>
                          <td><?php echo $q["id"] ?></td>
                          <td><?php echo stripslashes($q["content"]) ?></td>
                          <input type="hidden" name="id" value="<?php echo $q['id'] ?>">
                          <td><button type="submit" class="btn btn-success">EDIT</button></td>
                        </form>
                      </tr>
                  <?php } mysqli_free_result($result); ?>
                </tbody>
              <?php } elseif(!$ques && !$uq && !$up) { ?>
                <?php
                  $query = "SELECT * FROM players";
                  $result = mysqli_query($connection, $query);
                  $num = mysqli_num_rows($result);
                ?>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Score</th>
                    <th>Edit</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for($i=1;$i<=$num;$i++) { ?>
                    <tr>
                      <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?update_player=1' ?>">
                        <?php $p = mysqli_fetch_array($result, MYSQLI_ASSOC); ?>
                        <td><?php echo $p['id'] ?></td>
                        <td><?php echo $p['name'] ?></td>
                        <td><?php echo $p['score'] ?></td>
                        <input type="hidden" name="id" value="<?php echo $p['id'] ?>">
                        <td><button class="btn btn-success" type="submit">EDIT</button></td>
                      </form>
                    </tr>
                  <?php } ?>
                </tbody>
              <?php } ?>
            </table>
            <?php if($uq) { ?>
              <?php
                $id = $_POST['id'];
                $query = "SELECT * FROM questions WHERE id = {$id}";
                $result = mysqli_query($connection, $query);
                $question = mysqli_fetch_array($result, MYSQLI_ASSOC);
                mysqli_free_result($result);
              ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?uq=1' ?>">
                  <textarea rows="4" cols="100" name="content"><?php echo $question['content']; ?></textarea>
                  <input type="hidden" name="id" value="<?php echo $id ?>">
                  <br><br>
                  <button style="float:left;margin-right:15px;" type="submit" name="uq" class="btn btn-success">Update</button>
                </form>
                <a href="<?php echo $_SERVER['PHP_SELF'] ?>"><button class="btn btn-danger">Cancel</button></a>
            <?php } ?>
            <?php if($up) {?>
              <?php
                $id = $_POST['id'];
                $query = "SELECT * FROM players WHERE id = {$id}";
                $result = mysqli_query($connection, $query);
                $player = mysqli_fetch_array($result, MYSQLI_ASSOC);
                mysqli_free_result($result);
              ?>
              <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?up=1&&player=1' ?>">
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="<?php echo $player['name'] ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="score" class="col-sm-2 control-label">Score</label>
                  <div class="col-sm-10">
                    <input type="score" class="form-control" name="score" value="<?php echo $player['score'] ?>">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button style="float:left;margin-right:15px;" type="submit" class="btn btn-success">Update</button>
                  </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $id ?>">
                </form>
                <a style="margin-left: 180px;" href="<?php echo $_SERVER['PHP_SELF'] . '?player=1' ?>"><button class="btn btn-danger">Cancel</button></a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
