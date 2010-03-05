<?php /* $Id: convert.php,v 1.0 2007/06/19 20:40:17 Nethesis srl $ */

require_once('DB.php'); //PEAR must be installed

$db_user = "faxuser";
$db_pass = "faxpass";
$db_host = "localhost";
$db_name = "faxdb";
$db_engine = "mysql";

$datasource = $db_engine.'://'.$db_user.':'.$db_pass.'@'.$db_host.'/'.$db_name;
$db = DB::connect($datasource); // attempt connection
$sql = "SELECT * FROM RICEVUTI "; 

$results = $db->getAll($sql); 
if(DB::IsError($result)) { 
    die($result->getMessage().$sql); } 

foreach ($results as $row) {
    $insql = "INSERT INTO faxweb_fax 
                (fax_type,number,device,filename,path,rpath,type,sendto,com_id,date,pages,duration,quality,rate,data,errcorr,page,forward_rcp,letto) VALUES 
                ('R','$row[1]','$row[2]','$row[3].tif','/home/e-smith/faxweb/docs/received','/received','image/tiff','$row[4]','$row[6]','$row[7]','$row[8]','$row[9]','$row[10]','$row[11]','$row[12]','$row[13]','$row[14]','$row[16]','$row[17]')"; 
    $inresult = $db->query($insql);
    if(DB::IsError($inresult)) { 
       die($inresult->getMessage().$insql); 
    }
} 

$sql = "SELECT * FROM STATUS "; 

$results = $db->getAll($sql); 
if(DB::IsError($result)) { 
    die($result->getMessage().$sql); } 

foreach ($results as $row) {
    $insql = "INSERT INTO faxweb_fax 
                (fax_type,number,name,filename,path,rpath,type,date,tts,ktime,rtime,job_id,com_id,state,user,pages,attempts,esito,tipo,duration) VALUES 
                ('I','$row[1]','".trim($row[2])."','$row[3].tif','/home/e-smith/faxweb/docs/sent','/sent','image/tiff','".date('Y-m-d H:i:s',$row[5])."','$row[6]','$row[7]','$row[8]','$row[9]','$row[10]','$row[11]','$row[12]','$row[13]','$row[14]','$row[15]','$row[16]','$row[17]')"; 
    $inresult = $db->query($insql);
    if(DB::IsError($inresult)) { 
       die($inresult->getMessage().$insql); 
    }
} 

?>


