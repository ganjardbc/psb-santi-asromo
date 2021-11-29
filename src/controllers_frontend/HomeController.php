<?php
use \Interop\Container\ContainerInterface;
class HomeController {

   	protected $db;
   	protected $view;

   	public function __construct(ContainerInterface $container) {
       	$this->db = $container->db;
       	$this->view = $container->view;
   	}

   	public function index($request, $response, $args){
		try {
			$common  = new common();
            /*if ($common->date_diff()) {
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
            
			// return $this->view->render($response, "/home.twig", array(
		    // 	"title" => "Pendaftaran Santri Asromo Online",
		    // 	"active" => "home"
		    // ));
			$benefit = [
				['image' => 'img/persyaratan.png', 'title' => 'Ruhiyah (Bener)', 'desc' => [
					['desc' => 'Beraqidah Ahlusunnah wal jamaah'],
					['desc' => 'Keimanan yang kokoh'],
					['desc' => 'Menjalankan Ibadah sesuai dengan syariat'],
				]],
				['image' => 'img/panduan2.png', 'title' => 'Aqliyah (Pinter)', 'desc' => [
					['desc' => 'Memiliki keluasan Ilmu Pengetahuan'],
					['desc' => 'Memiliki kepakaan sosial dan sikap tasammuh'],
				]],
				['image' => 'img/reg.png', 'title' => 'Jasadiyah (Perigel)', 'desc' => [
					['desc' => 'Memiliki Kemuliaan Akhlaq'],
					['desc' => 'Memiliki Kemampuan Leadership Entreperunership dan Mandiri'],
				]],
			];
			return $this->view->render($response, "/home.twig", array(
		    	"title" => "Pendaftaran Santri Asromo Online",
		    	"active" => "home",
				"benefit" => $benefit,
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	
}