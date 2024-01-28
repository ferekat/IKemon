<?php 
    include "classes/pokemon.php";
    include "classes/user.php";
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }
    if($_SESSION['user_id']!="admin"){
        header("Location: index.php");
        exit();
    }
    $repos = new PokemonRepository();
    $users = new UserRepository();
    $user = $users->all();
    $pokemon = $repos->all();
    $cardID = "card".count($pokemon);
    $userID = "admin";
    $errors=[];
    if(isset($_POST['create'])){
        if(empty($_POST['pokename'])){
            $errors[] = "The name is required.";
        }
        if(empty($_POST['hp'])){
            $errors[] = "The HP is required.";
        }
        else if($_POST['hp']<1 || $_POST['hp'] > 340){
            $errors[] = "The HP must be between 1 and 340.";
        }
        if(empty($_POST['attack'])){
            $errors[] = "The attack is required.";
        }
        else if($_POST['attack']<1 || $_POST['attack'] > 100){
            $errors[] = "The attack must be between 1 and 100.";
        }
        if(empty($_POST['defense'])){
            $errors[] = "The defense is required.";
        }
        else if($_POST['defense']<1 || $_POST['defense'] > 100){
            $errors[] = "The defense must be between 1 and 100.";
        }
        if(empty($_POST['price'])){
            $errors[] = "The price is required.";
        }
        else if($_POST['price']<100 || $_POST['price'] > 1000){
            $errors[] = "The price must be between 100 and 1000.";
        }
        if(empty($_POST['image'])){
            $errors[] = "The image link is required.";
        }
        if(empty($_POST['description'])){
            $errors[] = "The description is required.";
        }
        if(count($errors)==0){
            $nextPoke = new Pokemon($_POST['pokename'],$_POST['type'],$_POST['hp'],$_POST['attack'],$_POST['defense'],$_POST['price'],$_POST['description'],$_POST['image']);
            $repos->add($nextPoke,$cardID);
            //array_push($_SESSION['user_cards'],$cardID);
            //$users->updateCards($user[$userID],$_SESSION['user_cards']);
            header("Location: index.php");
            
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create new card</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/newcard.css">
</head>
<body>
    <header>
    <?php echo '<h1><a href="index.php">IKémon</a> > Home <div class="login">
        <a href="profile.php" class="login">'.$_SESSION['user_id'].'</a><p class="money">🪙'.$_SESSION['user_money'].'</p></div>
        </h1>' ?>
    </header>
    <div class="userDetails">
        <h2>Create new card</h2>
            <form method="post" action="" enctype="multipart/form-data">
            <table class="profileTable">
            <tr>
                <th>Card ID:</th>
                <td><?php echo "<p>".$cardID."</p>"?></td>
            </tr>
            <tr>
                <th>Name:</th>
                <td><input type="text" id="pokename" name="pokename"></td>
            </tr>
            <tr>
                <th>Type:</th>
                <td>
                    <select name="type" id="type">
                        <option value="normal">Normal</option>
                        <option value="fire">Fire</option>
                        <option value="water">Water</option>
                        <option value="electric">Electric</option>
                        <option value="grass">Grass</option>
                        <option value="ice">Ice</option>
                        <option value="figthing">Fighting</option>
                        <option value="poison">Poison</option>
                        <option value="ground">Ground</option>
                        <option value="psychic">Psychic</option>
                        <option value="bug">Bug</option>
                        <option value="rock">Rock</option>
                        <option value="ghost">Ghost</option>
                        <option value="dark">Dark</option>
                        <option value="steel">Steel</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>HP:</th>
                <td><input type="number" id="hp" min="1" max="340" name="hp"></td>
            </tr>
            <tr>
                <th>Attack:</th>
                <td><input type="number" id="attack" min="1" max="100" name="attack"></td>
            </tr>
            <tr>
                <th>Defense:</th>
                <td><input type="number" id="defense" min="1" max="100" name="defense"></td>
            </tr>
            <tr>
                <th>Price:</th>
                <td><input type="number" id="price" min="100" max="1000" name="price"></td>
            </tr>
            <tr>
                <th>Image link:</th>
                <td><input type="text" id="image" name="image"></td>
            </tr>
            <tr>
                <th>Description:</th>
                <td><textarea id="description" name="description" maxlength="500" cols="60" rows="10"></textarea></td>
            </tr>
        </table>
        <input type="submit" name="create" value="Create new card">
        <div id="errors">
                    <ul>
                        <?php foreach ($errors as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
        </form>
    </div>
</body>
</html>