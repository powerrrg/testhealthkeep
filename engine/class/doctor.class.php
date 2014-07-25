<?php
class Doctor{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    public function getById($id){
	    
	    $sql="select * from doctor, taxonomy where id_doctor=:id and taxonomy_code_doctor=code_taxonomy limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
   public function getAutoCompleteCcode($q,$miles){
	   $sql="select * from zipcode where zip LIKE '$q%' limit 10";
	   return $this->config_Class->query($sql,array());
   }
    
   public function search($page=1,$lname,$fname,$miles,$zip,$spec){
  
	   $zip= str_replace('-', '', $zip);
	   $zip= str_replace(' ', '', $zip);
	   $zip=(string)$zip;
	   
	   
	   if($spec==""){
		   $tsql="";
	   }else{
	   		$spec = preg_replace("/[^a-zA-Z0-9 ]/", "", urldecode($spec));
		   $tsql=" and name_taxo='$spec'";
	   }
	   
	   $miles=(int)$miles;
	   
	   if($miles>0 || $zip>0){
		   //search miles radius
	   }
	   
	   if($lname!=""){
		   $lname=" and last_name_doctor LIKE '$lname%'";
	   }
	   
	   if($fname!=""){
		   $fname=" and first_name_doctor LIKE '$fname%'";
	   }
	   $resZip=false;
	   $zsql="";
	   if($zip!=0 && $miles>0){
		   $sql="select * from zipcode where zip='$zip' limit 1";
		   $resZippo=$this->config_Class->query($sql,array());
		   if(isset($resZippo[0])){
			   $sql="SELECT *, ( 3959 * acos( cos( radians(".$resZippo[0]["latitude"].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$resZippo[0]["longitude"].") ) + sin( radians(".$resZippo[0]["latitude"].") ) * sin( radians( latitude ) ) ) ) AS distance FROM zipcode HAVING distance < $miles ORDER BY distance";
			   $res=$this->config_Class->query($sql,array());
			   if($res["result"]){
			   		$resZip=array();
				   foreach($res as $key=>$value){
					   if(is_int($key)){
						   $resZip[]=$value["zip"];
					   }
				   }
				   $resZip = "'". implode("', '", $resZip) ."'";
			   }
			   
		   }
		   //http://zipcodedistanceapi.redline13.com/API#radius
		   //it could work with this API but would limit us, mainly because of the ordering by distance
		   /*$resJson=file_get_contents("http://zipcodedistanceapi.redline13.com/rest/NjkKHRLjw5uRvXyfe6SfZklCKEAcTLIH52sNUd6rjBiPzR5OYX3XipA24aeXVWE1/radius.json/$zip/$miles/mile");
		   $result=json_decode($resJson);
		   $resZip=array();
		   foreach($result as $key0=>$value0){
		   		foreach($value0 as $key0=>$value){
			   		//print_r($value);
			   		$resZip[]=$value->zip_code;
			   		//echo $value->zip_code;
			   		//echo "<br /><br />";
			   		
			   }
		   }
		   //print_r($resZip);
		   //exit;
		   $resZip = "'". implode("', '", $resZip) ."'";*/
	   }
	   
	   if($resZip){
		   $zsql=" and zip_doctor IN (".$resZip.")";
	   }

	   if(isset($resZip[0])){
		   $sql="SELECT *, ( 3959 * acos( cos( radians(".$resZippo[0]["latitude"].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$resZippo[0]["longitude"].") ) + sin( radians(".$resZippo[0]["latitude"].") ) * sin( radians( latitude ) ) ) ) AS distance FROM profile, taxonomy,doctor,zipcode where zip_doctor=zip and taxonomy_code_doctor=code_taxonomy and npi_doctor=npi_profile $lname $fname $tsql $zsql order by distance";
	   }else{
	   $sql="select * from profile, taxonomy,doctor where taxonomy_code_doctor=code_taxonomy and npi_doctor=npi_profile $lname $fname $tsql $zsql";
	   }
	   //echo $sql;
	   $sql=$this->config_Class->getPagingQuery($sql,$page);
	   return $this->config_Class->query($sql,array());
	   
	    
   }
    
    public function getAllTaxonomy(){
	    $sql="select * from taxonomy group by name_taxonomy order by name_taxonomy";
	    return $this->config_Class->query($sql,array());
    }
    
    public function getAllNewTaxonomy(){
	    $sql="select *, sum(number_taxonomy) as total from taxonomy group by name_taxo having total>0 order by name_taxo";
	    return $this->config_Class->query($sql,array());
    }
    
    public function doctorValidate($npi){
	    $sql="select * from doctor where npi_doctor=:npi limit 1";
	    $res = $this->config_Class->query($sql,array(":npi"=>$npi));
	    
	    if($res["result"]){
		    if($res[0]["claimed_doctor"]=='1'){
			    return false;
		    }
		    
		    return $res;
	    }else{
		    return false;
	    }
    }
    
    public function updateDetails($npi,$address,$address2,$phone,$fax,$taxonomy){
	    $sql="update doctor set address_1_doctor=:address, address_2_doctor=:address2, telephone_doctor=:phone, fax_doctor=:fax, taxonomy_code_doctor=:taxonomy where npi_doctor=:npi";
	    return $this->config_Class->query($sql,array(":address"=>$address,":address2"=>$address2,":phone"=>$phone,":fax"=>$fax,":taxonomy"=>$taxonomy,":npi"=>$npi));
    }
    
    public function getByNPI($npi){
	    
	    $sql="select * from doctor, taxonomy where npi_doctor=:npi and taxonomy_code_doctor=code_taxonomy limit 1";
	    return $this->config_Class->query($sql,array("npi"=>$npi));
	    
    }
    
    public function getByUrl($url){
	    
	    $sql="select * from doctor, taxonomy where url_doctor=:url and taxonomy_code_doctor=code_taxonomy limit 1";
	    return $this->config_Class->query($sql,array("url"=>$url));
	    
    }
    
    public function getAll($start=0,$limit=30){
	    
	    $sql="select * from doctor order by last_name_doctor limit $start,$limit";
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getAutoCompleteTaxonomy($input){
	    
	    $sql="select * from taxonomy where name_taxonomy LIKE :input limit 10";
	    return $this->config_Class->query($sql,array(":input"=>"%$input%"));
	    
    }
    
    public function getAllFromTaxonomyGroup($group){
	    
	    $sql="select * from taxonomy where group_code_taxonomy=:group";
	    return $this->config_Class->query($sql,array(":group"=>$group));
	    
    }
    
    public function getAllTaxonomyGroups(){
	 
	 	$sql="select group_code_taxonomy,group_taxonomy from taxonomy group by group_code_taxonomy order by group_taxonomy";
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getAllFromTaxonomy($code){
	    
	    $sql="select * from doctor where taxonomy_code_doctor=:code";
	    return $this->config_Class->query($sql,array(":code"=>$code));
	    
    }
    
    public function getTaxonomyGroupByCode($group){
	    
	    $sql="select * from taxonomy where group_code_taxonomy=:group limit 1";
	    return $this->config_Class->query($sql,array(":group"=>$group));
	    
    }
    
    public function getTaxonomyByCode($code){
	    
	    $sql="select * from taxonomy where code_taxonomy=:code limit 1";
	    return $this->config_Class->query($sql,array(":code"=>$code));
	    
    }
    
}