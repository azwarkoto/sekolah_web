<?php

    ob_start();
    $error=""; // Variable To Store Error Message
    $connection = mysqli_connect("localhost", "root", "", "sekolah");
    $txInd = $_POST['txInd'];
    $rsp=""; //Variable to store the response

    // MAPEL
    if($txInd == "TablesShow"){
    	$query = mysqli_query($connection, "SELECT * FROM mata_pelajaran");
    	$rows = mysqli_num_rows($query);

    	if($rows <= 0){
    		$rsp .= "<tr><td colspan='4'>Belum ada Mata Pelajaran</td></tr>";
    	}else{
    		for($i=0;$i<$rows;$i++){
    			$data = mysqli_fetch_array($query);
    			$rsp .= "<tr><th scope='row'>" .$data['mp_cd']. "</th>";
    			$rsp .= "<td>" .$data['nama_mp']. "</td>";
    			$rsp .= "<td>" .$data['kelas']. "</td>";
    			$rsp .= "<td><input data-id='" .$data['mp_cd']. "' class='btn-delete-mp btn btn-md btn-primary btn-info btn-block' type='submit' value='Delete'></td></tr>";
    		}
    	}

    	echo $rsp;

    }elseif($txInd == "mapel-id"){
    	$kelas = $_POST['kelas'];

    	if($kelas == "X"){
    		$query = mysqli_query($connection, "SELECT substr(mp_cd,5) as max FROM mata_pelajaran WHERE kelas = '$kelas' ORDER BY mp_cd DESC");	
    	}elseif($kelas == "XI"){
    		$query = mysqli_query($connection, "SELECT substr(mp_cd,6) as max FROM mata_pelajaran WHERE kelas = '$kelas' ORDER BY mp_cd DESC");
    	}else{
    		$query = mysqli_query($connection, "SELECT substr(mp_cd,7) as max FROM mata_pelajaran WHERE kelas = '$kelas' ORDER BY mp_cd DESC");
    	}
    	
    	$data = mysqli_fetch_array($query);

    	$maxPlus = $data['max'] + 1;

    	echo $maxPlus;
    }elseif($txInd == "mapel-insert"){
    	$mpCd = $_POST['mpCd'];
    	$mpNm = $_POST['mpNm'];
    	$kelas = $_POST['kelas'];

    	$query = mysqli_query($connection, "INSERT INTO mata_pelajaran VALUES('$mpCd','$mpNm','$kelas')");
    }elseif($txInd == "mapel-for-guru"){
    	$query = mysqli_query($connection, "SELECT * FROM mata_pelajaran ORDER BY nama_mp");
		$rows = mysqli_num_rows($query);

		$rsp .= "<select class='form-control guru-mp-cd' placeholder='Mata Pelajaran'>";
        $rsp .= "<option value='0' selected hidden readonly>Mata Pelajaran</option>";

    	if($rows <= 0){
    		$rsp .= "<option value='0'>Belum Ada Mata Pelajaran</option>";
    	}else{
    		for($i=0;$i<$rows;$i++){
    			$data = mysqli_fetch_array($query);
    			$rsp .= "<option value='" .$data['mp_cd']. "'>" .$data['nama_mp']. "</option>";
    		}
    	}

    	$rsp .= "</select>";

    	echo $rsp;

    //GURU
    }elseif($txInd == "guruCd-find"){
    	$mpCd = $_POST['mpCd'];

    	$query = mysqli_query($connection, "SELECT count(1) as max FROM guru INNER JOIN mata_pelajaran on guru.mp_cd = mata_pelajaran.mp_cd WHERE guru.mp_cd = '$mpCd'");

    	$data = mysqli_fetch_array($query);

    	echo $data['max'];

    }elseif($txInd == "guru-insert"){
    	$guruCd = $_POST['guruCd'];
    	$guruNm = $_POST['guruNm'];
    	$guruNIP = $_POST['guruNIP'];
    	$guruJnsKlm = $_POST['guruJnsKlm'];
    	$guruTptLhr = $_POST['guruTptLhr'];
    	$guruTglLhr = $_POST['guruTglLhr'];
    	$guruAlmt = $_POST['guruAlmt'];
    	$guruTlp = $_POST['guruTlp'];
    	$guruPddk = $_POST['guruPddk'];
    	$guruJbtn = $_POST['guruJbtn'];
    	$guruPass = $_POST['guruPass'];
    	$guruMp = $_POST['guruMp'];

    	$query = mysqli_query($connection, "SELECT * FROM guru WHERE nip = '$guruNIP'");
    	$rows = mysqli_num_rows($query);

    	$rsp = '1';

    	if($rows <= 0){
    		$query = mysqli_query($connection, "INSERT INTO guru VALUES('$guruCd','$guruNm','$guruNIP','$guruJnsKlm','$guruTptLhr','$guruTglLhr','$guruAlmt','$guruTlp','$guruPddk','$guruJbtn','$guruPass','$guruMp',1)");  		
    	}else{
    		$rsp = '0';
    	}

    	echo $rsp;	

    //KELAS	
    }elseif($txInd == "guru-for-kelas"){
    	$query = mysqli_query($connection, "SELECT * FROM guru ORDER BY nama");
		$rows = mysqli_num_rows($query);

		$rsp .= "<select class='form-control kelas-guru-cd' placeholder='Wali Kelas'>";
        $rsp .= "<option value='0' selected hidden readonly>Wali Kelas</option>";

    	if($rows <= 0){
    		$rsp .= "<option value='0'>Belum Ada Guru</option>";
    	}else{
    		for($i=0;$i<$rows;$i++){
    			$data = mysqli_fetch_array($query);
    			$rsp .= "<option value='" .$data['guru_cd']. "'>" .$data['nama']. "</option>";
    		}
    	}

    	$rsp .= "</select>";

    	echo $rsp;
    }elseif($txInd == "kelas-find"){
    	$kls = $_POST['kls'];

    	$query = mysqli_query($connection, "SELECT count(1) as max FROM kelas WHERE kelas = '$kls'");

    	$data = mysqli_fetch_array($query);

    	echo $data['max'];

    }elseif($txInd == "kls-insert"){
    	$klsGuruCd = $_POST['klsGuruCd'];
    	$kelasKls = $_POST['kelasKls'];
    	$klsCd = $_POST['klsCd'];

        $hari = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat");
    	$query = mysqli_query($connection, "INSERT INTO kelas VALUES('$klsCd','$kelasKls','$klsGuruCd')");
        for($i=0;$i<5;$i++){
            for($j=1;$j<7;$j++){
                $query = mysqli_query($connection, "INSERT INTO jadwal(kelas_cd,hari,jam) VALUES('$klsCd','$hari[$i]',$j)");
            }
        }
    
    //JADWAL
    }elseif($txInd == "jadwal-drop-down"){
        
        $query = mysqli_query($connection, "SELECT * FROM kelas ORDER BY kelas_cd");
        $rows = mysqli_num_rows($query);

        $rsp .= "<select class='form-control jadwal-kls-cd' placeholder='Kelas'>";
        $rsp .= "<option value='0' selected hidden readonly>Kelas</option>";

        if($rows <= 0){
            $rsp .= "<option value='0'>Belum Ada Kelas</option>";
        }else{
            for($i=0;$i<$rows;$i++){
                $data = mysqli_fetch_array($query);
                $rsp .= "<option value='" .$data['kelas_cd']. "'>" .$data['kelas_cd']. "</option>";
            }
        }

        $rsp .= "</select>";

        echo $rsp;

    }elseif($txInd == "jadwal-find"){
        $kls = $_POST['kls'];
        $hari = $_POST['hari'];
        $jam = $_POST['jam'];

        $queryKls = mysqli_query($connection, "SELECT * FROM mata_pelajaran mp LEFT JOIN kelas kl on mp.kelas = kl.kelas WHERE kl.kelas_cd = '$kls'");
        $rowsKls = mysqli_num_rows($queryKls);

        $query = mysqli_query($connection, "SELECT * FROM jadwal jd LEFT JOIN mata_pelajaran mp on jd.mp_cd = mp.mp_cd WHERE kelas_cd = '$kls' AND hari = '$hari' AND jam = '$jam'");
        $rows = mysqli_num_rows($query);

        $rsp .= "<select class='form-control jadwal-mp-cd' placeholder='Mata-Pelajaran'>";
        //$rsp .= "<option value='0' selected hidden readonly>Mata Pelajaran</option>";

        $data = mysqli_fetch_array($query);

        if($data['nama_mp'] == ''){
            $rsp .= "<option value='0' selected hidden readonly>MaPel Belum dipilih</option>";
            for($i=0;$i<$rowsKls;$i++){
                $dataMp = mysqli_fetch_array($queryKls);
                $rsp .= "<option value='" .$dataMp['mp_cd']. "'>" .$dataMp['nama_mp']. "</option>";
            }
        }else{
            for($i=0;$i<$rows;$i++){
                $rsp .= "<option value='" .$data['mp_cd']. "' hidden selected>" .$data['nama_mp']. "</option>";
                        $data = mysqli_fetch_array($query);
            }
            for($i=0;$i<$rowsKls;$i++){
                $dataMp = mysqli_fetch_array($queryKls);
                $rsp .= "<option value='" .$dataMp['mp_cd']. "'>" .$dataMp['nama_mp']. "</option>";
            }
        }

        $rsp .= "</select>";

        echo $rsp;
    
    }elseif($txInd == "jadwal-guru-find"){
        $kls = $_POST['kls'];
        $hari = $_POST['hari'];
        $jam = $_POST['jam'];
        $mpCd = $_POST['mpCd'];

        $queryGr = mysqli_query($connection, "SELECT * FROM guru WHERE mp_cd = '$mpCd'");
        $rowsGr = mysqli_num_rows($queryGr);

        $query = mysqli_query($connection, "SELECT * FROM jadwal jd LEFT JOIN guru gr on jd.guru_cd = gr.guru_cd WHERE kelas_cd = '$kls' AND hari = '$hari' AND jam = '$jam'");
        $rows = mysqli_num_rows($query);

        $rsp .= "<select class='form-control jadwal-guru-cd' placeholder='Guru'>";
        //$rsp .= "<option value='0' selected hidden readonly>Mata Pelajaran</option>";

        $data = mysqli_fetch_array($query);

        if(strlen($data['nama']) <= 1 || $data['mp_cd'] != $mpCd ){
            $rsp .= "<option value='0' selected hidden readonly>Guru Belum dipilih</option>";
            for($i=0;$i<$rowsGr;$i++){
                $dataGr = mysqli_fetch_array($queryGr);
                $rsp .= "<option value='" .$dataGr['guru_cd']. "'>" .$dataGr['nama']. "</option>";
            }
        }else{
            for($i=0;$i<$rows;$i++){
                $rsp .= "<option value='" .$data['guru_cd']. "' hidden selected>" .$data['nama']. "</option>";
                        $data = mysqli_fetch_array($query);
            }
            for($i=0;$i<$rowsGr;$i++){
                $dataGr = mysqli_fetch_array($queryGr);
                $rsp .= "<option value='" .$dataGr['guru_cd']. "'>" .$dataGr['nama']. "</option>";
            }
        }

        $rsp .= "</select>";

        echo $rsp;

    }elseif($txInd == "jadwal-insert"){
        $kls = $_POST['kls'];
        $hari = $_POST['hari'];
        $jam = $_POST['jam'];
        $mpCd = $_POST['mpCd'];
        $guruCd = $_POST['guruCd'];

        $query = mysqli_query($connection, "UPDATE jadwal SET mp_cd = '$mpCd', guru_cd = '$guruCd' WHERE kelas_cd = '$kls' AND hari = '$hari' AND jam = '$jam'");

    // SISWA

    }elseif($txInd == "siswa-drop-down"){
        
        $query = mysqli_query($connection, "SELECT * FROM kelas ORDER BY kelas_cd");
        $rows = mysqli_num_rows($query);

        $rsp .= "<select class='form-control siswa-kls-cd' placeholder='Kelas'>";
        $rsp .= "<option value='0' selected hidden readonly>Kelas</option>";

        if($rows <= 0){
            $rsp .= "<option value='0'>Belum Ada Kelas</option>";
        }else{
            for($i=0;$i<$rows;$i++){
                $data = mysqli_fetch_array($query);
                $rsp .= "<option value='" .$data['kelas_cd']. "'>" .$data['kelas_cd']. "</option>";
            }
        }

        $rsp .= "</select>";

        echo $rsp;

    }elseif($txInd == "siswa-insert"){
        $sNisn = $_POST['sNisn'];
        $sNm = $_POST['sNm'];
        $sKls = $_POST['sKls'];
        $sThnMsk = $_POST['sThnMsk'];
        $sPass = $_POST['sPass'];

        $queryCk = mysqli_query($connection, "SELECT * FROM siswa where nisn = '$sNisn'");
        $rows = mysqli_num_rows($queryCk);

        $rsp = "Siswa $sNm berhasil diinput kedalam database";
        if($rows <= 0){
            $query = mysqli_query($connection, "INSERT INTO siswa(nisn,nama,kelas_cd,tahun_masuk,password,alumni_ind,first_login_ind) VALUES('$sNisn','$sNm','$sKls','$sThnMsk','$sPass',0,0)");
        }else{
            $rsp = "NISN telah digunakan!";
        }
        
        echo $rsp;
    // SISWA

    }

?>