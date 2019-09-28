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

## RECUPERER TOUT LES PERSONNAGES CONTENU DANS LA TABLE personnages

$bdd=$pdo->prepare("SELECT * FROM personnage ORDER BY pv DESC ");
$bdd->execute() ;
$perso= $bdd->fetchAll(PDO::FETCH_OBJ) ;



## ETAPE 2

## LES AFFICHERS DANS LE HTML
## AFFICHER SON NOM, SON ATK, SES PV, SES STARS

## ETAPE 3

## DANS CHAQUE PERSONNAGE JE VEUX POUVOIR APPUYER SUR UN BUTTON OU IL EST ECRIT "STARS"




## LORSQUE L'ON APPUIE SUR LE BOUTTON "STARS"

## ON SOUMET UN FORMULAIRE QUI METS A JOURS LE PERSONNAGE CORRESPONDANT (CELUI SUR LEQUEL ON A CLIQUER) EN INCREMENTANT LA COLUMN STARS DU PERSONNAGE DANS LA BASE DE DONNEE

#######################
## ETAPE 4
# AFFICHER LE MSG "PERSONNAGE ($name) A GAGNER UNE ETOILES"

if(!empty($_POST) AND isset($_POST))
{
    $etoile = $_POST['stars'] ;


    $dbEtoile = getStars($etoile, $pdo) ;

    $plusEtoile= $dbEtoile->stars + 1 ;

    $newEtoile = updateStars($etoile, $plusEtoile, $pdo) ;

    echo $newEtoile->name. " a gagné une étoile </br>" ;

}

function updateStars($id, $stars, $pdo)
{
    $query = $pdo->prepare("UPDATE personnage SET stars = :newStars WHERE id_personnage= :id_personnage");
    $query->execute(["newStars"=> $stars, "id_personnage"=>$id]);
    $state = $query->fetch(PDO::FETCH_OBJ);

    return getStars($id, $pdo) ;

}

function getStars($id, $pdo)
{
    $query = $pdo->prepare("SELECT * FROM personnage WHERE id_personnage = :id_personnage") ;
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
<h1>Mes personnages</h1>
<div class="w-100 mt-5">



    <form method="POST">

        <table style=" border-collapse: collapse;">
            <tr style="border: 1.5px solid black;font-size: 22px;">
                <td >Nom</td>
                <td>Atk</td>
                <td>Pv</td>
                <td>Stars</td>

            </tr>
            <?php
            foreach ($perso as $type)
            {?>
                <tr style="border: 1px solid black;">
                    <td><?=$type->name ?></td>
                    <td><?=$type->atk ?></td>
                    <td><?=$type->pv?></td>

                    <td><button style="background-color: black; color:white ;" value="<?=$type->id_personnage?>" name="stars">stars<?=$type->stars?></button></td>

                </tr>
                <?php
            }
            ?>

        </table>
    </form>

</div>

</body>
</html>
