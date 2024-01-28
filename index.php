<?php
include "classes/pokemon.php";
include "classes/user.php";
session_start();
$repos = new PokemonRepository();
$userRepos = new UserRepository();
$pokemon = $repos->all();
$users = $userRepos->all();
$isLoggedIn = false;
$isadmin = false;
if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
    if ($_SESSION['user_id'] === "admin") {
        $isadmin = true;
        foreach ($pokemon as $id => $poke) {
            if (isset($_POST['modify_' . $id])) {
                $_SESSION['modify_card'] = $id;
                header("Location: modify.php");
            }
        }
    }
}
$types = array_unique(array_column($pokemon, 'type'));
foreach ($pokemon as $id => $poke) {
    if (isset($_POST['buy_' . $id])) {
        if ($poke->price <= $_SESSION['user_money'] && count($_SESSION['user_cards']) < 5 && $_SESSION['user_id'] != "admin") {
            $_SESSION['user_money'] -= $poke->price;
            $usercards = array_push($_SESSION['user_cards'], $id);
            foreach ($users['admin']->cards as $cardID => $userCard) {
                if ($userCard === $id) {
                    unset($users['admin']->cards[$cardID]);
                }
            }

            $userRepos->updateCards($users[$_SESSION['user_id']], $_SESSION['user_cards']);
            $userRepos->updateMoney($users[$_SESSION['user_id']], $_SESSION['user_money']);
            $userRepos->updateCards($users['admin'], array_values($users['admin']->cards));

            header("Location: profile.php");
        }
    }
}
if(isset($_POST['random'])){
    if (200 <= $_SESSION['user_money'] && count($_SESSION['user_cards']) < 5 && $_SESSION['user_id'] != "admin"){
    $random = rand(0,count($users['admin']->cards));

    $_SESSION['user_money'] -= 200;
    array_push($_SESSION['user_cards'],$users['admin']->cards[$random]);
    unset($users['admin']->cards[$random]);


    $userRepos->updateCards($users[$_SESSION['user_id']], $_SESSION['user_cards']);
    $userRepos->updateMoney($users[$_SESSION['user_id']], $_SESSION['user_money']);
    $userRepos->updateCards($users['admin'], array_values($users['admin']->cards));
    header("Location: profile.php");
    }
}

$page = isset($_GET['page']) ? max(1, $_GET['page']) : 1;
$pageNum = 9;
$allCards = count($pokemon);
$allPages = ceil($allCards / $pageNum);
$first = ($page - 1) * $pageNum;
$last = $first + $pageNum;
$displayedCards = array_slice($pokemon, $first, $pageNum, true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>

<body>
    <header>
        <?php
        if ($isLoggedIn) {
            echo '<h1><a href="index.php">IKémon</a> > Home <div class="login">
            <a href="profile.php" class="login">' . $_SESSION['user_id'] . '</a><p class="money">🪙' . $_SESSION['user_money'] . '</p></div>
            </h1>';
        } else echo '<h1><a href="index.php">IKémon</a> > Home <a href="login.php" class="login">Log in / Sign up</a></h1>';
        ?>
    </header>
    <div class="random-card">
            <?php
            if(!$isadmin && $isLoggedIn){
                echo
            '<form method="post" id="random-form">
                <label for="random" style="font-weight:bold">Buy random card:</label>
                <input type="submit" id="random" name="random" class="icon" value="💰 200">
            </form>';}
            ?>
        <label for="types">Select the type:</label>
        <select id="types" onchange="filterTypes()">
            <option value="selectall">Select all</option>
            <?php
            foreach ($types as $type) {
                echo "<option value=" . $type . ">" . $type . "</option>";
            }
            ?>
        </select>
        </div>
    <div id="content">
        <div id="card-list">
            <?php
                foreach ($pokemon as $id => $pokes) {
                        echo '<div class="pokemon-card" data-type="' . $pokes->type . '">
                        <div class="image clr-' . $pokes->type . '">
                            <img src="' . $pokes->image . '">
                        </div>
                        <div class="details">
                            <h2><a href="details.php?id=' . $id . '">' . $pokes->name . '</a></h2>
                            <span class="card-type"><span class="icon">🏷</span> ' . $pokes->type . '</span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span> ' . $pokes->hp . '</span>
                                <span class="card-attack"><span class="icon">⚔</span> ' . $pokes->attack . '</span>
                                <span class="card-defense"><span class="icon">🛡</span> ' . $pokes->defense . '</span>
                            </span>
                        </div>';
                        $hasadmin = false;
                        foreach($users['admin']->cards as $cID => $card){
                            if($card == $id){
                                $hasadmin = true;
                                break;
                            }
                        }
                        if ($isLoggedIn && !$isadmin && $hasadmin) {
                            echo '<div class="buy">
                    <form id="buy" method="post">
                            <span class="card-price"><input type="submit" name="buy_' . $id . '" class="icon" value="💰' . $pokes->price . '"></span>
                            </form>
                        </div>';
                        }
                        if ($isadmin && $hasadmin) {
                            echo '<div class=buy>
                    <form id="modify" method="post">
                            <span class="card-price"><input type="submit" name="modify_' . $id . '" class="icon" value="Modify card"></span>
                            </form>
                    </div>';
                        }
                        if(!$hasadmin && !$isadmin && $isLoggedIn){
                            echo
                            '<div class="sold">
                            <span class="card-price">
                            <span class="icon">🚫</span> '. $pokes->price.'</span>
                            </div>';
                        }
                        if(!$hasadmin && $isadmin && $isLoggedIn){
                            echo
                            '<div class="sold">
                            <span class="card-price">
                            <span class="icon">🚫</span></span>
                            </div>';
                        }
                        echo    '</div>';
                    }
            ?>
        </div>
        <!--<div id="pagination">
            <?php
            for ($i = 1; $i <= $allPages; $i++) {
                echo '<a id="page-turn" href="?page=' . $i . '">' . $i . '</a>';
            }
            ?>
        </div>-->
        <script>
            function filterTypes() {
                var selectedType = document.getElementById('types').value;
                var pokes = document.querySelectorAll('.pokemon-card');

                pokes.forEach(function(poke) {
                    var pokeType = poke.dataset.type;

                    if (selectedType === 'selectall' || pokeType === selectedType) {
                        poke.style.display = 'block';
                    } else {
                        poke.style.display = 'none';
                    }
                });
            }
        </script>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>