<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    
    $response = (object)array();
        
	$query = mysqli_query($conn, "SELECT * FROM kategori");
	$data = array();
	
	while($row = mysqli_fetch_array($query)){
	    
		array_push($data,array(
		    "id_kategori"=>$row['id_kategori'],
			"title"=>$row['kategori'],
			"icon"=>$row['icon'],
			"warna"=>$row['warna'],
		));
		
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