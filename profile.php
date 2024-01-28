<?php 
    include "classes/pokemon.php";
    include "classes/user.php";
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }
    $repos = new PokemonRepository();
    $pokemon = $repos->all();
    if (isset($_POST['logout'])) {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_money']);
        unset($_SESSION['user_cards']);
    
        header("Location: login.php");
        exit();
    }
    if(isset($_POST['newcard'])){
        header("Location: newcard.php");
    }
    $userRepos = new UserRepository();
    $users = $userRepos->all();
    foreach($_SESSION['user_cards'] as $id => $card){
        if(isset($_POST['sell_' . $card])){
            $price = ceil($pokemon[$card]->price*0.9);
            $_SESSION['user_money'] += $price;
            unset($users[$_SESSION['user_id']]->cards[$id]);
            //$users[$_SESSION['user_id']]->cards = array_values()
            unset($_SESSION['user_cards'][$id]);
            $_SESSION['user_cards'] = array_values($_SESSION['user_cards']);
            $userRepos->updateCards($users[$_SESSION['user_id']],array_values($users[$_SESSION['user_id']]->cards));
            $userRepos->updateMoney($users[$_SESSION['user_id']],$_SESSION['user_money']);
            $admincard = array_push($users['admin']->cards,$card);
            $userRepos->updateCards($users['admin'],$users['admin']->cards);
            header("Location: profile.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/profile.css">
    <link rel="stylesheet" href="styles/cards.css">
</head>
<body>
    <header>
        <?php echo '<h1><a href="index.php">IKémon</a> > Home <div class="login">
        <a href="profile.php" class="login">'.$_SESSION['user_id'].'</a><p class="money">🪙'.$_SESSION['user_money'].'</p></div>
        </h1>' ?>
    </header>
    <div class="userDetails">
        <h2>Profile details</h2>
        <table class="profileTable">
            <tr>
                <th>Username:</th>
                <?php 
                    echo "<td>". $_SESSION['user_id'] . "</td>";
                ?>
            </tr>
            <tr>
                <th>Email address:</th>
                <?php 
                    echo "<td>". $_SESSION['user_email'] . "</td>";
                ?>
            </tr>
            <tr>
                <th>Money:</th>
                <?php 
                    echo "<td>". $_SESSION['user_money'] . "</td>";
                ?>
            </tr>
            <tr>
                <th>Your cards:</th>
            </tr>
        </table>
        <div id="content" style="padding-top:0";>
        <div id="card-list" style="background-color:rgba(221, 221, 222, 0.807); border-bottom-left-radius:6px; border-bottom-right-radius:6px";>
        <?php
            foreach ($_SESSION['user_cards'] as $card) {
                if(array_key_exists($card,$pokemon)){
                    $poke=$pokemon[$card];}
                $pricepercent = ceil($poke->price*0.9);
                echo '<div class="pokemon-card">
                        <div class="image clr-' . $poke->type . '">
                            <img src="' . $poke->image . '">
                        </div>
                        <div class="details">
                            <h2><a href="details.php?id=' . $card. '">' . $poke->name . '</a></h2>
                            <span class="card-type"><span class="icon">🏷</span> ' . $poke->type . '</span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span> ' . $poke->hp . '</span>
                                <span class="card-attack"><span class="icon">⚔</span> ' . $poke->attack . '</span>
                                <span class="card-defense"><span class="icon">🛡</span> ' . $poke->defense . '</span>
                            </span>
                        </div>';
                if($_SESSION['user_id']!="admin"){
                    echo 
                        '<div class="sell">
                        <form id="sell" method="post">
                            <span class="card-price"><input type="submit" name="sell_'.$card.'" class="icon" value="💸 '.$pricepercent.'"></span>
                            </form>
                        </div>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php 
            if($_SESSION['user_id']=="admin"){
                echo '<form method="post">
                <div>
                <input type="submit" name="newcard" value="Create new card">
                <input type="submit" name="logout" value="Log out">
                </div>
                </form>';
            }
            else{
                echo '<form method="post">
                <input type="submit" name="logout" value="Log out">
                </form>';
            }
        ?>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>