<?php

	require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    
	$data = file_get_contents("php://input");
	$json = json_decode($data, true);
	
    $nama = $conn-> real_escape_string($json['nama']);
    $alamat = $conn-> real_escape_string($json['alamat']);
    $telepon = $conn-> real_escape_string($json['telepon']);
    $desa = $conn-> real_escape_string($json['desa']);
    $kecamatan = $conn-> real_escape_string($json['kecamatan']);
    $kota = $conn-> real_escape_string($json['kota']);
    $provinsi = $conn-> real_escape_string($json['provinsi']);
    $latitude = $conn-> real_escape_string($json['latitude']);
    $longitude = $conn-> real_escape_string($json['longitude']);
    $push=$conn-> real_escape_string($json['push']);
    $catatan = $conn-> real_escape_string($json['catatan']);
    $filePath=$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$conn-> real_escape_string($json["image"]);
    
    $response = (object)array();
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE push='$push'");
    $row = mysqli_fetch_array($query);
    $email=$row['email'];
    
    $query2 = mysqli_query($conn, "SELECT * FROM sdata_pemilik WHERE nama='$nama' AND email!='$email'");
    $row2 = mysqli_fetch_array($query2);
    $namacek= isset($row2['nama']) ?  $row2['nama']:null;
	
	    
    if($row['push']==$push && $push!=''){
	    if($namacek==null){
    	
        	if((empty($nama))  || (empty($alamat)) || (empty($desa)) || (empty($kecamatan)) || (empty($kota)) || (empty($provinsi)) || (empty($latitude)) || (empty($longitude)) ||(empty($catatan)) || (empty($telepon))){ 
        	    
             	$response->success = 0;
             	$response->message = "Kolom tidak boleh kosong.";
             	die(json_encode($response));
             	
            }else{
                
                $query2 = mysqli_query($conn, "SELECT * FROM sdata_pemilik WHERE email='$email'");
                $row2 = mysqli_fetch_array($query2);
                $id_pemilik= isset($row2['id_pemilik']) ?  $row2['id_pemilik']:null;
                
                if((empty($json["image"]))){
                    
                    if($id_pemilik==null){
                        $image='167210996X.png';
                        
                    }else{
                        $image=$row2['gambar'];
                    }
                    
                }else{
                    
                    if (file_exists($filePath)){
                        
                        $temp = explode(".", $filePath);
                        $newfilename = round(microtime(true)) . '.' . end($temp);
                        rename( $filePath,$_SERVER['DOCUMENT_ROOT'].'/uploads/'.$newfilename);
                        $image=$newfilename;
                    }
            	 	
                }
                
                
                if($id_pemilik==null){
                    $query3 = mysqli_query($conn, "insert into sdata_pemilik values(null,'$nama','$image','$email','$alamat','$latitude','$longitude','$provinsi','$kota','$kecamatan','$desa','$telepon','$catatan','0','$ts',null)");
                    
                }else{
                    $query3 = mysqli_query($conn, "UPDATE sdata_pemilik SET longitude='$longitude',gambar='$image',latitude='$latitude',nama='$nama',email='$email',alamat='$alamat',provinsi='$provinsi',catatan='$catatan',kota='$kota',kecamatan='$kecamatan',desa='$desa',telepon='$telepon',updated_at='$ts' WHERE id_pemilik='$id_pemilik'");
                }
                
                
                
                if($query3){
                    
                    $response->success = 1;
                    $response->message = "Data Berhasil diupdate.";
                 	die(json_encode($response));
                	 	
                }else{
                    
                    $response->success = 0;
                    $response->message = "Silakan periksa kembali input anda.";
                 	die(json_encode($response));
                
                }   
            }
    	
    	}else{
    	    
            $response->success = 0;
            $response->message = "Nama toko sudah ada.";
            die(json_encode($response));
            
        }
	    
	}else{
    	    
        $response->success = 0;
        $response->message = "API ini telah dilindungi. :)";
        die(json_encode($response));
        
    }
    
	mysqli_close($conn);

?>