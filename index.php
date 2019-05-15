<?php 
require_once 'vendor/autoload.php';
require_once 'config.php';
date_default_timezone_set('Europe/Paris');

/**
* envoi d'un mail par swift_mailer 
* @return int nb de mails envoyés
*/
function sendMail($emailTo, $sujet, $msg, $cci = true)//:int
{
	require 'config.php';

	$mailTo = $emailTo;
	if (!is_array($emailTo)){
		$mailTo = [$emailTo];
	}
	// Crée le Transport
	$transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
	$transport->setUsername($gmailUser);
	$transport->setPassword($gmailpwd);
	

	// Crée le Mailer utilisant le Transport
	$mailer = new Swift_Mailer($transport);

	// Crée le message en HTML et texte
	$message = new Swift_Message($sujet);
	$message->setFrom([$gmailUser => $pseudo]);

	if ($cci){
		$message->setBcc($mailTo);
	}else{
		$message->setTo([$mailTo]);
	}
	
	if (is_array($msg) && array_key_exists('text', $msg) && array_key_exists('html', $msg)){
		$message->setBody($msg['html'] ,'text/html' );
		$message->addPart($msg['text'] ,'text/plain' );
	}else if ( is_array($msg) && array_key_exists('html', $msg)){
		$message->setBody($msg["html"], 'text/html');
		$message->addPart($msg["html"], 'text/plain');
	}elseif (is_array($msg) && array_key_exists("text", $msg)) {
		$message->setBody($msg["text"], 'text/plain');

	}elseif (is_array($msg)) {
		die('erreur une clé n\'est pas bonne');

	}else{
		$message->setBody($msg, 'text/plain');
	}

	// envoie le message
	return($mailer->send($message));
}
/**
* créer une chaine "token" 
* @return string
*/
function RandomString($lg = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = "";
    for ($i = 0; $i < $lg; $i++) {
        $randstring .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return ($randstring);
}

// DEBUT
	if (session_status() != PHP_SESSION_ACTIVE){
		session_start();
	}

	if(!empty($_GET) && isset($_GET["token"]) && !empty($_GET["token"]) ){
		$file = file_get_contents('.gitignore', true);
		if (strstr($file, $_GET["token"]))
		{
			// supprimer le fichier token
			if (file_exists($_GET["token"])){
				if (unlink($_GET["token"])){
					echo '<br />Bravo ! le fichier '. $_GET["token"] . " a été supprimé. ";

				}
			}else{
				$msg = "<br />Oup's ! petit pirate ! Ce token est invalide.";
				sendMail($dest, "PIRATE",  $msg);
				echo $msg;
			}
			unset($_SESSION["mail"]);
			//unset($_GET["token"]);
			//header('location:index.php');
		}

	}


	if(isset($_SESSION['mail']) && strtolower($_SESSION['mail']) == 'ok') {
		// envoyer un mail
		$token = RandomString(12);
		$handle = fopen($token, "w");
		$htmlMail = ["html" => '<h1>Bienvenue !</h1><a href="http://localhost/0021_Envoimail_S10/index.php?token='.urlencode($token).'">Il y a eu une visite sur la page. Cliquez ICI pour supprimer le token</a>'];
		if (sendMail($dest, "Surprise s10 token",  $htmlMail)){
			echo '<br /> Mail envoyé et fichier '. $token . " créé. ";
			unset($_SESSION["mail"]);
			fclose($handle);
			$handle = fopen(".gitignore", "a");
			if ($handle){
				fwrite($handle, "\r\n" . $token);
				fclose($handle);
				echo '<br /> Fichier '. $token . " ajouté dans .gitignore";
				echo '<br /> Cliquez sur le lien envoyé par mail pour supprimer le token.';
			}

		}
	}else{
		$_SESSION["mail"] = 'ok';
		echo "<h1>Merci de rafraichir la page pour avoir votre surprise !!!</h1>";
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<section class="sectionHome">
<!-- 			<h1>Raffraichir la page pour votre surprise ! </h1>
 -->		</section>
	</body>
</html>
