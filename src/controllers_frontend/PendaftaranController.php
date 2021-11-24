<?php
use \Interop\Container\ContainerInterface;
class PendaftaranController {

   	protected $db;
   	protected $view;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->view = $container->view;
   	}

   	public function index($request, $response, $args){
		try {
            $common  = new common();
            // if ($common->date_diff()) {
            // 	$data_common = $common->countdown($this->db);
            // 	if ($data_common['diff_date_from'] > 1) {
	        //     	return $this->view->render($response, "/comingsoon.twig", array(
			// 	    	"title" => "Pendaftaran Santri Asromo Online",
			// 	    	"tanggal_pembukaan" => $data_common['date_from']
			// 	    ));
	        //     }
	        //     if ($data_common['diff_date_to'] <= 1) {
	        //     	return $this->view->render($response, "/penutupan.twig", array(
			// 	    	"title" => "Pendaftaran Santri Asromo Online",
			// 	    	"tanggal_penutupan" => $data_common['date_to']
			// 	    ));
	        //     }
            // }
           
            
            $status_button = "0";
            if ($common->date_diff_disable()) {
                $status_button = "0";
            }else{
                $status_button = "1";
            }
            
            $sql = "select 
                c.id_session,
                c.status_gelombang,
                c.max_bangku,
                a.tanggal_ujian,
                a.kuota_pendaftaran 
             from psb_config c inner join psb_agenda a on c.status_gelombang = a.gelombang";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;
            $gelombang = $config->status_gelombang;

            $sql = "select tanggal_pendaftaran_from,tanggal_pendaftaran_to from psb_agenda where gelombang = 2";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $gelombang_two = $sth->fetchObject();

            $sql = "select count(no_kartu) as jml_pendaftar from psb_kartu_ujian where gelombang='$gelombang' and id_session='$id_session'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $count_pendaftar = $sth->fetchObject();
            $jml_pendaftar = intval($count_pendaftar->jml_pendaftar);
            $batas_kuota = intval($config->kuota_pendaftaran);
            $status_kuota = "kurang";
            if ($jml_pendaftar >= $batas_kuota) {
                $status_kuota = "lebih";
            };
			return $this->view->render($response, "/pendaftaran.twig", array(
		    	"title" => "Pendaftaran Santri Asromo Online",
		    	"active" => "pendaftaran",
                "status_kuota" => $status_kuota,
                "gelombang_two" => $gelombang_two,
                "status_button" => $status_button
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function sukses($request, $response, $args){
		try {
			$kode_pendaftaran = $args['kode_pendaftaran'];
			$nama = $request->getParam('name');
			$sql = "select id,nominal_transfer from psb_pendaftaran where kode_pendaftaran='$kode_pendaftaran' and nama = '$nama'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $data = $sth->fetchObject();
            $rupiah = function($angka){
				$hasil_rupiah = number_format($angka,0,',','.');
				return $hasil_rupiah;
			};
            if (!$data) {
            	return $response->withRedirect($request->getUri()->getBasePath().'/pendaftaran');
            }

			return $this->view->render($response, "/sukses_daftar.twig", array(
		    	"title" => "Sukses - Pendaftaran Santri Asromo Online",
		    	"nominal_transfer" => $rupiah($data->nominal_transfer)
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

    public function upload_foto($request, $response, $args){
        try {
            $input = $request->getParsedBody();
            $name_photo = $input['name'];
            $target_dir = "img/upload/";
            $ext = strtolower(substr(strrchr(basename($_FILES["file"]["name"]), '.'), 1));
            $supported_image = array('jpg','jpeg','png');
            if(in_array($ext, $supported_image) === false) {
                return $response->withStatus(400)->withJson([
                    "status" => 400,
                    "message" => "File not supported. accept file png, jpg, jpeg"
                ]);
            }
            $target_file = $target_dir.$name_photo.'.'.$ext;
            $filename = $name_photo.'.'.$ext;
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            return $response->write($filename);
        } catch (Exception $e) {
            echo $e;
        }
    }

	public function submit($request, $response, $args){
		try {
			$input = $request->getParsedBody();
            $nisn = addslashes($input["nisn"]);
            $nik = addslashes($input["nik"]);
            $jenjang = addslashes($input["jenjang"]);
            $sql = "select count(nik) as cnt from psb_pendaftaran where nik = '$nik' and jenjang = '$jenjang'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $check_nik = $sth->fetchObject();
            if ($check_nik->cnt != '0') {
                return $response->write("<p>NIK ".$nik." sudah terdaftar. maaf, tidak bisa melakukan pendaftaran. </p>");
            }

			$sql = "select 
                c.id_session,
                c.status_gelombang,
                c.max_bangku,
                a.tanggal_ujian,
                c.biaya_pendaftaran,
                a.kuota_pendaftaran  
             from psb_config c inner join psb_agenda a on c.status_gelombang = a.gelombang";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();

			$now_date = new DateTime();
        	
			// $name_photo = preg_replace('/\s+/', '_', $input["nama"])."_".$now_date->getTimestamp();
			// $target_dir = "img/upload/";
		 //    $ext = strtolower(substr(strrchr(basename($_FILES["file"]["name"]), '.'), 1));
		 //    $target_file = $target_dir.$name_photo.'.'.$ext;
		 //    $filename = $name_photo.'.'.$ext;
		 //    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		    
		    //form input
		    $kode_pendaftaran = "PSB-SA-".$now_date->getTimestamp();
		    $jenis_pendaftaran = "PSB_ONLINE";
		    $nama = addslashes($input["nama"]);
		    $nama_panggilan = addslashes($input["nama_panggilan"]);
		    
		    $jenis_kelamin = addslashes($input["jenis_kelamin"]);
		    $tempat_lahir = addslashes($input["tempat_lahir"]);
		    $tanggal_lahir = $input["hari_lahir"]."/".$input["bulan_lahir"]."/".$input["tahun_lahir"];
		    $alamat = addslashes($input["alamat"]);
		    $provinsi = addslashes($input["provinsi"]);
		    $kabupaten = addslashes($input["kabupaten"]);
		    $kecamatan = addslashes($input["kecamatan"]);
		    $kelurahan = addslashes($input["kelurahan"]);
		    $kode_pos = addslashes($input["kode_pos"]);
		    $no_hp = addslashes($input["no_hp"]);
		    $pas_foto = addslashes($input['filename']);
		    
		    $nama_sekolah = addslashes($input["nama_sekolah"]);
		    $nama_ibu = addslashes($input["nama_ibu"]);
		    $nama_ayah = addslashes($input["nama_ayah"]);
		    $nama_wali = addslashes($input["nama_wali"]);
		    $pendidikan_ibu = addslashes($input["pendidikan_ibu"]);
		    $pendidikan_ayah = addslashes($input["pendidikan_ayah"]);
		    $pekerjaan_ibu = addslashes($input["pekerjaan_ibu"]);
		    $pekerjaan_ayah = addslashes($input["pekerjaan_ayah"]);
		    $status = "UNVERIFIED";
		    $kategori_pendaftaran = addslashes($input["kategori_pendaftaran"]);
		    $alamat_ortu = $input["alamat_ortu"];
            // $pindahan_kelas = isset($input["pindahan_kelas"]) ? $input["pindahan_kelas"] : 'non';
            $pindahan_kelas_angka = $jenjang == "SMP" ? "8" : "11";
		    $pindahan_kelas = $kategori_pendaftaran == "SANTRI PINDAHAN" ? $pindahan_kelas_angka : 'non';
		    $asal_sekolah = addslashes($input["asal_sekolah"]);
		    $provinsi_ortu = addslashes($input["provinsi_ortu"]);
		    $kabupaten_ortu = addslashes($input["kabupaten_ortu"]);
		    $kecamatan_ortu = addslashes($input["kecamatan_ortu"]);
		    $kelurahan_ortu = addslashes($input["kelurahan_ortu"]);
		    $id_session = $config->id_session;
		    $rand = substr($now_date->getTimestamp(), -3);
		    $nominal_transfer = substr($config->biaya_pendaftaran,0,3).$rand;

		    $sql = "insert into psb_pendaftaran 
             (
                kode_pendaftaran,
                jenis_pendaftaran,
                nama,
                nama_panggilan,
                jenjang,
                jenis_kelamin,
                tempat_lahir,
                tanggal_lahir,
                alamat,
                kabupaten,
                provinsi,
                kecamatan,
                kelurahan,
                kode_pos,
                no_hp,
                pas_foto,
                nisn,
                nama_sekolah,
                nama_ibu,
                nama_ayah,
                nama_wali,
                pendidikan_ibu,
                pendidikan_ayah,
                pekerjaan_ibu,
                pekerjaan_ayah,
                nominal_transfer,
                status,
                kategori_pendaftaran,
                alamat_ortu,
                pindahan_kelas,
                nik,
                asal_sekolah,
                created_at,
                kabupaten_ortu,
                provinsi_ortu,
                kecamatan_ortu,
                kelurahan_ortu,
                id_session
             ) 
             values 
             (
                '$kode_pendaftaran',
                '$jenis_pendaftaran',
                '$nama',
                '$nama_panggilan',
                '$jenjang',
                '$jenis_kelamin',
                '$tempat_lahir',
                '$tanggal_lahir',
                '$alamat',
                '$kabupaten',
                '$provinsi',
                '$kecamatan',
                '$kelurahan',
                '$kode_pos',
                '$no_hp',
                '$pas_foto',
                '$nisn',
                '$nama_sekolah',
                '$nama_ibu',
                '$nama_ayah',
                '$nama_wali',
                '$pendidikan_ibu',
                '$pendidikan_ayah',
                '$pekerjaan_ibu',
                '$pekerjaan_ayah',
                '$nominal_transfer',
                '$status',
                '$kategori_pendaftaran',
                '$alamat_ortu',
                '$pindahan_kelas',
                '$nik',
                '$asal_sekolah',
                now(),
                '$kabupaten_ortu',
                '$provinsi_ortu',
                '$kecamatan_ortu',
                '$kelurahan_ortu',
                '$id_session'
             )";
            $sth = $this->db->prepare($sql);
            $sth->execute();
			return $response->withRedirect($request->getUri()->getBasePath().'/sukses-daftar/'.$kode_pendaftaran.'?name='.$nama);
		} catch (Exception $e) {
			echo $e;
		}
	}
}