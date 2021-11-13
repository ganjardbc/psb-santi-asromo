<?php
use \Interop\Container\ContainerInterface;
class BiayaBackController {

   	protected $db;
   	protected $container;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->container = $container;
   	}

   	public function biaya_back($request, $response, $args){
		try {
            $sth = $this->db->prepare("select * from psb_biaya_pendidikan");
            $sth->execute();
            $data = $sth->fetchAll();
            return $response->withJson($data);
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function save($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("insert into psb_biaya_pendidikan 
            (
            	nama_biaya,
            	harga_smp,
            	harga_sma
            ) 
            values(
            	:nama_biaya,
            	:harga_smp,
            	:harga_sma
            )");
            $sth->bindParam("nama_biaya", $input['nama_biaya']);
            $sth->bindParam("harga_smp", $input['harga_smp']);
            $sth->bindParam("harga_sma", $input['harga_sma']);
            $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function update($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("update psb_biaya_pendidikan set 
				nama_biaya =:nama_biaya,
				harga_smp=:harga_smp,
				harga_sma=:harga_sma where id_biaya_pendidikan = :id_biaya_pendidikan");
           $sth->bindParam("nama_biaya", $input['nama_biaya']);
            $sth->bindParam("harga_smp", $input['harga_smp']);
            $sth->bindParam("harga_sma", $input['harga_sma']);
            $sth->bindParam("id_biaya_pendidikan", $input['id_biaya_pendidikan']);
            $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}

	public function delete($request, $response, $args){
		try {
			$input = $request->getParsedBody();
			$sth = $this->db->prepare("delete from psb_biaya_pendidikan where id_biaya_pendidikan=:id_biaya_pendidikan");
            $sth->bindParam("id_biaya_pendidikan", $input['id_biaya_pendidikan']);
            $sth->execute();
            return $response->withJson(array("status" => "success"));
		} catch (Exception $e) {
			return $response->write($e)->withStatus(400);
		}
	}


}