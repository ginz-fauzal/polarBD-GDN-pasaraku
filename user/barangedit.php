<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
	
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	
    $push           =$conn-> real_escape_string($json["push"]);
    $id_barang      =$conn-> real_escape_string($json["id_barang"]);
    $nama    =$conn-> real_escape_string($json["nama"]);
    $id_kategori    =$conn-> real_escape_string($json["id_kategori"]);
    $harga_dasar    =$conn-> real_escape_string($json["harga_dasar"]);
    $harga     =$harga_dasar+1000;
    $deskripsi     =$conn-> real_escape_string($json["deskripsi"]);
    $dis     =$conn-> real_escape_string($json["dis"]);
    $berat     =$conn-> real_escape_string($json["berat"]);
    $kondisi     =$conn-> real_escape_string($json["kondisi"]);
    $stok    =$conn-> real_escape_string($json["stok"]);
    $stok_flash_sale     =$conn-> real_escape_string($json["stok_flash_sale"]);
    $mulai     =$conn-> real_escape_string($json["mulai"]);
    $selesai     =$conn-> real_escape_string($json["selesai"]);
    $filePath=$json["gambars"];
    
    $response = (object)array();
    
    if ((empty($nama))|| (empty($id_kategori)) || (empty($harga_dasar)) || (empty($harga))|| (empty($stok))|| (empty($deskripsi)) || (empty($berat))) { 
        $response->success = 0;
        $response->message = "Kolom tidak boleh kosong";
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
	
        $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
        $row = mysqli_fetch_array($query);
        
        if($row['push']==$push && $push!=''){
            
        	if($harga_dasar>$harga){
        	    $response->success = 0;
                $response->message = "Harga jual harus lebih dari harga dasar.";
                die(json_encode($response));
        	}else{
        	    
        	    $query4= mysqli_query($conn,"UPDATE `sdata_barang` SET `status_post`='Hide' WHERE index_user='$id_barang'");
                $query2 = mysqli_query($conn, "UPDATE barang SET id_kategori='$id_kategori',nama='$nama',harga_dasar='$harga_dasar',harga='$harga',deskripsi='$deskripsi',stok='$stok',kondisi='Baru',berat='$berat',dis='$dis',mulai_flash_sale='$mulai',selesai_flash_sale='$selesai',stok_flash_sale='$stok_flash_sale',updated_at='$ts',status_barang='Tinjau' WHERE id_barang='$id_barang'");
                $i=0;
                $delArray="";
                foreach($filePath as $item){
                    $tempfile=$_SERVER['DOCUMENT_ROOT'].'/master/uploads/'.$item['path'];
                    if (file_exists($tempfile) && $item['skip']==0){
                        $temp = explode(".", $tempfile);
                        $newfilename = round(microtime(true)) .$i.'.' . end($temp);
                        rename( $tempfile,$_SERVER['DOCUMENT_ROOT'].'/master/uploads/'.$newfilename);
                        $i=$i+1;
                        $query = mysqli_query($conn, "insert into foto values(null,'$id_barang','$newfilename','$ts',null)");
                        $id_foto=mysqli_insert_id($conn);
                        $delArray=$delArray.$id_foto.",";
                    }else{
                        $delArray=$delArray.$item['id_foto'].",";
                    }
                }
                $delArray=substr($delArray, 0, -1);;
                
                $query = mysqli_query($conn, "DELETE FROM foto WHERE id_barang='$id_barang' AND id_foto NOT IN ($delArray)");
                
                if($query2){
                    $response->success = 1;
                    $response->message = "Barang berhasil diubah.";
                    echo json_encode($response);
                }else{
                    $response->success = 0;
                    $response->message = "Sistem bermasalah";
                    // $response->message = $delArray;
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