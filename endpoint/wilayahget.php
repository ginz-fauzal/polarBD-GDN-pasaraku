<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$jenis=$conn-> real_escape_string($json['jenis']);
	$id=$conn-> real_escape_string($json['id']);
	$wilayah=$conn-> real_escape_string($json['wilayah']);
	
    $response = (object)array();
    
    if($jenis!='provinces'){
        
        if($jenis=='regencies'){
            $filter="WHERE $wilayah='$id' AND id='3217'";
        }else if($jenis=='districts'){
            $filter="WHERE $wilayah='$id' AND id='3217140'";
        }else{
            $filter="WHERE $wilayah='$id'";
        }
        
    }else{
        
        $filter="WHERE id='32'";
        
    }
	
    $query=mysqli_query($conn, "SELECT * FROM $jenis $filter order by name");
    $data=array();
	$index=0;
	
	while($row= mysqli_fetch_array($query)){
	    
	   array_push($data,array(
	       "id"=>$row['id'],
	       "name"=>$row['name'],
	       "title"=>$row['name'],
	   ));
	   $index=$index+1;
	   
	}
	if($query){
	    
        $response->data=$data;
        $response->success = 1;
	 	$response->message = "Data sukses ditampilkan";
 	    echo json_encode($response);
 	    
	} else { 
	    
        $response->data=[];
        $response->success = 0;
	 	$response->message = "Data gagal ditampilkan";
	 	echo json_encode($response);
	 	
	}
   
	mysqli_close($conn);

?>