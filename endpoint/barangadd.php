<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	
    $push           =$conn-> real_escape_string($json["push"]);
    $nama    =$conn-> real_escape_string($json["nama"]);
    $id_kategori    =$conn-> real_escape_string($json["id_kategori"]);
    $harga_dasar    =$conn-> real_escape_string($json["harga_dasar"]);
    $harga     =$harga_dasar+1000;
    $deskripsi     =$conn-> real_escape_string($json["deskripsi"]);
    $berat     =$conn-> real_escape_string($json["berat"]);
    $kondisi     =$conn-> real_escape_string($json["kondisi"]);
    $dis     =$conn-> real_escape_string($json["dis"]);
    $stok     =$conn-> real_escape_string($json["stok"]);
    $stok_flash_sale     =$conn-> real_escape_string($json["stok_flash_sale"]);
    $mulai     =$conn-> real_escape_string($json["mulai"]);
    $selesai     =$conn-> real_escape_string($json["selesai"]);
    $filePath=$json["gambars"];
    
    $response = (object)array();
    
    if ((empty($nama)) || (empty($id_kategori)) || (empty($harga_dasar)) || (empty($harga))|| (empty($stok))|| (empty($deskripsi)) || (empty($berat))  || count($filePath)==0) { 
        
        $response->success = 0;
        $response->message = "Kolom dan gambar tidak boleh kosong";
        die(json_encode($response));
        
    }else{
	
        $query = mysqli_query($conn, "SELECT users.push,sdata_pemilik.id_pemilik FROM users LEFT JOIN sdata_pemilik on users.email=sdata_pemilik.email WHERE users.push='$push'");
        $row = mysqli_fetch_array($query);
        $id_pemilik=$row['id_pemilik'];
        
        if($row['push']==$push && $push!=''){
            
        	if($harga_dasar>$harga){
        	    
        	    $response->success = 0;
                $response->message = "Harga jual harus lebih dari harga dasar.";
                die(json_encode($response));
                
        	}else{
        	     if($dis>0){
                    $query5 = mysqli_query($conn, "SELECT * FROM konfigurasi WHERE id_konfigurasi in (5,6)");
                    while($row5 = mysqli_fetch_array($query5)){
                        if($row5['id_konfigurasi']==5){
                            $mulai=$row5['status'];
                        }else{
                            $selesai=$row5['status'];
                        }
                    }  
                }else{
                    $mulai='0000-00-00';
                    $selesai='0000-00-00';
                }
        	    
                $query = mysqli_query($conn, "insert into barang values(null,'$id_pemilik','$id_kategori','$nama','$dis','$harga','$harga_dasar','','$deskripsi','$stok','$berat','Baru','$stok_flash_sale','$mulai','$selesai','Tinjau','$ts',null)");
                $id_barang=mysqli_insert_id($conn);
                $i=0;
                
                foreach($filePath as $item){
                    
                    $tempfile=$_SERVER['DOCUMENT_ROOT'].'/master/uploads/'.$item['path'];
                    
                    if (file_exists($tempfile)){
                        
                        $temp = explode(".", $tempfile);
                        $newfilename = round(microtime(true)) .$i.'.' . end($temp);
                        rename( $tempfile,$_SERVER['DOCUMENT_ROOT'].'/master/uploads/'.$newfilename);
                        $i=$i+1;
                        $query = mysqli_query($conn, "insert into foto values(null,'$id_barang','$newfilename','$ts',null)");
                        
                    }
                    
                }
                
                if($query){
                    
                    $response->success = 1;
                    $response->message = "Barang berhasil ditambahkan.";
                    echo json_encode($response);
                    
                }else{
                    
                    $response->success = 0;
                    $response->message = "Maaf input sedang bermasalah.";
                    echo json_encode($response);
                    
                }   
        	    
        	}
            
        }else{
            
            $response->success = 0;
            $response->message = "Maaf data anda tidak dikenal.";
            die(json_encode($response));
            
        }
    }


mysqli_close($conn);

?>