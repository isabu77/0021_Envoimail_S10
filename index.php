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

	if (session_status() != PHP_SESSION_ACTIVE){
		session_start();
	}

	if(isset($_SESSION['mail']) && strtolower($_SESSION['mail']) == 'ok') {
		// envoyer un mail
		$token = RandomString(12);
		if (sendMail($dest, "Exercice s10 token",  "Le token est " . $token)){
			$handle = fopen($token, "w");
			echo 'mail envoyé et fichier '. $token . " créé. ";
			unset($_SESSION["mail"]);
			fclose($handle);
			$handle = fopen(".gitignore", "a");
			if ($handle){
				//fseek($handle, -1, SEEK_END);
				fwrite($handle, "\r\n" . $token);
				fclose($handle);
				echo 'fichier '. $token . " ajouté dans .gitignore";
			}

		}
	}else{
		$_SESSION["mail"] = 'ok';
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<section class="sectionHome">
			<h1>Raffraichir la page pour votre surprise ! </h1>
		</section>
	</body>
</html>
