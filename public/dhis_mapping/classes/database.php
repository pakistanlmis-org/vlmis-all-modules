<?php

class Database
{
	
private $connection;

public function connect_db(){
    
        $this->connection = mysqli_connect('10.10.10.4', 'vlmisr2user', '{5bhXduIBQ*&', 'vlmisr2');
        if(mysqli_connect_error()){
            die("Database Connection Failed" . mysqli_connect_error() . mysqli_connect_errno());
        }
}
        
          
function __construct()
{
	$this->connect_db();
}

public function sanitize($var){
    
	$return = mysqli_real_escape_string($this->connection, $var);
	return $return;
}

public function read(){
   
	
      $sql =    "SELECT
                locations.pk_id,
                locations.location_name
                FROM
                locations
                WHERE
                locations.province_id = 1
                AND locations.geo_level_id = 4

                ORDER BY

                locations.location_name";
        
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function read1(){
   
	
      $sql =    "SELECT DISTINCT
                dhis_punjab.distcode,
                dhis_punjab.district
                FROM
                dhis_punjab
                ORDER BY
                dhis_punjab.district";
        
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function read2(){
   
	
      $sql =    "SELECT * FROM `locations` WHERE parent_id = ".$_REQUEST['pk_id']."";
        
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function read3(){
   
	
      $sql =   "SELECT DISTINCT
                dhis_punjab.tcode,
                dhis_punjab.tehsil
                FROM
                dhis_punjab
                INNER JOIN vlmis_dhis ON dhis_punjab.distcode = vlmis_dhis.dhis_id
                WHERE
                vlmis_dhis.vlmis_id = ".$_REQUEST['pk_id']."";
              
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function read4(){
   
	
      $sql =    "SELECT * FROM `locations` WHERE parent_id = ".$_REQUEST['pk_id']."";
        
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function read5()
        {
   
	
       $sql =   "SELECT DISTINCT
                dhis_punjab.uncode,
                dhis_punjab.un_name
                FROM
                dhis_punjab
                INNER JOIN vlmis_dhis ON dhis_punjab.tcode = vlmis_dhis.dhis_id
                WHERE
                vlmis_dhis.vlmis_id = ".$_REQUEST['pk_id']."";
        
              
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function readepivlmis()
        {
   
	
  $sql =       "SELECT DISTINCT
                locations.location_name,
                warehouses.pk_id,
                warehouses.warehouse_name
                FROM
                warehouses
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                WHERE  
                warehouses.stakeholder_office_id = 6
                AND locations.geo_level_id = 6  
                AND  locations.parent_id = ".$_REQUEST['pk_id']."
                ORDER BY
                locations.pk_id,
                warehouses.warehouse_name";
        
              
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function readepidhis()
        {
    
       $sql =  "SELECT
                dhis_punjab.un_name,
                dhis_punjab.facode,
                dhis_punjab.fac_name
                FROM
                dhis_punjab
                INNER JOIN vlmis_dhis ON dhis_punjab.tcode = vlmis_dhis.dhis_id
                WHERE
                vlmis_dhis.vlmis_id = ".$_REQUEST['pk_id']."";
        
	$res = mysqli_query($this->connection, $sql);
       
	return $res;
        
}

public function Selectdistrict(){
    
   
	 $sql = "SELECT vlmis_dhis.vlmis_id, vlmis_dhis.dhis_id FROM vlmis_dhis WHERE vlmis_dhis.type = 'district'";
   
	 $res = mysqli_query($this->connection, $sql);
         
         $array='';
         
         while ($row = $res->fetch_array()) 
         {
             $array[$row['vlmis_id']] = $row['dhis_id'];
         }
       
	return $array;
}

public function Selecttehsil(){
    
   
        
         $sql = "SELECT vlmis_dhis.vlmis_id, vlmis_dhis.dhis_id FROM vlmis_dhis WHERE vlmis_dhis.type = 'tehsil'";
   
	 $res = mysqli_query($this->connection, $sql);
         
         $array='';
         
         while ($row = $res->fetch_array()) 
         {
             $array[$row['vlmis_id']] = $row['dhis_id'];
         }
       
	return $array;
}

public function Selectuc(){
    
  
         $sql = "SELECT vlmis_dhis.vlmis_id, vlmis_dhis.dhis_id FROM vlmis_dhis WHERE vlmis_dhis.type = 'uc'";
    
	 $res = mysqli_query($this->connection, $sql);
         
         $array='';
         
         while ($row = $res->fetch_array()) 
         {
             $array[$row['vlmis_id']] = $row['dhis_id'];
         }
       
	return $array;
}

public function Selectepi(){
    
  
         $sql = "SELECT vlmis_dhis.vlmis_id, vlmis_dhis.dhis_id FROM vlmis_dhis WHERE vlmis_dhis.type = 'epi'";
    
	 $res = mysqli_query($this->connection, $sql);
         
         $array='';
         
         while ($row = $res->fetch_array()) 
         {
             $array[$row['vlmis_id']] = $row['dhis_id'];
         }
       
	return $array;
}

public function create($dist_code,$pk_id,$map)
        {
        
	$sql = "INSERT INTO `vlmis_dhis` (dhis_id,vlmis_id,type ) VALUES " . "( '$dist_code','$pk_id','$map' )";
        
	$res = mysqli_query($this->connection, $sql);
        
	if($res){
 		return true;
	}
        else{
		return false;
	}
        
}

public function districtdropdown(){
    
	 $sql =  "SELECT * FROM `locations` WHERE  parent_id = 1  AND geo_level_id = 4";
         
	 $res = mysqli_query($this->connection, $sql);
         
	 return $res;
}

public function tehsildropdown(){
    
        $sql = "SELECT * FROM `locations` WHERE parent_id = ".$_REQUEST['pk_id']."";
    
        $res = mysqli_query($this->connection, $sql);
         
	return $res;
}

public function ucdropdown(){
    
       $sql = "SELECT * FROM `locations` WHERE parent_id = ".$_REQUEST['pk_id']."";
   
        $res = mysqli_query($this->connection, $sql);
         
	return $res;
}

    }
    
   

$database = new Database();
$database->connect_db();    