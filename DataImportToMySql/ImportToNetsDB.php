<?php

include 'simple_html_dom.php';
//1.import team data
function ImportNetsTeamDataAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('div#info') as $a){
    $teamdata = array();

   $teamdata['LOCATION']= substr(strip_tags($a->find('p')[2]->innertext),15);
   $teamdata['TEAM NAMES']= substr(strip_tags($a->find('p')[3]->innertext),16);
   $teamdata['PLAYOFF APPERANCES']= substr(strip_tags($a->find('p')[6]->innertext),26);
   $teamdata['CHAMPIONSHIPS']=substr(strip_tags($a->find('p')[7]->innertext),20);
//Site where data is scrapped:$html = file_get_html();
   $teamdata['2017-18 RECORD']=substr(strip_tags($htmlTeamDataRecord->find("div ul.team_recorddetail li")[0]->innertext),8);
        

           $Teamdata[]= $teamdata;

}
//
// print_r($Teamdata);
echo json_encode($Teamdata);

$db = new PDO('mysql:host=localhost;dbname=teams' ,'root','R98sc30lb337%');
$stmt = $db->prepare("insert into nets_team_data values(?,?,?,?,?)");

foreach($Teamdata as $TeamdataRow){
   $stmt->bindParam(1, $TeamdataRow['LOCATION']); 
   $stmt->bindParam(2, $TeamdataRow['TEAM NAMES']); 
   $stmt->bindParam(3, $TeamdataRow['PLAYOFF APPERANCES']); 
   $stmt->bindParam(4, $TeamdataRow['CHAMPIONSHIPS']); 
   $stmt->bindParam(5, $TeamdataRow['2017-18 RECORD']); 
 
   $stmt->execute();

}
    
} catch (Exception $e) {
    echo $e->getMessage();
}
}
//2.import new team roster
function ImportNetsNewTeamRosterAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#roster') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();
  
//            echo strip_tags($li->find('td')[]->innertext);
         $players['PLAYER']= strip_tags($li->find('td a')[0]->innertext);
         $players['POSITION']= strip_tags($li->find('td')[1]->innertext);
         $players['HEIGHT']= strip_tags($li->find('td')[2]->innertext);
         $players['WEIGHT']= strip_tags($li->find('td')[3]->innertext);
         $players['Birth Date']= strip_tags($li->find('td')[4]->innertext);
         $players['EXPERIENCE']= strip_tags($li->find('td')[6]->innertext);
         $players['COLLEGE']= strip_tags($li->find('td')[7]->innertext);
        

  $arrayroster[]= $players;
  


}
//

 $ArrayNewroster=array_slice($arrayroster,1);
//  print_r($ArrayNewroster);
//echo json_encode($ArrayNewroster);

$db = new PDO('mysql:host=localhost;dbname=teams' ,'root','R98sc30lb337%');
$stmt = $db->prepare("insert into nets_newteam_roster values(?,?,?,?,?,?,?)");

foreach($ArrayNewroster as $ArrayNewRosterRow){
    $stmt->bindParam(1, $ArrayNewRosterRow['PLAYER']); 
   $stmt->bindParam(2, $ArrayNewRosterRow['POSITION']); 
   $stmt->bindParam(3, $ArrayNewRosterRow['HEIGHT']); 
   $stmt->bindParam(4, $ArrayNewRosterRow['WEIGHT']); 
    $stmt->bindParam(5, $ArrayNewRosterRow['Birth Date']); 
   $stmt->bindParam(6, $ArrayNewRosterRow['EXPERIENCE']); 
   $stmt->bindParam(7, $ArrayNewRosterRow['COLLEGE']); 
   
 
   $stmt->execute();

}
}
} catch (Exception $e) {
    echo $e->getMessage();


}
}

//3.import Team PayRoll
function ImportNetsTeamPayRollAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#contracts') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();
  
  $players['Player']= strip_tags($li->find('th')[0]->innertext);
  $players['Age']= strip_tags($li->find('td')[0]->innertext);
  if(strip_tags($li->find('td')[1]->innertext)=='&nbsp;'){
       $players['2018-19']='';
  }
  else{
      $players['2018-19']= strip_tags($li->find('td')[1]->innertext);
  }
  
  $players['2019-20']= strip_tags($li->find('td')[2]->innertext);
  $players['2020-21']= strip_tags($li->find('td')[3]->innertext);
  $players['2021-22']= strip_tags($li->find('td')[4]->innertext);
  $players['2022-23']= strip_tags($li->find('td')[5]->innertext);
  $players['2023-24']= strip_tags($li->find('td')[6]->innertext);
  $players['Signed Using']= strip_tags($li->find('td')[7]->innertext);
   if(strip_tags($li->find('td')[8]->innertext)=='&nbsp;'){
       $players['Guaranteed']='';
  }
  else{
       $players['Guaranteed']= strip_tags($li->find('td')[8]->innertext);
  }

  $ArrayPayRoll[]= $players;

}
//

 $ArrayNewPayRoll=array_slice($ArrayPayRoll,2);
  print_r($ArrayNewPayRoll);
//echo json_encode($ArrayNewPayRoll);

$db = new PDO('mysql:host=localhost;dbname=teams' ,'root','R98sc30lb337%');
$stmt = $db->prepare("insert into NETS_team_payroll values(?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewPayRoll as $ArrayNewPayRollRow){

//The 2018-19 for order of payroll(don't move age or player).
   $stmt->bindParam(1, $ArrayNewPayRollRow['2018-19']); 
   $stmt->bindParam(2, $ArrayNewPayRollRow['Player']); 
   $stmt->bindParam(3, $ArrayNewPayRollRow['Age']); 
   $stmt->bindParam(4, $ArrayNewPayRollRow['2019-20']); 
   $stmt->bindParam(5, $ArrayNewPayRollRow['2020-21']); 
   $stmt->bindParam(6, $ArrayNewPayRollRow['2021-22']); 
   $stmt->bindParam(7, $ArrayNewPayRollRow['2022-23']); 
   $stmt->bindParam(8, $ArrayNewPayRollRow['2023-24']); 
   $stmt->bindParam(9, $ArrayNewPayRollRow['Signed Using']); 
   $stmt->bindParam(10, $ArrayNewPayRollRow['Guaranteed']); 
 
   $stmt->execute();

}
//This deletes the empty row just before the total team and another contract player.
   $stmt = $db->prepare("DELETE FROM `nets_team_payroll` WHERE `Player`='';");
   $stmt->execute();
} 
} catch (Exception $e) {
    echo $e->getMessage();
}
}
//4.Import PLAYERS to a ALL NBA Players table.

function ImportAllenCrabbeSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Allen_Crabbe values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportCarisLeVertSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Caris_LeVert values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportDAngeloRussellSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into DAngelo_Russell values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportDanteCunninghamSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Dante_Cunningham values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}

function ImportDeMarreCarrollSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into DeMarre_Carroll values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}

function ImportJacobWileySeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Jacob_Wiley values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportJarrettAllenSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Jarrett_Allen values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}


function ImportJeremyLinSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Jeremy_Lin values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}

function ImportJoeHarrisSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Joe_Harris values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}

function ImportQuincyAcySeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Quincy_Acy values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}

function ImportRondaeHollisJeffersonSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Rondae_Hollis_Jefferson values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportSpencerDinwiddieSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Spencer_Dinwiddie values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportTimofeyMozgovSeasonStatsAndInsertIntoMysql(){

try {
//Site where data is scrapped:$html = file_get_html();
foreach($html->find('table#table3') as $ul){
    
    foreach($ul->find('tr') as $li){
  $players = array();


            $players['Season'] = strip_tags($li->find('td')[0]->innertext);
            $players['Team'] =strip_tags($li->find('td')[1]->innertext);
            $players['G'] = strip_tags($li->find('td')[27]->innertext);
            $players['GS'] = strip_tags($li->find('td')[28]->innertext);
            $players['MP'] = strip_tags($li->find('td')[5]->innertext);
            $players['FG%'] = strip_tags($li->find('td')[11]->innertext);
            $players['3P%'] = strip_tags($li->find('td')[9]->innertext);
            $players['FT%'] = strip_tags($li->find('td')[10]->innertext);
            $players['RB'] = strip_tags($li->find('td')[3]->innertext);
            $players['AST'] = strip_tags($li->find('td')[4]->innertext);
            $players['STL'] = strip_tags($li->find('td')[7]->innertext);
            $players['BLK'] = strip_tags($li->find('td')[8]->innertext);
            $players['TOV'] = strip_tags($li->find('td')[6]->innertext);
            $players['PTS'] = strip_tags($li->find('td')[2]->innertext);
            $players['DOUBLE-DOUBLES'] = strip_tags($li->find('td')[31]->innertext);
            $players['TRIPLE-DOUBLES'] = strip_tags($li->find('td')[32]->innertext);
            
//            16 TOT
                         
           $arraybeforeslice[]= $players;
    }
}

$ArrayNewRoster=array_slice($arraybeforeslice,1);
// print_r($ArrayNewRoster);

//echo json_encode($data);

$db = new PDO('mysql:host=localhost;dbname=players_season_pergame','root','R98sc30lb337%');
$stmt = $db->prepare("insert into Timofey_Mozgov values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach($ArrayNewRoster as $row){
   $stmt->bindParam(1, $row['Season']); 
   $stmt->bindParam(2, $row['Team']); 
   $stmt->bindParam(3, $row['G']); 
   $stmt->bindParam(4, $row['GS']); 
   $stmt->bindParam(5, $row['MP']); 
   $stmt->bindParam(6, $row['FG%']); 
   $stmt->bindParam(7, $row['3P%']); 
   $stmt->bindParam(8, $row['FT%']); 
   $stmt->bindParam(9, $row['RB']); 
   $stmt->bindParam(10, $row['AST']); 
   $stmt->bindParam(11, $row['STL']); 
   $stmt->bindParam(12, $row['BLK']); 
   $stmt->bindParam(13, $row['TOV']); 
   $stmt->bindParam(14, $row['PTS']); 
   $stmt->bindParam(15, $row['DOUBLE-DOUBLES']); 
   $stmt->bindParam(16, $row['TRIPLE-DOUBLES']); 
   $stmt->execute();
    

}
} catch (Exception $e) {
    echo $e->getMessage();
}
}
function ImportNetsPlayersSeasonStatsAndInsertIntoMysqlDB(){
	

	try {

ImportAllenCrabbeSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportCarisLeVertSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportDAngeloRussellSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportDanteCunninghamSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportDeMarreCarrollSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportJacobWileySeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportJarrettAllenSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportJeremyLinSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportJoeHarrisSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportQuincyAcySeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportRondaeHollisJeffersonSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportSpencerDinwiddieSeasonStatsAndInsertIntoMysql();
 usleep(mt_rand(15000000,25000000));
ImportTimofeyMozgovSeasonStatsAndInsertIntoMysql();

 
}


catch(Exception $e) {
  echo $e->getMessage();
}
	
}

//17.ImportNetsToDB

//1.Team Data
//
ImportNetsTeamDataAndInsertIntoMysql();

//
//2.Team New Roster
//

ImportNetsNewTeamRosterAndInsertIntoMysql();
//
//3.Team PayRoll
//

ImportNetsTeamPayRollAndInsertIntoMysql();

//4.IMPORT PLAYERS TO  players_season_pergame


ImportNetsPlayersSeasonStatsAndInsertIntoMysqlDB();



?>





