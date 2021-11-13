<?php
use \Interop\Container\ContainerInterface;
class DatapendaftaranController {

   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	public function datapendaftaran($request, $response, $args){
		try {
			$slug = "select id_session from psb_config limit 1";
			$limit = 50;
			$page = ($args['page'] - 1) * $limit;
			$sql = "select 
				id,
				nama,
				status,
				nominal_transfer,
				nik,
				nisn,
				pas_foto,
				jenis_kelamin,
				jenjang,
				no_hp,
				created_at 
			from psb_pendaftaran where id_session=($slug) order by created_at desc limit $limit offset $page";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function datapendaftaran_id($request, $response, $args){
		try {
			$sql = "select 
                k.no_kartu,
                k.no_ruangan,
                k.no_bangku,
                k.gelombang,
                k.tanggal,
                p.* 
                from psb_pendaftaran p 
                left join psb_kartu_ujian k on k.id_pendaftaran = p.id where p.id = :id_pendaftaran";
            $sth = $this->db->prepare($sql);
            $sth->bindParam("id_pendaftaran", $args['id_pendaftaran']);
            $sth->execute();
            $data = $sth->fetchObject();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function search($request, $response, $args){
		try {
			$slug = "select id_session from psb_config limit 1";
			$limit = 50;
			$q = $request->getParam('q');
			$sql = "select 
				id,
				nama,
				status,
				nominal_transfer,
				nik,
				nisn,
				pas_foto,
				jenis_kelamin,
				jenjang,
				no_hp,
				created_at 
			from psb_pendaftaran where id_session=($slug) and (nama like '%$q%' or nik like '%$q%' or nisn like '%$q%' or no_hp like '%$q%' or nominal_transfer like '%$q%') order by created_at desc limit $limit";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function hapus_pendaftaran($request, $response, $args){
		try {
			$input = $request->getParsedBody();
            $id_pendaftaran = $input['id_pendaftaran'];
            $sql = "delete from psb_kartu_ujian where id_pendaftaran='$id_pendaftaran'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $sql = "delete from psb_pendaftaran where id='$id_pendaftaran'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $sql = "delete from psb_hasil_tes where id_pendaftaran='$id_pendaftaran'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            // return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function upload_image($request, $response, $args){
		try {
            $input = $request->getParsedBody();
            $target_dir = "img/upload/";
		    $ext = strtolower(substr(strrchr(basename($_FILES["file"]["name"]), '.'), 1));
		    $target_file = $target_dir.$_POST['name'].'.'.$ext;
		    $filename = $_POST['name'].'.'.$ext;
		    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		    $image = array(
		        "image" => $filename
		    );
            return $response->withJson($image);
        } catch (Exception $e) {
            return $response->write($e)->withStatus(400);
        }
	}



	public function update_pendaftaran($request, $response, $args){
		try {
			$input = $request->getParsedBody();
            $sql = "update psb_pendaftaran set  
                nama=:nama,
                nama_panggilan=:nama_panggilan,
                jenjang=:jenjang,
                jenis_kelamin=:jenis_kelamin,
                tempat_lahir=:tempat_lahir,
                tanggal_lahir=:tanggal_lahir,
                alamat=:alamat,
                kabupaten=:kabupaten,
                provinsi=:provinsi,
                kecamatan=:kecamatan,
                kelurahan=:kelurahan,
                kode_pos=:kode_pos,
                no_hp=:no_hp,
                pas_foto=:pas_foto,
                nisn=:nisn,
                nama_sekolah=:nama_sekolah,
                nama_ibu=:nama_ibu,
                nama_ayah=:nama_ayah,
                nama_wali=:nama_wali,
                pendidikan_ibu=:pendidikan_ibu,
                pendidikan_ayah=:pendidikan_ayah,
                pekerjaan_ibu=:pekerjaan_ibu,
                pekerjaan_ayah=:pekerjaan_ayah,
                kategori_pendaftaran=:kategori_pendaftaran,
                alamat_ortu=:alamat_ortu,
                pindahan_kelas=:pindahan_kelas,
                nik=:nik,
                asal_sekolah=:asal_sekolah,
                created_at=now(),
                kabupaten_ortu=:kabupaten_ortu,
                provinsi_ortu=:provinsi_ortu,
                kecamatan_ortu=:kecamatan_ortu,
                kelurahan_ortu=:kelurahan_ortu 
                where id=:id";
             $sth = $this->db->prepare($sql);
             $sth->bindParam("id", $input['id']);
             $sth->bindParam("nama", $input['nama']);
             $sth->bindParam("nama_panggilan", $input['nama_panggilan']);
             $sth->bindParam("jenjang", $input['jenjang']);
             $sth->bindParam("jenis_kelamin", $input['jenis_kelamin']);
             $sth->bindParam("tempat_lahir", $input['tempat_lahir']);
             $sth->bindParam("tanggal_lahir", $input['tanggal_lahir']);
             $sth->bindParam("alamat", $input['alamat']);
             $sth->bindParam("kabupaten", $input['kabupaten']);
             $sth->bindParam("provinsi", $input['provinsi']);
             $sth->bindParam("kode_pos", $input['kode_pos']);
             $sth->bindParam("no_hp", $input['no_hp']);
             $sth->bindParam("pas_foto", $input['pas_foto']);
             $sth->bindParam("nisn", $input['nisn']);
             $sth->bindParam("nama_sekolah", $input['nama_sekolah']);
             $sth->bindParam("nama_ibu", $input['nama_ibu']);
             $sth->bindParam("nama_ayah", $input['nama_ayah']);
             $sth->bindParam("nama_wali", $input['nama_wali']);
             $sth->bindParam("pendidikan_ibu", $input['pendidikan_ibu']);
             $sth->bindParam("pendidikan_ayah", $input['pendidikan_ayah']);
             $sth->bindParam("pekerjaan_ibu", $input['pekerjaan_ibu']);
             $sth->bindParam("pekerjaan_ayah", $input['pekerjaan_ayah']);
             $sth->bindParam("kecamatan", $input['kecamatan']);
             $sth->bindParam("kelurahan", $input['kelurahan']);
             $sth->bindParam("kategori_pendaftaran", $input['kategori_pendaftaran']);
             $sth->bindParam("alamat_ortu", $input['alamat_ortu']);
             $sth->bindParam("pindahan_kelas", $input['pindahan_kelas']);
             $sth->bindParam("nik", $input['nik']);
             $sth->bindParam("asal_sekolah", $input['asal_sekolah']);
             $sth->bindParam("kecamatan_ortu", $input['kecamatan_ortu']);
             $sth->bindParam("kelurahan_ortu", $input['kelurahan_ortu']);
             $sth->bindParam("kabupaten_ortu", $input['kabupaten_ortu']);
             $sth->bindParam("provinsi_ortu", $input['provinsi_ortu']);
             $sth->execute();

            if ($input['status_kartu_ujian'] === 'yes') {
	             $sql = "update psb_kartu_ujian set  
	                no_kartu=:no_kartu,
	                no_ruangan=:no_ruangan,
	                no_bangku=:no_bangku,
	                gelombang=:gelombang,
	                jenjang=:jenjang,
	                jenis_kelamin=:jenis_kelamin,
	                tanggal=:tanggal
	                where id_pendaftaran=:id_pendaftaran";
	             $sth = $this->db->prepare($sql);
	             $sth->bindParam("id_pendaftaran", $input['id']);
	             $sth->bindParam("no_kartu", $input['no_kartu']);
	             $sth->bindParam("no_ruangan", $input['no_ruangan']);
	             $sth->bindParam("no_bangku", $input['no_bangku']);
	             $sth->bindParam("gelombang", $input['gelombang']);
	             $sth->bindParam("jenjang", $input['jenjang']);
	             $sth->bindParam("tanggal", $input['tanggal']);
	             $sth->bindParam("jenis_kelamin", $input['jenis_kelamin']);
	             $sth->execute();
            }
            return $response->withJson($input);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	

}