<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$push=$conn-> real_escape_string($json['push']);
	
    $response = (object)array();
	
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
    
    if($row['push']==$push && $push!=''){
        $query2=mysqli_query($conn, "SELECT notifikasi.* FROM notifikasi INNER JOIN sdata_checkout on sdata_checkout.id_checkout=notifikasi.id_checkout WHERE sdata_checkout.id='$id' order by notifikasi.created_at DESC");
        $data=array();
    	$index=0;
    	while($row= mysqli_fetch_array($query2)){
    	   array_push($data,array(
    	       "index"=>$index,
    	       "id_checkout"=>$row['id_checkout'],
    	       "keterangan"=>$row['keterangan'],
    	       "status_baca"=>$row['status_baca'],
    	       "created_at"=>$row['created_at'],
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
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	}
    }else{
        $response->data=[];
        $response->success = 0;
        $response->message = "API ini telah dilindungi. ::)";
        die(json_encode($response));
    }
   
	mysqli_close($conn);

?>