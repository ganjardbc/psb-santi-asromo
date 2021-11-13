<?php
use \Interop\Container\ContainerInterface;
class SeleksiController {

   	protected $db;
   	protected $dbSiakad;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->dbSiakad = $container->dbSiakad;
       	$this->container = $container;
   	}

   	public function seleksi($request, $response, $args){
		try {
			$limit = 50;
			$page = ($args['page'] - 1) * $limit;
            $sth = $this->db->prepare("select u.id_kartu,t.id_seleksi,u.no_kartu, p.*,ifnull(t.keterangan, 'BELUM') as keterangan from psb_kartu_ujian u inner join psb_pendaftaran p on p.id = u.id_pendaftaran
left join psb_hasil_tes t on t.no_kartu = u.no_kartu where u.id_session = (select id_session from psb_config limit 1) limit $limit offset $page");
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function search($request, $response, $args){
		try {
			$limit = 50;
			$q = $request->getParam('q');
            $sth = $this->db->prepare("select u.no_kartu, p.*,ifnull(t.keterangan, 'BELUM') as keterangan from psb_kartu_ujian u inner join psb_pendaftaran p on p.id = u.id_pendaftaran
left join psb_hasil_tes t on t.no_kartu = u.no_kartu where (p.nama like '%$q%' or p.nik like '%$q%' or p.nisn like '%$q%' or p.no_hp like '%$q%' or u.no_kartu like '%$q%') and u.id_session = (select id_session from psb_config limit 1) limit $limit");
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function luluskan($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sql = "select id_session from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;
            $sth = $this->db->prepare("insert into psb_hasil_tes 
                (
                    no_kartu,
                    id_pendaftaran,
                    keterangan,
                    id_session
                ) values 
                (
                    :no_kartu,
                    :id_pendaftaran,
                    'LULUS',
                    '$id_session'
                )");
            $sth->bindParam("no_kartu", $input['no_kartu']);
            $sth->bindParam("id_pendaftaran", $input['id_pendaftaran']);
            $sth->execute();
            
            $sth_data = $this->db->prepare("SELECT 
                     ht.no_kartu,
                     ht.id_seleksi,
                     p.id,
                     p.id_session,
                     p.kode_pendaftaran,
                     p.nominal_transfer,
                     p.nama,
                     p.nama_panggilan,
                     p.jenjang,
                     p.jenis_kelamin,
                     p.tempat_lahir,
                     p.tanggal_lahir,
                     p.alamat,
                     prov.id_prov,
                     kab.id_kab,
                     kec.id_kec,
                     kel.id_kel,
                     p.kode_pos,
                     CONCAT('https://psbonlinesantiasromo.or.id/img/upload/',p.pas_foto) AS photo,
                     p.nisn,
                     p.nama_sekolah AS nama_asal_sekolah,
                     p.kategori_pendaftaran,
                     p.pindahan_kelas,
                     p.no_hp,
                     p.asal_sekolah,
                     p.nama_ibu,
                     p.nama_ayah,
                     p.pekerjaan_ibu,
                     p.pekerjaan_ayah,
                     p.pendidikan_ibu,
                     p.pendidikan_ayah,
                     p.nama_wali,
                     p.nik,
                     p.alamat_ortu,
                     prov_ortu.id_prov AS id_prov_ortu,
                     kab_ortu.id_kab AS id_kab_ortu,
                     kec_ortu.id_kec AS id_kec_ortu,
                     kel_ortu.id_kel AS id_kel_ortu
                     FROM psb_hasil_tes ht 
                     INNER JOIN psb_pendaftaran p ON ht.id_pendaftaran = p.id
                     LEFT OUTER JOIN psb_provinsi prov ON p.provinsi = prov.nama
                     LEFT OUTER JOIN psb_kabupaten kab ON p.kabupaten = kab.nama AND kab.id_prov = prov.id_prov
                     LEFT OUTER JOIN psb_kecamatan kec ON p.kecamatan = kec.nama AND kec.id_kab = kab.id_kab
                     LEFT OUTER JOIN psb_kelurahan kel ON p.kelurahan = kel.nama AND kel.id_kec = kec.id_kec
                     LEFT OUTER JOIN psb_provinsi prov_ortu ON p.provinsi_ortu = prov_ortu.nama
                     LEFT OUTER JOIN psb_kabupaten kab_ortu ON p.kabupaten_ortu = kab_ortu.nama AND kab_ortu.id_prov = prov_ortu.id_prov
                     LEFT OUTER JOIN psb_kecamatan kec_ortu ON p.kecamatan_ortu = kec_ortu.nama AND kec_ortu.id_kab = kab_ortu.id_kab
                     LEFT OUTER JOIN psb_kelurahan kel_ortu ON p.kelurahan_ortu = kel_ortu.nama AND kel_ortu.id_kec = kec_ortu.id_kec
                    WHERE ht.id_session = ".$id_session." AND p.id = ".$input['id_pendaftaran']);
            $sth_data->execute();
            $data = $sth_data->fetchAll();
            if(count($data) != 0){
                $data_input = $data[0];
                $sth_kasja = $this->dbSiakad->prepare("insert into calon_siswa 
                    (
                        id_siswa_psb,
                    	id_session,
                    	siswa_status,
                    	pindahan_kelas,
                    	nisn,
                    	kode_pendaftaran,
                    	nominal_transfer,
                    	nama,
                    	nama_panggilan,
                    	jenjang,
                    	jenis_kelamin,
                    	tempat_lahir,
                    	tanggal_lahir,
                    	alamat,
                    	id_prov,
                    	id_kab,
                    	id_kec,
                    	id_kel,
                    	kode_pos,
                    	photo,
                    	nama_asal_sekolah,
                    	no_hp,
                    	asal_sekolah,
                    	nama_ibu,
                    	nama_ayah,
                    	pendidikan_ayah,
                    	pendidikan_ibu,
                    	pekerjaan_ayah,
                    	pekerjaan_ibu,
                    	nama_wali,
                    	nik,
                    	alamat_ortu,
                    	id_prov_ortu,
                    	id_kab_ortu,
                    	id_kec_ortu,
                    	id_kel_ortu,
                    	no_kartu,
                    	id_departemen,
                    	status,
                    	created_at,
                    	updated_at

                    ) values 
                    (
                        :id_siswa_psb,
                    	:id_session,
                    	:siswa_status,
                    	:pindahan_kelas,
                    	:nisn,
                    	:kode_pendaftaran,
                    	:nominal_transfer,
                    	:nama,
                    	:nama_panggilan,
                    	:jenjang,
                    	:jenis_kelamin,
                    	:tempat_lahir,
                    	:tanggal_lahir,
                    	:alamat,
                    	:id_prov,
                    	:id_kab,
                    	:id_kec,
                    	:id_kel,
                    	:kode_pos,
                    	:photo,
                    	:nama_asal_sekolah,
                    	:no_hp,
                    	:asal_sekolah,
                    	:nama_ibu,
                    	:nama_ayah,
                    	:pendidikan_ayah,
                    	:pendidikan_ibu,
                    	:pekerjaan_ayah,
                    	:pekerjaan_ibu,
                    	:nama_wali,
                    	:nik,
                    	:alamat_ortu,
                    	:id_prov_ortu,
                    	:id_kab_ortu,
                    	:id_kec_ortu,
                    	:id_kel_ortu,
                    	:no_kartu,
                    	:id_departemen,
                    	1,
                    	now(),
                    	now()
                    )");
                $sth_kasja->bindParam("id_siswa_psb", $data_input['id']);
                $sth_kasja->bindParam("id_session", $data_input['id_session']);
                $sth_kasja->bindParam("siswa_status", $data_input['kategori_pendaftaran']);
                $sth_kasja->bindParam("pindahan_kelas", $data_input['pindahan_kelas']);
                $sth_kasja->bindParam("nisn", $data_input['nisn']);
                $sth_kasja->bindParam("kode_pendaftaran", $data_input['kode_pendaftaran']);
                $sth_kasja->bindParam("nominal_transfer", $data_input['nominal_transfer']);
                $sth_kasja->bindParam("nama", $data_input['nama']);
                $sth_kasja->bindParam("nama_panggilan", $data_input['nama_panggilan']);
                
                $sth_kasja->bindParam("jenjang", $data_input['jenjang']);
                $sth_kasja->bindParam("jenis_kelamin", $data_input['jenis_kelamin']);
                $sth_kasja->bindParam("tempat_lahir", $data_input['tempat_lahir']);
                $sth_kasja->bindParam("tanggal_lahir", $data_input['tanggal_lahir']);
                $sth_kasja->bindParam("alamat", $data_input['alamat']);
                $sth_kasja->bindParam("id_prov", $data_input['id_prov']);
                $sth_kasja->bindParam("id_kab", $data_input['id_kab']);
                $sth_kasja->bindParam("id_kec", $data_input['id_kec']);
                $sth_kasja->bindParam("id_kel", $data_input['id_kel']);
                $sth_kasja->bindParam("kode_pos", $data_input['kode_pos']);
                $sth_kasja->bindParam("photo", $data_input['photo']);
                $sth_kasja->bindParam("nama_asal_sekolah", $data_input['nama_asal_sekolah']);
                $sth_kasja->bindParam("no_hp", $data_input['no_hp']);
                $sth_kasja->bindParam("asal_sekolah", $data_input['asal_sekolah']);
                
                $sth_kasja->bindParam("nama_ibu", $data_input['nama_ibu']);
                $sth_kasja->bindParam("nama_ayah", $data_input['nama_ayah']);
                $sth_kasja->bindParam("pekerjaan_ayah", $data_input['pekerjaan_ayah']);
                $sth_kasja->bindParam("pekerjaan_ibu", $data_input['pekerjaan_ibu']);
                $sth_kasja->bindParam("pendidikan_ayah", $data_input['pendidikan_ayah']);
                $sth_kasja->bindParam("pendidikan_ibu", $data_input['pendidikan_ibu']);
                $sth_kasja->bindParam("nama_wali", $data_input['nama_wali']);
                $sth_kasja->bindParam("nik", $data_input['nik']);
                $sth_kasja->bindParam("alamat_ortu", $data_input['alamat_ortu']);
                $sth_kasja->bindParam("id_prov_ortu", $data_input['id_prov_ortu']);
                $sth_kasja->bindParam("id_kab_ortu", $data_input['id_kab_ortu']);
                $sth_kasja->bindParam("id_kec_ortu", $data_input['id_kec_ortu']);
                $sth_kasja->bindParam("id_kel_ortu", $data_input['id_kel_ortu']);
                $sth_kasja->bindParam("no_kartu", $data_input['no_kartu']);
                
                $departemen = "";
                if($data_input['jenjang'] == "SMP"){
                    $departemen = "e690939a-ee1f-48b2-87b6-dadf720f0ddd";
                }else if($data_input['jenjang'] == "SMA"){
                    $departemen = "0512d953-32b8-4a60-9aa1-1d713a8a68bd";
                };
                $sth_kasja->bindParam("id_departemen",$departemen);
                $sth_kasja->execute();
            }
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function batalkan($request, $response, $args){
		try {
            $input = $request->getParsedBody();
            $sth = $this->db->prepare("delete from psb_hasil_tes where id_pendaftaran = :id_pendaftaran and id_session=(select id_session from psb_config limit 1)");
            $sth->bindParam("id_pendaftaran", $input['id_pendaftaran']);
            $sth->execute();
            
            $sth_kasja = $this->dbSiakad->prepare("delete from calon_siswa where id_siswa_psb = :id_siswa_psb");
            $sth_kasja->bindParam("id_siswa_psb", $input['id_pendaftaran']);
            $sth_kasja->execute();
        } catch (Exception $e) {
            return $response->write($e)->withStatus(400);
        }
	}

}