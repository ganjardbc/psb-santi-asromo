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
				['image' => 'img/persyaratan.png', 'title' => 'Konsultasi Jurusan Gratis', 'desc' => 'Sebanyak apapun konsultasi belajar dan jurusan tidak ada tambahan biaya.', 'route' => '/'],
				['image' => 'img/panduan2.png', 'title' => 'Pembelajaran Online', 'desc' => 'Fasilitas belajar online di aplikasi belajar NF dan video pembelajaran di portal SIP dan Aplikasi Skolla', 'route' => '/'],
				['image' => 'img/reg.png', 'title' => 'Peluang Beasiswa', 'desc' => 'Ditawarkan pada 100 siswa peringkat teratas yang memenuhi persyaratan', 'route' => '/'],
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