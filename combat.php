<?php
require __DIR__ . "/vendor/autoload.php";

## ETAPE 0

## CONNECTEZ VOUS A VOTRE BASE DE DONNEE

try {
    $pdo = new PDO('mysql:host=localhost;dbname=rendu;charset=utf8', "root", "root");
}

catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
## ETAPE 1

## POUVOIR SELECTIONER UN PERSONNE DANS LE PREMIER SELECTEUR


## ETAPE 2

## POUVOIR SELECTIONER UN PERSONNE DANS LE DEUXIEME SELECTEUR

## ETAPE 3

## LORSQUE LON APPPUIE SUR LE BOUTON FIGHT, RETIRER LES PV DE CHAQUE PERSONNAGE PAR RAPPORT A LATK DU PERSONNAGE QUIL COMBAT

## ETAPE 4

## UNE FOIS LE COMBAT LANCER (QUAND ON APPPUIE SUR LE BTN FIGHT) AFFICHER en dessous du formulaire
# pour le premier perso PERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)
# pour le second persoPERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)

## ETAPE 5

## N'AFFICHER DANS LES SELECTEUR QUE LES PERSONNAGES QUI ONT PLUS DE 10 PV

$query=$pdo->prepare("SELECT * FROM personnage WHERE pv>=10 ORDER BY id_personnage DESC") ;
$query->execute() ;
$array = $query->fetchAll(PDO::FETCH_OBJ) ;


if(!empty($_POST))
{
    $perso1 = $_POST['perso1'] ;
    $perso2 =$_POST['perso2'] ;


    //Récuperer Les personnage choisi
    $dbPerso1 = getPerso($perso1, $pdo) ;
    $dbPerso2 = getPerso($perso2, $pdo) ;


    //Soustraire les atk de perso2 au pv de perso 1 et ineversement soustraire les atk de perso1 au pv de perso 2
    $pvPerso1 = $dbPerso1->pv - $dbPerso2->atk ;
    $pvPerso2 = $dbPerso2->pv - $dbPerso1->atk ;


    $newPerso1 =updatePvPerso($perso1, $pvPerso1, $pdo) ;
    $newPerso2 =updatePvPerso($perso2, $pvPerso2, $pdo) ;




    echo "<i style='color:red;'>". $newPerso1->name. " a perdu ".$newPerso2->atk." PV ;</i><b> il lui reste ".$newPerso1->pv." PV </b></br>" ;
    echo "<i style='color:red;'>". $newPerso2->name. " a perdu ".$newPerso1->atk." PV ;</i><b> il lui reste ". $newPerso2->pv." PV </b></br>" ;


}
function updatePvPerso($id, $pv, $pdo)
{
    $query = $pdo->prepare("UPDATE personnage SET pv = :newPv WHERE id_personnage = :id_personnage");
    $query->execute(["newPv"=>$pv, "id_personnage"=>$id]) ;
    $state = $query->fetch(PDO::FETCH_OBJ) ;

    return getPerso($id, $pdo) ;
}

function getPerso($id, $pdo)
{
    $query =$pdo->prepare("SELECT * FROM personnage WHERE id_personnage = :id_personnage") ;
    $query->execute(['id_personnage'=>$id]) ;

    return $query->fetch(PDO::FETCH_OBJ) ;
}



?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rendu Php</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<nav class="nav mb-3">
    <a href="./rendu.php" class="nav-link">Acceuil</a>
    <a href="./personnage.php" class="nav-link">Mes Personnages</a>
    <a href="./combat.php" class="nav-link">Combats</a>
</nav>
<h1>Combats</h1>
<div class="w-100 mt-5">

    <form method="POST" action="">
        <div class="form-group">
            <select name="perso1" id="">
                <option value="" selected disabled>Choissisez un personnage</option>
                <?php
                foreach ($array as $type)
                {?>
                    <option value="<?=$type->id_personnage?>"><?=$type->name ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <select name="perso2" id="">
                <option value="" selected disabled>Choissisez un personnage</option>
                <?php
                foreach ($array as $type)
                {?>
                    <option value="<?=$type->id_personnage?>"><?=$type->name ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <button style=" background-color: black ; color:white ;" class="btn">Fight</button>
    </form>

</div>

</body>
</html>
