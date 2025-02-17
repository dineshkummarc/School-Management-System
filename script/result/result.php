<?php


class result {
   


public $student;
public $student_ob;
public $exam;

//starting connection

 public function __construct(){
     
     $this->db=new database();
     $this->conn=$this->db->conn;
     $this->student_ob=new student();
     $this->student=$this->student_ob->get_student_info();
     $this->exam=new exam();
     $this->exam=$this->exam->get_exam_info();

 }

 public function select($query){
 	$batch=$this->result=$this->db->select($query);
   return $batch;
  }

 

public function batch_return($id){
	return $this->student[$id]['batch'];
}

  public function get_result_info1($exam_id1){

   $sql="SELECT * FROM result ORDER BY total DESC";
   $res=$this->select($sql);
   $sub_array=array();
   $info=array();
   $c=0;
   $batch_merit=array();

for ($i=0; $i <100007 ; $i++) { 
	$batch_merit[$i]=0;
}

   while ($row=mysqli_fetch_array($res)) {

   	   $id=$row['student_id'];
   	   
   	   //echo "$c<br/>";
   	   $exam_id=$row['exam_id'];
   	   $sub_array['exam_id']=$exam_id;
       $sub_array['id']=$row['id'];
       $sub_array['student_id']=$row['student_id'];
       $sub_array['mcq']=$row['mcq'];
       $sub_array['written']=$row['written'];
       $sub_array['total']=$row['total'];
       $sub_array['date']=$row['date'];
       $sub_array['add_by']=$row['add_by'];
       $sub_array['sms']=$row['sms'];
       $batch_id=$this->batch_return($row['student_id']);
      if($exam_id==$exam_id1){
        $c++;
        $batch_merit[$batch_id]++;
        $sub_array['center_merit']=$c;
        $sub_array['batch_merit']=$batch_merit[$batch_id];
      	$info[$id]=$sub_array;
      }
   }
return $info;
  }





  public function get_result_info(){

  $info=array();
  foreach ($this->exam as $key => $value) {
  	$id=$value['id'];
  	$sub_array=$this->get_result_info1($id);
  	$info[$id]=$sub_array;
  }

return $info;
  }

  public function get_result(){
   $sql="SELECT * FROM result ORDER BY id DESC";
   $res=$this->select($sql);
   $sub_array=array();
   $info=array();


   while ($row=mysqli_fetch_array($res)) {
       $id=$row['id'];
       $student_id=$row['student_id'];
       $exam_id=$row['exam_id'];
       $sub_array['exam_id']=$exam_id;
       $sub_array['id']=$row['id'];
       $sub_array['student_id']=$row['student_id'];
       $sub_array['mcq']=$row['mcq'];
       $sub_array['written']=$row['written'];
       $sub_array['total']=$row['total'];
       $sub_array['date']=$row['date'];
       $sub_array['add_by']=$row['add_by'];
       $sub_array['sms']=$row['sms'];
       $info[$id]=$sub_array;
     }
     return $info;
  }

//end dabtabase connection

public function search_array($arr,$id){
	$flag=0;
	//print_r($arr);
	//echo "<br/>";
	foreach ($arr as $key => $value) {
		$id1=$key;
		if($id1==$id){
			$flag=1;
			break;
		}
	}
	return $flag;
}


public function student_result($student_id,$exam_id){
	$info=$this->get_result_info();
	$res=array();
	$flag=$this->search_array($info,$exam_id);
	if($flag==1){
		$res=$info[$exam_id];
	  $flag=$this->search_array($res,$student_id);
	  if($flag==1){
	  	return $res[$student_id];
	  }
	}

	return -1;
}


public function best_mark($exam_id){
  $info=$this->get_result_info();
  $res=$info[$exam_id];
  $best=0;
  foreach ($res as $key => $value) {
    $best=$value['total'];
    break;
  }
  return $best;
}

  public function permission_for_save($student_id,$exam_id,$program_id){

  	   $info=$this->student;
  	   $exam_info=$this->get_result_info();
  	 
      if($student_id<1000 ||  $student_id=="")return 0;
     // search student find
     
     $flag=0;
      foreach ($info as $key => $value) {
        $sid=$value['id'];
        if($sid==$student_id){
          $flag=1;
          break;
        }
      }

  	  if($flag==0)return $flag;


    // search student id is given exam   
  	   $flag=1;
  	   foreach ($exam_info[$exam_id] as $key => $value) {
  	   	 $id=$value['student_id'];
  	   	 if($id==$student_id){
  	   	 	$flag=0;
  	   	 	break;
  	   	 }
  	   }

     if($flag==0)return $flag;

     $flag=0;
     $pro_id=$info[$student_id]['program'];
     if($pro_id==$program_id)$flag=1;

  	return $flag;

  }

  public function get_avilable_sms_user($exam_id){
      $exam_info=$this->get_result_info1($exam_id);
      $info=array();
      foreach ($exam_info as $key => $value) {
        $id=$value['id'];
        $sms=$value['sms'];
        if($sms==0){
          $info[$id]=$value;
      }
        }
        return $info;
  }

  public function result_sms($value){
    $student_id=$value['student_id'];
    $exam_id=$value['exam_id'];
    $mcq=$value['mcq'];
    $written=$value['written'];
    $total=$value['total'];

    $nick=$this->student[$student_id]['nick'];
    $exam_info=$this->exam;
    
    $exam_name=$exam_info[$exam_id]['exam_name'];
    $exam_mcq=$exam_info[$exam_id]['mcq'];
    $exam_written=$exam_info[$exam_id]['written'];
    $exam_total=$exam_info[$exam_id]['total'];

    $msg="Dear ".$nick.",\n";
    $msg.="Your obtained marks for the exam '".$exam_name."' is \n";

    if($exam_mcq!="N/A")$msg.="MCQ: ".$mcq."/".$exam_mcq."\n";
    if($exam_written!="N/A")$msg.="Written: ".$written."/".$exam_written."\n";

    $msg.="Total: ".$total."/".$exam_total."\n";
    $msg.="\n YOUTH";

    return $msg;
  }


}


?>

