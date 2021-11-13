<?php
class common {

   	public function __construct() {}

   	public function countdown($db){
   		try {
			$sql = "select 
				c.id_session,
				c.status_gelombang,
				c.max_bangku,
				a.tanggal_pendaftaran_from,
				a.tanggal_pendaftaran_to,
				a.tanggal_ujian
			 from psb_config c inner join psb_agenda a on c.status_gelombang = a.gelombang";
            $sth = $db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $tanggal_pendaftaran_from = $config->tanggal_pendaftaran_from;
            $tanggal_pendaftaran_to = $config->tanggal_pendaftaran_to;
			$tanggal_pembukaan = $tanggal_pendaftaran_from;
            $tanggal_pembukaan = date_create($tanggal_pembukaan);
            $tanggal_sekarang = date_create(date('Y-m-d'));
            $diff_from = date_diff($tanggal_sekarang,$tanggal_pembukaan);
            $selisih_tanggal_from = $diff_from->format("%R%a");
            $selisih_tanggal_from = intval($selisih_tanggal_from);

            $tanggal_penutupan = $tanggal_pendaftaran_to;
            $tanggal_penutupan = date_create($tanggal_penutupan);
            $diff_to = date_diff($tanggal_sekarang,$tanggal_penutupan);
            $selisih_tanggal_to = $diff_to->format("%R%a");

            $selisih_tanggal_to = intval($selisih_tanggal_to);
            $data = array(
            	"diff_date_from" => $selisih_tanggal_from,
            	"diff_date_to" => $selisih_tanggal_to,
            	"date_from" => $tanggal_pendaftaran_from,
            	"date_to" => $tanggal_pendaftaran_to
            );
            return $data;
		} catch (Exception $e) {
			echo $e;
		}
   	}

    //tanggal diff pendaftaran
   	public function date_diff() {
   		return true;
   	}
   	
   	//tanggal diff pendaftaran button
   	public function date_diff_disable() {
   		return false;
   	}

   	public function ang_rom($angka) {
	    $hsl = "";
	    if ($angka < 1 || $angka > 5000) { 
	        $hsl = "Batas Angka 1 s/d 5000";
	    } else {
	        while ($angka >= 1000) {
	            $hsl .= "M"; 
	            $angka -= 1000;
	        }
	    }
	    if ($angka >= 500) {
	        // statement di atas akan bernilai true / benar
	        // Jika var angka lebih dari sama dengan 500
	        if ($angka > 500) {
	            if ($angka >= 900) {
	                $hsl .= "CM";
	                $angka -= 900;
	            } else {
	                $hsl .= "D";
	                $angka-=500;
	            }
	        }
	    }
	    while ($angka>=100) {
	        if ($angka>=400) {
	            $hsl .= "CD";
	            $angka -= 400;
	        } else {
	            $angka -= 100;
	        }
	    }
	    if ($angka>=50) {
	        if ($angka>=90) {
	            $hsl .= "XC";
	            $angka -= 90;
	        } else {
	            $hsl .= "L";
	            $angka-=50;
	        }
	    }
	    while ($angka >= 10) {
	        if ($angka >= 40) {
	            $hsl .= "XL";
	            $angka -= 40;
	        } else {
	            $hsl .= "X";
	            $angka -= 10;
	        }
	    }
	    if ($angka >= 5) {
	        if ($angka == 9) {
	            $hsl .= "IX";
	            $angka-=9;
	        } else {
	            $hsl .= "V";
	            $angka -= 5;
	        }
	    }
	    while ($angka >= 1) {
	        if ($angka == 4) {
	            $hsl .= "IV"; 
	            $angka -= 4;
	        } else {
	            $hsl .= "I";
	            $angka -= 1;
	        }
	    }
	    return ($hsl);
	}
}