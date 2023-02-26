<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$id_barang=$conn-> real_escape_string($json['id_barang']);
	
    $response = (object)array();
    
    $data=array();
	$index=0;
	$query=mysqli_query($conn, "SELECT sdata_barang.*,sdata_pemilik.catatan,sdata_pemilik.gambar as gambar_toko,sdata_pemilik.nama as toko,sdata_pemilik.telepon FROM sdata_barang LEFT JOIN sdata_pemilik on sdata_barang.id_pemilik=sdata_pemilik.id_pemilik WHERE sdata_barang.id_barang='$id_barang' AND sdata_barang.status_post='Publish'");
	
	while($row= mysqli_fetch_array($query)){
	    $gambar=array();
	    $id_barang=$row['id_barang'];
	    $query1 = mysqli_query($conn, "SELECT * FROM gambar where id_barang='$id_barang'");
	    $idx=0;
	    while($row1 = mysqli_fetch_array($query1)){
	       
	        array_push($gambar,array(
    		    "index"=>$idx,
    		    "id_gambar"=>$row1['id_gambar'],
    		    "id_barang"=>$row1['id_barang'],
    		    "url"=>$row1['url'],
    		    "skip"=>1,
    			"gambars"=>$url."master/uploads/".$row1['url'],
    		));
    		$idx+=1;
    		
	    }
	    
	   $dis=$row['dis'];
	   $harga=$row['harga'];
	   $harga_dis=$harga-$harga*$dis/100;
	   array_push($data,array(
		    "image"=>0,
	       "index"=>$index,
	       "id_barang"=>$row['id_barang'],
	       "nama"=>$row['nama'],
	       "toko"=>$row['toko'],
	       "catatan"=>$row['catatan'],
	       "gambar_toko"=>$url."uploads/".$row['gambar_toko'],
	       "telepon"=>'62'.substr($row['telepon'], 1),
	       "harga"=>$harga,
	       "harga_dis"=>$harga_dis,
	       "dis"=>$dis,
	       "gambar"=>$gambar,
	       "berat"=>$row['berat'],
	       "kondisi"=>"-",
	       "deskripsi"=>$row['deskripsi'],
	       "stok"=>$row['stok'],
	       "jumlah"=>1,
	       "pilih"=>0,
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
   
	mysqli_close($conn);

?>