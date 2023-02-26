<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    
    $response = (object)array();

	$query = mysqli_query($conn, "SELECT * FROM iklan ORDER BY RAND()");
	$data = array();
	
	$index=0;
	while($row = mysqli_fetch_array($query)){
	    
		array_push($data,array(
		    "index"=>$index,
		    "id_iklan"=>$row['id_iklan'],
			"gambar"=>$web.$row['gambar'],
		));
		$index+=1;
		
	}

    if($query){
        
        $response->data=$data;
	 	$response->message = "Data sukses ditampilkan";
	 	echo json_encode($response);
	 	
	} else { 
	    
        $response->data=[];
	 	$response->message = "Data gagal ditampilkan";
	 	echo json_encode($response);
	 	
	}
	
    mysqli_close($conn);

?>