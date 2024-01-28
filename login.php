<?php 
    include "classes/user.php";
    session_start();
    $repos = new UserRepository();
    $users = $repos->all();
    $user = "";
    $errorsLogIn = [];
    $errors = [];
    $usn = $_POST['loginusername']?? '';
    $psw = $_POST['loginpassword'] ?? '';
    $reguser = $_POST['signupusername'] ?? '';
    $regemail = $_POST['email'] ?? '';
    $regpsw = $_POST['signuppassword'] ?? '';
    $pswagain = $_POST['passwordagain'] ?? '';
    if (isset($_POST['login'])) {
        if (empty($_POST['loginusername'])) {
            $errorsLogIn[] = 'Username is required';
        }
        else if(!array_key_exists($usn,$users)){
            $errorsLogIn[] = 'There is no profile with this username.';
        }
        else{
            $user=$users[$usn];
        }
        if(empty($_POST['loginpassword'])){
            $errorsLogIn[] = 'Password is required';
        }
        else if($user->password !== $psw){
            $errorsLogIn[] = 'Incorrect password';
        }
        else {
            $user=$users[$usn];
            unset($_POST['loginusername']);
            unset($_POST['loginpassword']);
            $_SESSION['user_id'] = $usn;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_money'] = $user->money;
            $_SESSION['user_cards'] = $user->cards;
            header("Location: profile.php");
            exit();
        }
    }
    if (isset($_POST['signup'])) {
        if (empty($_POST['signupusername'])) {
            $errors[] = 'Username is required';
        }
        else if(array_key_exists($reguser,$users)){
            $errors[] = 'This username is taken.';
        }
        if(empty($_POST['email'])){
            $errors[] = 'Email is required.';
        }
        else if(!filter_var($regemail, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'The email format is incorrect.';
        }
        if(empty($_POST['signuppassword'])){
            $errors[] = 'Password is required';
        }
        if(empty($_POST['passwordagain'])){
            $errors[] = 'Password again is required.';
        }
        else if($pswagain !== $regpsw){
            $errors[] = 'The passwords do not match.';
        }
        if(count($errors)==0) {
            $newUser = new User($regemail,$regpsw,1500,[]);
            $repos->add($newUser,$reguser);
            $_SESSION['user_id'] = $reguser;
            $_SESSION['user_email'] = $newUser->email;
            $_SESSION['user_money'] = $newUser->money;
            $_SESSION['user_cards'] = $newUser->cards;
            header("Location: profile.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In / Sign Up</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <header>
        <h1><a href="index.php">IKémon</a> > Home <a href="login.php" class="login">Log in / Sign up</a></h1>
    </header>
    <div class="columns">
        <div class="column">
            <h2>Log In</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-25">
                        <label for="loginusername">Username</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="loginusername" name="loginusername">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="loginpassword">Password</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="loginpassword" name="loginpassword">
                    </div>
                </div>
                <div class="row">
                    <input type="submit" name="login" value="Log In">
                </div>
                <div id="errors">
                    <ul>
                        <?php foreach ($errorsLogIn as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </form>
        </div>
        <div class="column">
            <h2>Sign Up</h2>
            <form method="post">
            <div class="row">
                    <div class="col-25">
                        <label for="signupusername">Username</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="signupusername" name="signupusername">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="email">Email Address</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="email" name="email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="signuppassword">Password</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="signuppassword" name="signuppassword">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="passwordagain">Password Again</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="passwordagain" name="passwordagain">
                    </div>
                </div>
                <div class="row">
                    <input type="submit" name="signup" value="Sign Up">
                </div>
                <div id="errors">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>