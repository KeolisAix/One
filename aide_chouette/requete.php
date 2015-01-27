<?php
$base = pg_connect("host=192.168.207.125 dbname=chouette2 user=postgres password=postgres");
// ESPACE VARIABLE
$R = $_GET['ajax'];
$DateBefore = $_GET['date'];
list($year, $month, $day) = split('[-]', $DateBefore);
$Date = $day."-".$month."-".$year;
$jour = array("256","4","8","16","32","64","128"); 
$datefr = $jour[date("w", strtotime($DateBefore))];
$ligne = $_GET['ligne'];
// END
if($R == 1){
	$sql="SELECT time_table_id FROM \"aix_\".time_table_dates WHERE date= '".$Date."'";
	$req=pg_query($sql);
    $nb=pg_num_rows($req);	
	echo $nb;
	die();
}
if($R == 2){
	$resultats = array();
	//echo $datefr;
	$sql="SELECT DISTINCT t1.time_table_id,t2.\"comment\" FROM aix_.routes r, aix_.time_tables_vehicle_journeys t1, aix_.vehicle_journeys v1, aix_.lines l, aix_.time_tables t2 WHERE ((((t1.vehicle_journey_id = v1.id) AND (v1.route_id = r.id)) AND (r.line_id = l.id)) AND (t2.id = t1.time_table_id)) AND l.\"name\"='".$ligne."' AND (t2.int_day_types & ".$datefr.")=".$datefr." ORDER BY time_table_id ASC";
	$req=pg_query($sql);
	
	echo "
	<p><center><table id='tableau'>
  		<tr>
    		<th style='border:solid 1px;text-align:center;padding:5px'>ID</th>
    		<th style='border:solid 1px;text-align:center;padding: 5px'>Nom</th>
  		</tr>";
	while ($valeur=pg_fetch_array($req)){ 
                	$resultats[] = $valeur;
	}
	for($i = 0; $i < pg_num_rows($req); $i++)
    {
        if( isset($resultats[$i]) )
        {
            echo "<tr><td style='border:solid 1px;text-align:center'>".$resultats[$i]['time_table_id']."</td><td style='border:solid 1px;text-align:center'>".$resultats[$i]['comment'].'</td></tr>';
        }
    }
	echo "</table></center></p>";
	die();
}
if($R == 4){
    $nbLigne = $_GET["nbligne"];
    $reqq = "SELECT DISTINCT t1.time_table_id FROM aix_.routes r, aix_.time_tables_vehicle_journeys t1, aix_.vehicle_journeys v1, aix_.lines l, aix_.time_tables t2 WHERE ((((t1.vehicle_journey_id = v1.id) AND (v1.route_id = r.id)) AND (r.line_id = l.id)) AND (t2.id = t1.time_table_id)) AND l.\"name\"='".$ligne."' AND (t2.int_day_types & ".$datefr.")=".$datefr;
    $sql=pg_query($reqq);
    while ($valeur=pg_fetch_array($sql)){ 
                	$resultats[] = $valeur;
	}
    $requeteACreer = "select T.time_table_id from aix_.time_table_periods T where  ('".$DateBefore."'>T.period_start AND '".$DateBefore."'< T.period_end) AND (T.time_table_id=".$resultats[0]['time_table_id']."";
	for($i = 1; $i < $nbLigne; $i++)
    {
        if( isset($resultats[$i]) )
        {
            $requeteACreer = $requeteACreer." OR T.time_table_id=".$resultats[$i]['time_table_id']."";
        }
    }
    $requeteACreer = $requeteACreer.")";
    //echo $requeteACreer;
    //echo "<br>";
    $sql2=pg_query($requeteACreer);
    while ($valeur=pg_fetch_array($sql2)){ 
                	$resultats2[] = $valeur;

	}
    $nbcal = "";
        echo "<p><center><table id='tableauParcours'>
  	        <tr>
    	        <th style='border:solid 1px;text-align:center;padding:5px'>Parcours</th>
    	        <th style='border:solid 1px;text-align:center;padding: 5px'>Nom</th>
  	        </tr>";
    for($i = 0; $i < pg_num_rows($sql2); $i++)
    {   
        $nbcal = $nbcal.$resultats2[$i]['time_table_id'].".";
        //echo $resultats2[$i]['time_table_id'].".";                 
        $reqP = "SELECT DISTINCT R.\"name\", R.\"id\" FROM aix_.routes r, aix_.time_tables_vehicle_journeys t1, aix_.vehicle_journeys v1, aix_.lines l, aix_.time_tables t2 WHERE ((((t1.vehicle_journey_id = v1.id) AND (v1.route_id = r.id)) AND (r.line_id = l.id)) AND (t2.id = t1.time_table_id)) AND L.name='".$ligne."' AND t2.id=".$resultats2[$i]['time_table_id']." ORDER BY R.\"id\" ASC";
        $sqlP=pg_query($reqP);
        //echo $reqP;
        
     for($i = 0; $i < pg_num_rows($sqlP); $i++) {                    
            $row = pg_fetch_assoc($sqlP);
            $plus = $i +1;
                echo "<tr><td style='border:solid 1px;text-align:center'>".$row['id']."</td><td style='border:solid 1px;text-align:center'>".$row['name']."</td></tr>";
       
        }
    }
    echo "</table></center>";
    echo "<p>Les Calendriers utilisés pour ce tableau sont : ".$nbcal;
    die();
}
?>