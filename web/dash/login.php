<?php
  if (isset($_SESSION['login'])) {
      header("Location: /dashboard/");
      exit;
  }
  // Authentication
  $failed = false;
  if (isset($_POST['sent'])) {
    $conn = new PDO($dsn, $db['user'], $db['pass']);
    $prep = $conn->prepare("SELECT * FROM users WHERE name=:username");
    $prep->bindValue(":username", $_POST['username'], PDO::PARAM_STR);
    $prep->execute();
    $res = $prep->fetchAll(PDO::FETCH_ASSOC);
    if (empty($res)) {
      $failed = true;
    } else {
      $pass = $res[0]['password_hash'];
      if (password_verify($_POST['password'], $pass)) {
        $_SESSION['user'] = $res[0]['name'];
        $_SESSION['user_id'] = $res[0]['id'];
        $_SESSION['login'] = true;
        $prep = $conn->prepare("SELECT * FROM bots WHERE user_id=:userid AND active=1");
        $prep->bindValue(":userid", $res[0]['id'], PDO::PARAM_INT);
        $prep->execute();
        $results = $prep->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($results)) {
          $_SESSION['botname'] = $results[0]['name'];
          $_SESSION['bot_id'] = $results[0]['id'];
        }
        header("Location: /dashboard/");
        exit;
      } else {
        $failed = true;
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content=""/>
        <meta name="author" content=""/>
        <link rel="icon" href="../favicon.ico"/>
        
        <title>EggPanel - Login</title>
        
        <!-- Bootstrap core CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet"/>
        
        <!-- Custom styles for this template -->
        <link href="/css/signin.css" rel="stylesheet"/>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
         <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
         <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
         <![endif]-->
    </head>
    
    <body>
        
        <div class="container">
            <?php if ($failed) { ?>
<h1>Failed</h1>
            <?php } ?> 
            <form action="/dashboard/login" method="post" class="form-signin">
                <input type="hidden" name="sent" value="1">
                <h2 class="form-signin-heading">Please sign in</h2>
                <label for="username" class="sr-only">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" value="remember-me"> Remember me
                                    </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                        </form>
            
        </div> <!-- /container -->
        
        
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
