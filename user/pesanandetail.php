<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/user/vendor/autoload.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$push=$conn-> real_escape_string($json['push']);
	$id_checkout=$conn-> real_escape_string($json['id']);
	
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    
    if($row['push']==$push && $push!=''){
        
        $query1 = mysqli_query($conn, "SELECT * FROM sdata_listcheckout INNER JOIN sdata_barang ON sdata_listcheckout.id_barang=sdata_barang.id_barang where sdata_listcheckout.id_checkout='$id_checkout'");
    	$dataDetail=array();
    	$index=0;
    	
    	while($row1= mysqli_fetch_array($query1)){
    	    
    	    $gambar=array();
    	    $id_barang=$row1['id_barang'];
    	    $query2 = mysqli_query($conn, "SELECT * FROM gambar where id_barang='$id_barang'");
    	    
    	    while($row2 = mysqli_fetch_array($query2)){
    	        
    	        array_push($gambar,array(
        		    "id_gambar"=>$row2['id_gambar'],
        		    "id_barang"=>$row2['id_barang'],
        		    "url"=>$row2['url'],
        		    "skip"=>1,
        			"gambars"=>$web."master/uploads/".$row2['url'],
        		));
        		
    	    }
    	    
    	   $dis=$row1['dis'];
    	   $harga=$row1['harga'];
    	   $harga_dis=$harga-$harga*$dis/100;
    	   array_push($dataDetail,array(
    		    "image"=>0,
    	       "index"=>$index,
    	       "id_barang"=>$row1['id_barang'],
    	       "id_listcheckout"=>$row1['id_listcheckout'],
    	       "nama"=>$row1['nama'],
    	       "harga"=>$harga,
    	       "harga_dis"=>(int)$harga_dis,
    	       "dis"=>$dis,
    	       "gambar"=>$gambar,
    	       "deskripsi"=>$row1['deskripsi'],
    	       "keterangan"=>$row1['keterangan'],
    	       "stok"=>(int)$row1['stok'],
    	       "jumlah"=>(int)$row1['jumlah'],
    	   ));
    	   $index=$index+1;
    	   
    	}
    	
    	$query3 = mysqli_query($conn, "SELECT sdata_checkout.*,driver.nama,driver.telepon as telepon_driver,driver.gambar FROM sdata_checkout LEFT JOIN driver ON sdata_checkout.id_driver=driver.id_driver WHERE id_checkout='$id_checkout'");
        $data=array();
    	$index=0;
    	
        
    	while($row3= mysqli_fetch_array($query3)){
    	    
    	   array_push($data,array(
    	       "index"=>$index,
    	       "status_pembayaran"=>'Lunas',
    	       "id_checkout"=>$row3['id_checkout'],
    	       "nama"=>$row3['nama'],
    	       "telepon"=>'62'.substr($row3['telepon_driver'], 1),
    	       "gambar"=>$web."uploads/".$row3['gambar'],
    	       "kode"=>$row3['kode'],
    	       "total_barang"=>$row3['total_barang'],
    	       "jenis_bayar"=>$row3['jenis_bayar'],
    	       "asuransi"=>$row3['asuransi'],
    	       "jenis_pengiriman"=>$row3['jenis_pengiriman'],
    	       "total_bayar"=>$row3['total_bayar'],
    	       "ongkir"=>$row3['ongkir'],
    	       "alamat"=>$row3['alamat'],
    	       "latitude"=>$row3['latitude'],
    	       "longitude"=>$row3['longitude'],
    	       "penerima"=>$row3['penerima'],
    	       "tanggal"=>$row3['tanggal'],
    	       "waktu"=>$row3['waktu'],
    	       "status"=>$row3['status'],
    	       "jasa_kirim"=>$row3['jasa_kirim'],
    	       "no_resi"=>$row3['no_resi'],
    	       "visual"=>false
    	   ));
    	   $index=$index+1;
    	   
    	}
    	
    	if($query3){
    	    
            $response->data=$data;
            $response->dataDetail=$dataDetail;
    	 	$response->message = "Data sukses ditampilkan";
	 	    echo json_encode($response);
	 	    
    	} else { 
    	    
            $response->data=[];
            $response->dataDetail=[];
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	 	
    	}
    	
    }else{
        
        $response->data=[];
        $response->dataDetail=[];
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
   
	mysqli_close($conn);

?>