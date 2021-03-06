<html>
    <head>
        <style>
                /** notifications **/
        .notify {
          display: block;
          background: #fff;
          padding: 12px 18px;
          max-width: 400px;
          margin: 0 auto;
          cursor: pointer;
          -webkit-border-radius: 3px;
          -moz-border-radius: 3px;
          border-radius: 3px;
          margin-bottom: 20px;
          box-shadow: rgba(0, 0, 0, 1) 0px 1px 2px 0px;
        }

        .notify h1 { margin-bottom: 6px; }

        .successbox h1 { color: #f00; }
        .errorbox h1 { color: #6f423b; }

        .successbox h1:before, .successbox h1:after { background: #cad8a9; }
        .errorbox h1:before, .errorbox h1:after { background: #d6b8b7; }

        .notify .alerticon { 
          display: block;
          text-align: center;
          margin-bottom: 10px;
        }
        </style>
        <script>
            window.history.pushState('Job', 'Requete', '/H2C/');
        </script>
    </head>
</html>

<?php
###                  ###
#    Prérequis         #
###                  ###
header('Content-Type: text/html; charset=UTF-8');
include('config.php'); //INCLUDE DU FICHIER DE CONFIGURATION
ini_set('display_errors',1); //Pour afficher les érreurs PHP set sur 1 sinon laissé 0
ini_set('display_startup_errors',1); // Permet un déboggage 1 = Afficher / 2 = Masqué
error_reporting(-1); // Masque l'intégralité des érreurs
if (!isset($JobLancer)) { // test d'une variable non attribué la premiere fois
    ###                  ###
    #    Definition JOB    #
    ###                  ###
    $Job = $_GET["Job"]; // Variable qui défini le type de Job
    $Demandeur = $_GET["mail"]; // Variable qui défini le demandeur, Cette variable est lié a l'Authentification de la page index.php
    $DateNow = date("d/m/Y"); // Défini la Date actuelle
    $HeureNow = date("H:i:s"); // Défini l'Heure actuelle
    $Mail = $_GET["mail"]; // Clone de la Variable $demandeur, elle permet de défini le Mail qui recevra les résultats des Jobs.
    $base = "preprod"; // Base de Données sur laquelle les jobs ce base. ( test / preprod / production )

    ###                  ###
    #      IMPORTATION     #
    ###                  ###
    if($Job == "Import"){ // Si je job est un import alors
        $DateDebut = $_GET["ImportDateDebut"]; // On défini une variable Date de Début sous forme 01/02/2015, qui est renseigné via la page index.php
        $DateFin = $_GET["ImportDateFin"]; // On défini une variable Date de Fin, qui est renseigné via la page index.php
        $dateFormatDebut = str_replace('/', '-', $DateDebut); // Remplace les / de la date par des -, donc nouveau format 01-02-2015
        $dateAnnonceDebut = date('Y-m-d', strtotime($dateFormatDebut)); // Change la syntaxe de la date en 2015-02-01
        $dateFormatFin = str_replace('/', '-', $DateFin); // Remplace les / de la date par des -, donc nouveau format 01-02-2015
        $dateAnnonceFin = date('Y-m-d', strtotime($dateFormatFin)); // Change la syntaxe de la date en 2015-02-01
        $dureeSejour = (strtotime($dateAnnonceFin) - strtotime($dateAnnonceDebut)) /86400; // Permet de connaitre le nombre de jour entre 2 dates.
        if($_GET["PurgeOui"]){ $Purge = "1"; } else { $Purge = "0";} // Défini la variable de Purge.
        AddLogs($ChouetteLogsPath, $Job.";".$base.";".$DateNow.";".$HeureNow.";".$DateDebut.";".$DateFin.";".$Purge.";".$Demandeur); // Ajout du job dans les Logs
        $DateSplit = explode('/', $DateDebut); // Permet de split la date par lots
        $testt = 'cmd.exe /c '.$JobImportPath.' --context_param purge='.$Purge.' --context_param datedebut='.$DateSplit[2].$DateSplit[1].$DateSplit[0].' --context_param base='.$base.' --context_param jours='.$dureeSejour.' --context_param mail='.$Mail;    
        exec('cmd.exe /c '.$JobImportPath.' --context_param purge='.$Purge.' --context_param datedebut='.$DateSplit[2].$DateSplit[1].$DateSplit[0].' --context_param base='.$base.' --context_param jours='.$dureeSejour.' --context_param mail='.$Mail);
        //exec('cmd.exe /c calc.exe');
        JobFinish();
      // ^= Lancement de la commande H2C.  
    }

    ###                  ###
    #      BILLETTIQUE     #
    ###                  ###
    if($Job == "Bill"){ // Variable qui défini le type de Job
        $DateDebut = $_GET["BillDateDebut"]; // On défini une variable Date de Début sous forme 01/02/2015, qui est renseigné via la page index.php
        $DateFin = $_GET["BillDateFin"]; // On défini une variable Date de Fin, qui est renseigné via la page index.php
        $BillName = $_GET["BillName"]; // On défini un nom pour le fichier .ZIP de sortie via index.php
        $dateFormatDebut = str_replace('/', '-', $DateDebut); // Remplace les / de la date par des -, donc nouveau format 01-02-2015
        $dateAnnonceDebut = date('Y-m-d', strtotime($dateFormatDebut)); // Change la syntaxe de la date en 2015-02-01
        $dateFormatFin = str_replace('/', '-', $DateFin); // Remplace les / de la date par des -, donc nouveau format 01-02-2015
        $dateAnnonceFin = date('Y-m-d', strtotime($dateFormatFin)); // Change la syntaxe de la date en 2015-02-01
        $dureeSejour = (strtotime($dateAnnonceFin) - strtotime($dateAnnonceDebut)) /86400; // Permet de connaitre le nombre de jour entre 2 dates.
        AddLogs($ChouetteLogsPath, $Job.";;".$DateNow.";".$HeureNow.";".$DateDebut.";".$DateFin.";;".$Demandeur);  // Ajout du job dans les Logs
        $DateSplit = explode('/', $DateDebut); // Permet de split la date par lots
        $testt = 'cmd.exe /c '.$JobBillPath.' --context_param datedebut='.$DateSplit[2].$DateSplit[1].$DateSplit[0].' --context_param jours='.$dureeSejour.' --context_param fichier='.$BillName.' --context_param mail='.$Mail;    
        echo $testt; //Juste un affichage pour le déboggage.
        exec('cmd.exe /c '.$JobBillPath.' --context_param datedebut='.$DateSplit[2].$DateSplit[1].$DateSplit[0].' --context_param jours='.$dureeSejour.'  --context_param fichier='.$BillName.' --context_param mail='.$Mail);
        JobFinish();
      // ^= Lancement de la commande H2C.  
    }

    ###                  ###
    #      EXPORTATION     #
    ###                  ###

    if($Job == "Export"){
        $ExportFormat = $_GET["ExportFormat"]; // On défini le Format de sortie (NEPTUNE / GTFS)
        $ExportName = $_GET["ExportName"]; // On défini un nom pour le fichier .ZIP de sortie via index.php
        $ExportBase = $_GET["ExportBase"]; // On défini la base a exporter
        $cmd = 'cmd.exe /c '.$JobExportPath.' --context_param format='.$ExportFormat.' --context_param namezip='.$ExportName.' --context_param base='.$ExportBase.' --context_param mail='.$Mail;
        AddLogs($ChouetteLogsPath, $Job.";;".$DateNow.";".$HeureNow.";".$ExportFormat.";".$ExportName.";".$ExportBase.";".$Demandeur); // Ajout du job dans les Logs
        $ExportLogs = fopen('./Logs/'.$ExportName.'.zip.txt', 'a+'); // Ajout du job dans les Logs 
        fputs($ExportLogs, "###########################"."\r\n"); // Ajout du job dans les Logs
        fputs($ExportLogs, "####### EXPORT H2C ########"."\r\n"); // Ajout du job dans les Logs
        fputs($ExportLogs, "###########################"."\r\n"); // Ajout du job dans les Logs
        fputs($ExportLogs, ""."\r\n"); // Ajout du job dans les Logs
        fputs($ExportLogs, "Commande : ".$cmd."\r\n"); // Ajout du job dans les Logs 
        fputs($ExportLogs, "Date : ".$DateNow."\r\n"); // Ajout du job dans les Logs
        fputs($ExportLogs, "Heure : ".$HeureNow."\r\n"); // Ajout du job dans les Logs
        fputs($ExportLogs, "Demandeur : ".$Mail."\r\n"); // Ajout du job dans les Logs
        fclose($ExportLogs);
        exec($cmd); // Exécution de la commande
        JobFinish();
    }

    ###                  ###
    #     MISE EN PROD     #
    ###                  ###

    if($Job == "MEP"){// Variable qui défini le type de Job
        AddLogs($ChouetteLogsPath, $Job.";;".$DateNow.";".$HeureNow.";;;;".$Demandeur); // Ajout du job dans les Logs
        $cmd = 'cmd.exe /c '.$JobMEPPath.' --context_param mail='.$Demandeur;
        exec($cmd); // Exécution de la commande
        JobFinish();
    }
}
else {
    ?>
<html>
    <center>
        <br>
        <br>        <br>
        <br>        <br>
        <br>        <br>
        <br>
        <div id="contentok">
      <div class="notify errorbox">
        <h1>Job en cours !</h1>
        <span class="alerticon"><img src="http://hydra-media.cursecdn.com/dragonnest.gamepedia.com/d/db/Icon_delete.png"></span>
        <p>Le job que vous avez demandé n'est pas exécuté.</p>
          <p>Merci d'attendre environ 25 minutes entre chaque tâche.</p>
          <p>En cas de Problème merci de prévenir <a href="mailto:patrice.maldi@keolis.com">Patrice MALDI</a></p>
      </div>
</div>
</html>
<?php
}
###                  ###
#    Definition Logs   #
###                  ###

function AddLogs($Path, $texte){ // Creation des logs
    $fp=fopen($Path, "a+"); // a+ = Ajout a la fin
    fwrite($fp, $texte."\r\n");  // Ecriture dans un fichier
    fclose($fp); // Fermeture du fichier
}

function JobFinish(){
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
