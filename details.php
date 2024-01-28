<?php
    include 'classes/pokemon.php';
    session_start();
    $repos = new PokemonRepository();
    $pokemon = $repos->all();
    $isIDSet = false;
    $doesKeyExist = false;
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $isIDSet = true;
        if(array_key_exists($id, $pokemon)){
            $poke=$pokemon[$id];
            $doesKeyExist = true;
        }
    }
    $site = '<a href="login.php" class="login">Log in / Sign up</a>';
    if(isset($_SESSION['user_id'])){
        $site='<div class="login">
        <a href="profile.php" class="login">'.$_SESSION['user_id'].'</a><p class="money">🪙'.$_SESSION['user_money'].'</p></div>';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        if($isIDSet && $doesKeyExist){ 
            echo "<title>IKémon | $poke->name </title>";
        }
        else{
            echo "<title>IKémon | Poke-non </title>";
        }
    ?>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<?php 
    echo '<body>';
?>
    <header>
    <?php 
        if($isIDSet && $doesKeyExist){
            echo "<h1><a href='index.php'>IKémon </a> > $poke->name " . $site . "</h1>";
        }
        else{
            echo "<h1><a href='index.php'>IKémon </a> > Poke-non " . $site ."</h1>";
        }
    ?>
    </header>
    <div id="content">
        <div id="details">
            <?php

            if ($isIDSet) {
                if ($doesKeyExist) {
                    echo '<div class="image clr-'.$poke->type.'">
                            <img src="' . $poke->image . '" alt="">
                        </div>
                        <div class="info">
                            <div class="description">' . $poke->description . '</div>
                            <span class="card-type"><span class="icon">🏷</span> Type: ' . $poke->type . '</span>
                            <div class="attributes">
                                <div class="card-hp"><span class="icon">❤</span> Health: ' . $poke->hp . '</div>
                                <div class="card-attack"><span class="icon">⚔</span> Attack: ' . $poke->attack . '</div>
                                <div class="card-defense"><span class="icon">🛡</span> Defense: ' . $poke->defense . '</div>
                            </div>
                        </div>';
                } else {
                    echo '<p>Sorry, this Pokemon is a Poke-none.</p>';
                }
            } else {
                echo '<p>Poke-no-ID.</p>';
            }
            ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>
