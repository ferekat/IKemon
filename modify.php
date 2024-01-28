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

    foreach($pokemon as $pokeID => $poke){
        if($pokeID === $_SESSION['modify_card']){
            $modiPoke = $poke;
        }
    }
    $userID = "admin";
    $errors=[];
    if(isset($_POST['modify'])){
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
            $repos->updateName($modiPoke,$_POST['pokename']);
            $repos->updateType($modiPoke,$_POST['type']);
            $repos->updateHP($modiPoke,$_POST['hp']);
            $repos->updateAttack($modiPoke,$_POST['attack']);
            $repos->updateDefense($modiPoke,$_POST['defense']);
            $repos->updatePrice($modiPoke,$_POST['price']);
            $repos->updateImage($modiPoke,$_POST['image']);
            $repos->updateDesc($modiPoke,$_POST['description']);
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
    <title>Modify card</title>
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
        <h2>Modify card</h2>
            <form method="post" action="" enctype="multipart/form-data">
            <table class="profileTable">
            <tr>
                <th>Card ID:</th>
                <td><?php echo "<p>".$_SESSION['modify_card']."</p>"?></td>
            </tr>
            <tr>
                <th>Name:</th>
                <?php echo
                '<td><input type="text" id="pokename" name="pokename" value="'.$modiPoke->name.'"></td>
            </tr>
            <tr>
                <th>Type:</th>
                <td>
                    <select name="type" id="type">
                        <option value="normal"';if($modiPoke->type === "normal"){echo "selected";} echo '>normal</option>
                        <option value="fire"';if($modiPoke->type === "fire"){echo "selected";} echo '>fire</option>
                        <option value="water"';if($modiPoke->type === "water"){echo "selected";} echo '>water</option>
                        <option value="electric"';if($modiPoke->type === "electric"){echo "selected";} echo '>electric</option>
                        <option value="grass"';if($modiPoke->type === "grass"){echo "selected";} echo '>grass</option>
                        <option value="ice"';if($modiPoke->type === "ice"){echo "selected";} echo '>ice</option>
                        <option value="figthing"';if($modiPoke->type === "fighting"){echo "selected";} echo '>fighting</option>
                        <option value="poison"';if($modiPoke->type === "poison"){echo "selected";} echo '>poison</option>
                        <option value="ground"';if($modiPoke->type === "ground"){echo "selected";} echo '>ground</option>
                        <option value="psychic"';if($modiPoke->type === "psychic"){echo "selected";} echo '>psychic</option>
                        <option value="bug"';if($modiPoke->type === "bug"){echo "selected";} echo '>bug</option>
                        <option value="rock"';if($modiPoke->type === "rock"){echo "selected";} echo '>rock</option>
                        <option value="ghost"';if($modiPoke->type === "ghost"){echo "selected";} echo '>ghost</option>
                        <option value="dark"';if($modiPoke->type === "dark"){echo "selected";} echo '>dark</option>
                        <option value="steel"';if($modiPoke->type === "steel"){echo "selected";} echo '>steel</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>HP:</th>
                <td><input type="number" id="hp" min="1" max="340" name="hp" value="'.$modiPoke->hp.'"></td>
            </tr>
            <tr>
                <th>Attack:</th>
                <td><input type="number" id="attack" min="1" max="100" name="attack" value="'.$modiPoke->attack.'"></td>
            </tr>
            <tr>
                <th>Defense:</th>
                <td><input type="number" id="defense" min="1" max="100" name="defense" value="'.$modiPoke->defense.'"></td>
            </tr>
            <tr>
                <th>Price:</th>
                <td><input type="number" id="price" min="100" max="1000" name="price" value="'.$modiPoke->price.'"></td>
            </tr>
            <tr>
                <th>Image link:</th>
                <td><input type="text" id="image" name="image" value="'.$modiPoke->image.'"></td>
            </tr>
            <tr>
                <th>Description:</th>
                <td><textarea id="description" name="description" maxlength="500" cols="60" rows="10">'.$modiPoke->description.'</textarea></td>
            </tr>
        </table>'
        ?>
        <input type="submit" name="modify" value="Modify card">
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