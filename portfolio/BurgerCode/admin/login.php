<?php
    require 'database.php';    
    
    // on teste si le visiteur a soumis le formulaire de connexion   
    if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
	   if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {       
            $db = Database::connect();
           
           	// on teste si une entrée de la base contient ce couple login / pass
            $login = $db ->quote($_POST['login']);            
            $pass = $db -> quote(md5($_POST['pass'])); 
                      
            $db = Database::connect();
            $statement = $db->prepare("SELECT count(*) AS nb FROM membre WHERE login = $login AND pass_md5 = $pass");  
            $data = $statement->execute();
            $data = $statement->fetchAll();
                      
            Database::disconnect();                 
           
           	// si on obtient une réponse, alors l'utilisateur est un membre
            if ($data[0]['nb'] == 1) {  
                $erreur= ''; 
                session_start();
                $_SESSION['login'] = $_POST['login'];
                header('Location: index.php');
                exit();
            }elseif ($data[0]['nb'] == 0) {
                // si on ne trouve aucune réponse, le visiteur s'est trompé soit dans son login, soit dans son mot de passe
                $erreur = 'Compte non reconnu.';
            }else {
                // sinon, alors la, il y a un gros problème :)
                $erreur = 'Probème dans la base de données : plusieurs membres ont les mêmes identifiants de connexion.';
            }
	   }else {
	       $erreur = 'Au moins un des champs est vide.';
	   }
       
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Burger Code</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    
    <body>
        <h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger Code <span class="glyphicon glyphicon-cutlery"></span></h1>
         <div class="container admin">
            <div class="row">
      		    <form action="login.php" name="login" class="login" method="post">
					<table class="login-box">
				        <tbody>
                            <tr class="boxx">
							     <td class="conect-text">Connexion en mode ADMIN</td>
                            </tr>
                            <tr>
							     <td><input class="input-box" name="login" placeholder="Login" type="text" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"></td>
                            </tr>
                            <tr>
							     <td><input class="input-box" name="pass" placeholder="Mot de Passe" type="PASSWORD" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"></td>
                            </tr>
                            <tr>
							     <td><input class="input-button" name="connexion" value="Connexion" type="submit"></td>
                            </tr>
					   </tbody>
                    </table>
				</form>
                <p class="error"><?php if (isset($erreur)) echo '<br /><br />',$erreur;  ?></p>                
            </div>
        </div>   
    
    </body>
</html>
