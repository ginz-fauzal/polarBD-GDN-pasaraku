<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/user/vendor/autoload.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$push=$conn-> real_escape_string($json['push']);
	
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $id=$row['id'];
    
    if($row['push']==$push && $push!=''){
        
        $query2 = mysqli_query($conn, "SELECT * FROM sdata_checkout WHERE id='$id' AND status!='batal' order by created_at DESC");
        $data=array();
    	$index=0;
        
    	while($row= mysqli_fetch_array($query2)){
    	    
    	    $id_checkout=$row['id_checkout'];
            
    	   	array_push($data,array(
    	       "index"=>$index,
    	       "id_checkout"=>$id_checkout,
    	       "status_pembayaran"=>'Lunas',
    	       "kode"=>$row['kode'],
    	       "total_barang"=>$row['total_barang'],
    	       "asuransi"=>$row['asuransi'],
    	       "jenis_pengiriman"=>$row['jenis_pengiriman'],
    	       "jenis_bayar"=>$row['jenis_bayar'],
    	       "total_bayar"=>$row['total_bayar'],
    	       "ongkir"=>$row['ongkir'],
    	       "alamat"=>$row['alamat'],
    	       "penerima"=>$row['penerima'],
    	       "tanggal"=>$row['tanggal'],
    	       "waktu"=>$row['waktu'],
    	       "status"=>$row['status'],
    	       "jasa_kirim"=>$row['jasa_kirim'],
    	       "no_resi"=>$row['no_resi'],
    	       "visual"=>false
    	   ));
    	   $index=$index+1;
    	   
    	}
    	
    	if($query){
    	    
            $response->data=$data;
    	 	$response->message = "Data sukses ditampilkan";
	 	    echo json_encode($response);
	 	    
    	} else { 
    	    
            $response->data=[];
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	 	
    	}
    	
    }else{
        
        $response->data=[];
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
   
	mysqli_close($conn);

?>