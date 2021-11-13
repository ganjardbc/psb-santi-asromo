<?php
use \Interop\Container\ContainerInterface;
class AgendaBackController {


   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	
   	public function agenda($request, $response, $args){
		try {
            $sth = $this->db->prepare("select * from psb_agenda");
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function update($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("update psb_agenda set 
				tanggal_pendaftaran_from =:tanggal_pendaftaran_from,
				tanggal_pendaftaran_to =:tanggal_pendaftaran_to,
				tanggal_ujian =:tanggal_ujian,
				tanggal_pengumuman =:tanggal_pengumuman,
				tanggal_daftar_ulang_from =:tanggal_daftar_ulang_from,
				tanggal_daftar_ulang_to =:tanggal_daftar_ulang_to,
				kuota_pendaftaran =:kuota_pendaftaran 
				where id_agenda =:id_agenda");
            $sth->bindParam("tanggal_pendaftaran_from", $input['tanggal_pendaftaran_from']);
            $sth->bindParam("tanggal_pendaftaran_to", $input['tanggal_pendaftaran_to']);
            $sth->bindParam("tanggal_ujian", $input['tanggal_ujian']);
            $sth->bindParam("tanggal_pengumuman", $input['tanggal_pengumuman']);
            $sth->bindParam("tanggal_daftar_ulang_from", $input['tanggal_daftar_ulang_from']);
            $sth->bindParam("tanggal_daftar_ulang_to", $input['tanggal_daftar_ulang_to']);
            $sth->bindParam("kuota_pendaftaran", $input['kuota_pendaftaran']);
            $sth->bindParam("id_agenda", $input['id_agenda']);
            $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}
}