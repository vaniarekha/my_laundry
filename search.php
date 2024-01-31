<?php

    require "DataBase.php";
    $db = new DataBase();
    $connect = $db->dbConnect();

    $search = $_POST['search'];
    $perintah = "SELECT id_toko , nama_toko , kontak_toko , alamat_toko , lat , lng FROM toko";
    if(isset($search)){
        $perintah .= "  WHERE nama_toko LIKE '%".$search."%' OR alamat_toko  LIKE '%".$search."%'";
    }
    $eksekusi = mysqli_query($connect, $perintah);
    $cek = mysqli_affected_rows($connect);

        //echo $data["nama_toko"]." ";
 
        if ($cek > 0 ) {
            $response ["code"] = 1;
            $response ["messagee"] = "Sukses";
            $response ["data"] = array();

        
            while ($ambil = mysqli_fetch_object($eksekusi)){
                $F["id_toko"] = $ambil -> id_toko;
                $F["nama_toko"] = $ambil -> nama_toko;
                $F["kontak_toko"] = $ambil -> kontak_toko;
                $F["alamat_toko"] = $ambil -> alamat_toko;
                $F["lat"] = $ambil -> lat;
                $F["lng"] = $ambil -> lng;

                array_push($response["data"], $F);

            }    
    }

    else{

     $response ["code"] = 0;
     $response ["messagee"] = "gagal";
    }

    echo json_encode($response);
    mysqli_close($connect);

?>
