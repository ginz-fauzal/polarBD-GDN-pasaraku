<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	$kategori=$conn-> real_escape_string($json['kategori']);
	$back=(int)$conn-> real_escape_string($json['back']);
	$next=(int)$conn-> real_escape_string($json['next']);
	
    $response = (object)array();

    $data=array();
	$index=$back;
	$query=mysqli_query($conn, "SELECT * FROM sdata_barang INNER JOIN kategori on kategori.id_kategori=sdata_barang.id_kategori where kategori.kategori='$kategori' AND sdata_barang.status_post='Publish' limit $back,$next");
	
	while($row= mysqli_fetch_array($query)){
	    
	    $gambar=array();
	    $id_barang=$row['id_barang'];
	    $query1 = mysqli_query($conn, "SELECT * FROM gambar where id_barang='$id_barang'");
	    
	    while($row1 = mysqli_fetch_array($query1)){
	        
	        array_push($gambar,array(
    		    "id_gambar"=>$row1['id_gambar'],
    		    "id_barang"=>$row1['id_barang'],
    		    "url"=>$row1['url'],
    		    "skip"=>1,
    			"gambars"=>$web."master/uploads/".$row1['url'],
    		));
    		
	    }
	    
	   $dis=$row['dis'];
	   $harga=$row['harga'];
	   $harga_dis=$harga-$harga*$dis/100;
	   array_push($data,array(
		   "image"=>0,
	       "index"=>$index,
	       "id_barang"=>$row['id_barang'],
	       "nama"=>$row['nama'],
	       "harga"=>$harga,
	       "harga_dis"=>$harga_dis,
	       "dis"=>$dis,
	       "gambar"=>$gambar,
	       "berat"=>$row['berat'],
	       "kondisi"=>$row['kondisi'],
	       "deskripsi"=>$row['deskripsi'],
	       "stok"=>$row['stok'],
	       "jumlah"=>1,
	       "pilih"=>0,
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
   
	mysqli_close($conn);

?>