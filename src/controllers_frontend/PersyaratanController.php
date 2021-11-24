<?php
use \Interop\Container\ContainerInterface;
class PersyaratanController {

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
            
			$rupiah = function($angka){
				$hasil_rupiah = number_format($angka,0,',','.');
				return $hasil_rupiah;
			};
			$sql = "select * from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
			return $this->view->render($response, "/persyaratan.twig", array(
		    	"title" => "Persyaratan Pendaftaran Santri Asromo Online",
		    	"biaya" => $rupiah($config->biaya_pendaftaran),
		    	"active" => "persyaratan"
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}
}