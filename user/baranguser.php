<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
    $push=$conn-> real_escape_string($json['push']);
    
    $response = (object)array();
	
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $email=$row['email'];
    
    if($row['push']==$push && $push!=''){
        
    	$query2 = mysqli_query($conn, "SELECT barang.*,sdata_pemilik.nama as nama_toko,kategori.* FROM barang INNER JOIN kategori on kategori.id_kategori = barang.id_kategori INNER JOIN sdata_pemilik on sdata_pemilik.id_pemilik=barang.id_pemilik where sdata_pemilik.email='$email' AND barang.status_barang!='Hapus' order by barang.nama asc");
    	$data = array();
    	$index=0;
    	
    	while($row2 = mysqli_fetch_array($query2)){
    	    
    	    $gambar=array();
    	    $id_barang=$row2['id_barang'];
    	    $query3 = mysqli_query($conn, "SELECT * FROM foto where id_barang='$id_barang'");
    	    
    	    while($row3 = mysqli_fetch_array($query3)){
    	        
    	        array_push($gambar,array(
        		    "id_foto"=>$row3['id_foto'],
        		    "id_barang"=>$row3['id_barang'],
        		    "url"=>$row3['url'],
        		    "skip"=>1,
        			"gambars"=>$web."master/uploads/".$row3['url'],
        		));
        		
    	    }
    	    
    		array_push($data,array(
    		    "image"=>0,
    		    "index"=>$index,
    		    "id_barang"=>$row2['id_barang'],
    		    "id_pemilik"=>$row2['id_pemilik'],
    		    "id_kategori"=>$row2['id_kategori'],
    			"nama"=>$row2['nama'],
    			"status_barang"=>$row2['status_barang'],
    			"nama_toko"=>$row2['nama_toko'],
    			"kategori"=>$row2['kategori'],
    			"deskripsi"=>$row2['deskripsi'],
    			"stok"=>(int)$row2['stok'],
    			"stok_lama"=>(int)$row2['stok'],
    			"dis"=>$row2['dis'],
    			"kondisi"=>$row2['kondisi'],
    			"berat"=>$row2['berat'],
    			"harga_dasar"=>(int)$row2['harga_dasar'],
    			"harga"=>(int)$row2['harga'],
    	       "stok_flash_sale"=>$row2['stok_flash_sale'],
    	       "mulai_flash_sale"=>$row2['mulai_flash_sale'],
    	       "selesai_flash_sale"=>$row2['selesai_flash_sale'],
    			"gambar"=>$gambar,
    			"jumlah"=>0,
    			"visual"=>true
    		));
    		$index+=1;
    		
    	}
    	
        if($query){
            
            $response->data=$data;
            $response->success=1;
    	 	$response->message = "Data barang sukses ditampilkan";
    	 	echo json_encode($response);
    	 	
    	} else { 
    	    
            $response->data=[];
            $response->success=0;
    	 	$response->message = "Data barang gagal ditampilkan";
    	 	echo json_encode($response);
    	 	
    	}
	
    }else{
        
        $response->data=[];
        $response->success=0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
    mysqli_close($conn);


?>