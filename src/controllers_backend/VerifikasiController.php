<?php
use \Interop\Container\ContainerInterface;
class VerifikasiController {

   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	public function verifikasi($request, $response, $args){
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

	public function verifikasi_kartu_ujian($request, $response, $args){
		try {
			$id_pendaftaran = $args['id_pendaftaran'];
			$sql = "select * from psb_kartu_ujian where id_pendaftaran = '$id_pendaftaran'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function count_jumlah_pendaftar($request, $response, $args){
		try {
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

            $sql = "select count(id_kartu) as jml_pendaftar from psb_kartu_ujian where gelombang='$gelombang' and id_session='$id_session'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $count_pendaftar = $sth->fetchObject();
            $jml_pendaftar = intval($count_pendaftar->jml_pendaftar);
            $batas_kuota = intval($config->kuota_pendaftaran);
            $sisa_kuota = $batas_kuota - $jml_pendaftar;
            $sisa_kuota = $sisa_kuota < 0 ? 0 : $sisa_kuota;
            if ($jml_pendaftar >= $batas_kuota) {
            	return $response->withJson(array(
	            		"status" => "lebih",
	            		"pendaftar" => $jml_pendaftar,
	            		"batas_kuota" => $batas_kuota,
	            		"sisa_kuota" => $sisa_kuota
            		)
            	);
            }else{
            	return $response->withJson(array(
	            		"status" => "kurang",
	            		"pendaftar" => $jml_pendaftar,
	            		"batas_kuota" => $batas_kuota,
	            		"sisa_kuota" => $sisa_kuota
            		)
            	);
            }

            
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function batal_verifikasi($request, $response, $args){
		try {
			$input = $request->getParsedBody();
             $id_pendaftaran = $input['id_pendaftaran'];
             $sql = "delete from psb_kartu_ujian where id_pendaftaran='$id_pendaftaran'";
             $sth = $this->db->prepare($sql);
             $sth->execute();
             $sql = "update psb_pendaftaran set status = 'UNVERIFIED' where id = '$id_pendaftaran'";
             $sth = $this->db->prepare($sql);
             $sth->execute();
             $sql = "delete from psb_hasil_tes where id_pendaftaran='$id_pendaftaran'";
             $sth = $this->db->prepare($sql);
             $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function verifikasi_generate_kartu_ujian($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$now = new \DateTime('now');
            $year = $now->format('y');
			$sql = "select 
				c.id_session,
				c.status_gelombang,
				c.max_bangku,
				a.tanggal_ujian
			 from psb_config c inner join psb_agenda a on c.status_gelombang = a.gelombang";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $gelombang = $config->status_gelombang;
            $id_session = $config->id_session;
            $max_no_bangku = '0'.$config->max_bangku;
            $tanggal_ujian = strftime("%d %B %Y",strtotime($config->tanggal_ujian));
            
            $jenjang = $input['jenjang'];
            $jenis = $input['jenis_kelamin'];
            $sql = "select 
                max(no_ruangan) as max_no_ruangan,
                max(no_bangku) as max_no_bangku 
                from psb_kartu_ujian where 
                gelombang = '$gelombang' and jenjang = '$jenjang' and jenis_kelamin = '$jenis' 
                and id_session = '$id_session' 
                group by no_ruangan order by max_no_ruangan desc limit 1";
            $sth = $this->db->prepare($sql);
			$sth->execute();
            $data = $sth->fetchAll();
            $no_ruangan = '';
            $no_bangku = '';
            if (count($data) === 0) {
            	$sql = "select 
                    max(no_ruangan) as max_no_ruangan
                    from psb_kartu_ujian where 
                    gelombang = '$gelombang' 
                    and id_session = '$id_session' 
                    group by no_ruangan order by max_no_ruangan desc limit 1";
                $sth = $this->db->prepare($sql);
                $sth->execute();
                $data = $sth->fetchAll();
                if (count($data) === 0) {
                    $no_ruangan = '01';
                }else{
                    $no_ruangan = str_pad($data[0]['max_no_ruangan']+1, 2, "0", STR_PAD_LEFT);
                }
                $no_bangku = '001';
            }else{
                if ($data[0]['max_no_bangku'] === $max_no_bangku) {
                	$sql = "select 
                        max(no_ruangan) as max_no_ruangan
                        from psb_kartu_ujian where 
                        gelombang = '$gelombang' and id_session = '$id_session' 
                        group by no_ruangan order by max_no_ruangan desc limit 1";
                    $sth = $this->db->prepare($sql);
                    $sth->execute();
                    $data = $sth->fetchAll();
                    $no_ruangan = str_pad($data[0]['max_no_ruangan']+1, 2, "0", STR_PAD_LEFT);
                    $no_bangku = '001';
                }else{
                    $no_bangku = str_pad($data[0]['max_no_bangku']+1, 3, "0", STR_PAD_LEFT);
                    $no_ruangan = $data[0]['max_no_ruangan'];
                }
            }
            $jenjang = $input['jenjang'] === 'SMP' ? '1' : '2';
            $jenis = $input['jenis_kelamin'] === 'LAKI - LAKI' ? '01' : '02';
            $no_kartu = $year.$jenjang.$no_ruangan.$jenis.$gelombang.$no_bangku;

            $sql = "insert into psb_kartu_ujian 
             (
                id_pendaftaran,
                no_kartu,
                no_ruangan,
                no_bangku,
                jenis_kelamin,
                jenjang,
                gelombang,
                tanggal,
                id_session,
                created_at
             ) 
             values 
             (
                :id_pendaftaran,
                '$no_kartu',
                '$no_ruangan',
                '$no_bangku',
                :jenis_kelamin,
                :jenjang,
                '$gelombang',
                '$tanggal_ujian',
                '$id_session',
                now()
             )";
            $sth = $this->db->prepare($sql);
            $sth->bindParam("id_pendaftaran", $input['id_pendaftaran']);             
            $sth->bindParam("jenis_kelamin", $input['jenis_kelamin']);             
            $sth->bindParam("jenjang", $input['jenjang']);             
            $sth->execute();

            $sth = $this->db->prepare("update psb_pendaftaran set status = 'VERIFIED' where id = :id_pendaftaran");
            $sth->bindParam("id_pendaftaran", $input['id_pendaftaran']);             
            $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	

}