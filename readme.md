# Test envoi de mail

une page index.php qui contient tout mon code!
    (Biensur il peut y avoir plus de fichier pour protéger mes mots de passe ou inclure une librairie)

-   quand je charge la page 

si dans  $_SESSION['mail'] il y a qqch  
alors 
	envoyer un mail à contact[arobase]apprendre.co  
	supprimer $_SESSION['mail]  
sinon  
	déclarer $_SESSION['mail]  
	afficher : Merci de rafraichir la page pour avoir votre surprise !!!

Tant que vous n'avez pas fini la première consigne ne pas faire la suite.  

générer un token de 12 caractères pour l'envoyer par mail.  
créer un fichier avec pour nom le token  

ne pas versionner les fichiers token

faire un fichier token.txt mettre une ligne par nom de token

envoyer lien par mail avec le paramètre token

si le lien est cliqué et que le fichier token existe supprimer le fichier token et afficher félicitation

si le ficher token n'existe pas envoyer un mail avec tentative de piratage, puis afficher PIRATE

bien sûr tout ça sur git 
