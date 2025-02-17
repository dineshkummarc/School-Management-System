<?php

/**
*
*/

class batch
{
	
	 public function __construct(){
     
     $this->db=new database();
     $this->conn=$this->db->conn;
     
 }

 public function select($query){
   return $this->result=$this->db->select($query);
  }

 public function day($st){
 	$day=array();
 	for($i=0; $i<strlen($st); $i++){
 		if($st[$i]!=','){

 			if($st[$i]=='1')$day[1]="Saturday";
 		    else if($st[$i]=='2')$day[2]="Sunday";
 		    else if($st[$i]=='3')$day[3]="Monday";
 		    else if($st[$i]=='4')$day[4]="Tuesday";
 		    else if($st[$i]=='5')$day[5]="Wednesday";
 		    else if($st[$i]=='6')$day[6]="Thursday";
 		    else if($st[$i]=='7')$day[7]="Friday"; 
 
 		}
 	}
 	return $day;
 }

 public function day_index(){
      $day[1]="Saturday";
      $day[2]="Sunday";
      $day[3]="Monday";
      $day[4]="Tuesday";
      $day[5]="Wednesday";
      $day[6]="Thursday";
      $day[7]="Friday";
      return $day;
 }

public function num_array($st){
  $num=array();
  $n=0;
  $c=0;
  for($i=0; $i<strlen($st); $i++){
    if($st[$i]==','){
      array_push($num, $n);
        $n=1;
        $c=0;
    }
    else{
            
            $n=($n*$c)+(int)$st[$i];
            $c=10;
    }
  }
  return $num;
 } 

 public function convert_arr($arr){
 //convert arr to string ex:a[2]={1,2} output: st="1,2";
   $st="";
   $c=0;
   $si=sizeof($arr);
   foreach ($arr as $key => $value) {
      $c++;
      $s=$value;
      $st=$st.$s;
     if($si!=$c) $st=$st.' , ';
   }
return $st;
 } 

  public function batch_info(){
      $info=array();
      $sub=array();
     $sql="select * from batch";
     $res=$this->select($sql);
     while ($row=mysqli_fetch_array($res)) {
     	$id=$row['id'];
     	$sub["id"]=$row['id'];
     	$sub["name"]=$row['name'];
     	$sub["start"]=$row['start'];
     	$sub["end"]=$row['end'];
     	$sub["day"]=$this->day($row['day']);
      $sub["day_string"]=$this->convert_arr($sub["day"]);
      $sub["day"]=$row['day'];
      $info[$id]=$sub;
     }
	 return $info;
  }

  public function selectd_day($st){
         $arr=$this->num_array($st);
         $day_index=$this->day_index();
         $mark=array();
         for($i=1; $i<=7; $i++)$mark[$i]=0;
         for($i=0; $i<sizeof($arr); $i++){
             $mark[$arr[$i]]=1;
         }
         for($i=1; $i<=7; $i++){
           $day=$day_index[$i];
           if($mark[$i]==1){
             echo "<input type='checkbox' name='batch_day[]' value='$i' checked> $day<br>";
           }
           else{
             echo "<input type='checkbox' name='batch_day[]' value='$i'> $day<br>";
           }

         }
  }


  public function get_batch_name($batch_id){
  $info=$this->batch_info();
  if($batch_id==0)$res="";
  else $res="'".$info[$batch_id]['name']."'";
  return $res;
}

}

?>