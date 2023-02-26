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
        
        $query1=mysqli_query($conn, "SELECT * FROM sdata_keranjang WHERE id='$id'");
        $row1 = mysqli_fetch_array($query1);
        $id_keranjang=$row1['id_keranjang'];
        $query2 = mysqli_query($conn, "SELECT * FROM sdata_listkeranjang INNER JOIN sdata_barang ON sdata_listkeranjang.id_barang=sdata_barang.id_barang where sdata_listkeranjang.id_keranjang='$id_keranjang'");
    	$data=array();
    	$index=0;
    	
    	while($row2= mysqli_fetch_array($query2)){
    	    
    	    $gambar=array();
    	    $id_barang=$row2['id_barang'];
    	    $query3 = mysqli_query($conn, "SELECT * FROM gambar where id_barang='$id_barang'");
    	    
    	    while($row3 = mysqli_fetch_array($query3)){
    	        array_push($gambar,array(
        		    "id_gambar"=>$row3['id_gambar'],
        		    "id_barang"=>$row3['id_barang'],
        		    "url"=>$row3['url'],
        		    "skip"=>1,
        			"gambars"=>$web."master/uploads/".$row3['url'],
        		));
    	    }
    	    
    	   $dis=$row2['dis'];
    	   $harga=$row2['harga'];
    	   $harga_dis=$harga-$harga*$dis/100;
    	   $pilih=(int)$row2['pilih']==1 ? true:false;
    	   array_push($data,array(
    		   "image"=>0,
    	       "index"=>$index,
    	       "id_barang"=>$row2['id_barang'],
    	       "id_listkeranjang"=>$row2['id_listkeranjang'],
    	       "nama"=>$row2['nama'],
    	       "harga"=>$harga,
    	       "harga_dis"=>$harga_dis,
    	       "dis"=>$dis,
    	       "gambar"=>$gambar,
    	       "berat"=>$row2['berat'],
    	       "kondisi"=>$row2['kondisi'],
    	       "deskripsi"=>$row2['deskripsi'],
    	       "keterangan"=>$row2['keterangan'],
    	       "stok"=>(int)$row2['stok'],
    	       "jumlah"=>(int)$row2['jumlah'],
    	       "pilih"=>$pilih
    	   ));
    	   $index=$index+1;
    	   
    	}
    	
    	if($query2){
    	    
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
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
   
	mysqli_close($conn);

?>