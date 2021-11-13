<?php
use \Interop\Container\ContainerInterface;
class BiayaController {

   	protected $db;
   	protected $view;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->view = $container->view;
   	}

   	public function index($request, $response, $args){
   		try {
   			   $common  = new common();
               /* if ($common->date_diff()) {
                	$data_common = $common->countdown($this->db);
                	if ($data_common['diff_date_from'] > 1) {
    	            	return $this->view->render($response, "/comingsoon.twig", array(
    				    	"title" => "Pendaftaran Santri Asromo Online",
    				    	"tanggal_pembukaan" => $data_common['date_from']
    				    ));
    	            }
    	            if ($data_common['diff_date_to'] <= 1) {
    	            	return $this->view->render($response, "/penutupan.twig", array(
    				    	"title" => "Pendaftaran Santri Asromo Online",
    				    	"tanggal_penutupan" => $data_common['date_to']
    				    ));
    	            }
                }*/
               
               $sql = "select * from psb_biaya_pendidikan";
               $sth = $this->db->prepare($sql);
               $sth->execute();
               $datas = $sth->fetchAll();

               $sql = "select sum(harga_smp) as total_harga_smp, sum(harga_sma) as total_harga_sma from psb_biaya_pendidikan";
               $sth = $this->db->prepare($sql);
               $sth->execute();
               $total_harga = $sth->fetchObject();

               $sql = "select * from psb_biaya_pendidikan_keterangan";
               $sth = $this->db->prepare($sql);
               $sth->execute();
               $ket = $sth->fetchObject();
   			return $this->view->render($response, "/biaya.twig", array(
   		    	"title" => "Biaya Pendidikan - Santri Asromo Online",
   		    	"active" => "biaya",
   		    	"datas" => $datas,
   		    	"ket" => $ket,
   		    	"total_harga" => $total_harga
   		    ));

   		} catch (Exception $e) {
   			echo $e;
   		}
   	}

	
}