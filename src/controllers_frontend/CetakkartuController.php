<?php
use \Interop\Container\ContainerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
class CetakkartuController {

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
            
			$sql = "select id_session from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;

            $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
		    $limit = 50;
		    $skip = ($page - 1) * $limit;
		    $sql = "select count(p.id) as cnt from psb_kartu_ujian k 
                inner join psb_pendaftaran p 
                on k.id_pendaftaran = p.id where p.id_session='$id_session'";
			$sth = $this->db->prepare($sql);
		    $sth->execute();
		    $count_data = $sth->fetchObject();
		    $count = intval($count_data->cnt);

		    $sql = "select p.id as id_pendaftaran,p.nama,k.no_kartu,k.tanggal,k.no_ruangan,k.no_bangku,k.gelombang from psb_kartu_ujian k 
                inner join psb_pendaftaran p 
                on k.id_pendaftaran = p.id where p.id_session='$id_session' order by k.no_kartu limit ".$limit." offset ".$skip;
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $datas = $sth->fetchAll();
			return $this->view->render($response, "/cetak-kartu.twig", array(
		    	"title" => "Cetak Kartu Santri Asromo Online",
		    	"active" => "cetak-kartu",
		    	"datas" => $datas,
		    	"value" => "",
		    	"needed" => $count > $limit,
		    	"page" => $page,
		    	"skip" => $skip,
		    	"lastpage" => (ceil($count / $limit) == 0 ? 1 : ceil($count / $limit)),
		    	"limit" => $limit
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function index_search($request, $response, $args){
		try {
			$sql = "select id_session from psb_config";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $config = $sth->fetchObject();
            $id_session = $config->id_session;

            $limit     = 50;
		    $q = $request->getParam('q');
			if ($q=='') {
				return $response->withRedirect($request->getUri()->getBasePath().'/cetak-kartu');
			}

		    $sql = "select p.id as id_pendaftaran,p.nama,k.no_kartu,k.tanggal,k.no_ruangan,k.no_bangku,k.gelombang from psb_kartu_ujian k 
                inner join psb_pendaftaran p 
                on k.id_pendaftaran = p.id where p.id_session='$id_session' and (p.nama like '%$q%' or p.nik like '%$q%' or p.nisn like '%$q%') order by k.no_kartu limit ".$limit;
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $datas = $sth->fetchAll();
			return $this->view->render($response, "/cetak-kartu.twig", array(
		    	"title" => $q." Cetak kartu Santri Asromo Online",
		    	"active" => "cetak-kartu",
		    	"value" => $q,
		    	"datas" => $datas,
		    	"skip" => 0,
		    	"needed" => false,
		    	"page" => 1,
		    	"lastpage" => 1,
		    	"limit" => $limit
		    ));
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function cetak_kartu($request, $response, $args){
		try {
		    header("Content-type:application/pdf");
			$id = $args['id'];
			$no_kartu = $args['no_ujian'];
			$sql = "select p.id,p.nama,p.pas_foto,p.jenjang,k.no_kartu,k.tanggal,k.no_ruangan,k.no_bangku,k.gelombang from psb_kartu_ujian k 
                inner join psb_pendaftaran p 
                on k.id_pendaftaran = p.id where p.id = '$id' and no_kartu = '$no_kartu'";
            $sth = $this->db->prepare($sql);
            $sth->execute();
            $data = $sth->fetchObject();
            $common = new common();
            $gelombang = $common->ang_rom($data->gelombang);
            if(isset($_SERVER['HTTPS'])){
		        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		    }else{
		        $protocol = 'http';
		    }
            $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $request->getUri()->getBasePath();
			$dompdf = new Dompdf();
			$html = '<!DOCTYPE html>
<html>
<head>    
    <title></title>
</head>
<body style="font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif">
    <div style="border-color: black;
    border-style: solid;
    padding: 10px;
    border-width: 1px;">
        <table style="width:100%">
        <tr>
            <td style="width:25%">
                <img height="35" src="'.$base_url.'/img/logo2.png">
            </td>
            <td style="width:50%;text-align:center;align-self:center;">
                <b style="font-size:9px">
                    KARTU PESERTA SELEKSI UJIAN MASUK<br>PONDOK MUFIDAH SANTI ASROMO<br>GELOMBANG '.$gelombang.'
                </b>
            </td>
            
        </tr>
    </table>
    <hr style="background-color: black; height: 0.25px; border: 0;">
    <table style="width:100%">
        <tr>
            
            <td>
                <table class="table" style="font-size:8px;">
                    <tr>
                        <td>NO. PESERTA</td>
                        <td>:</td>
                        <td>'.$data->no_kartu.'</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top;">NAMA</td>
                        <td style="vertical-align:top;">:</td>
                        <td>'.$data->nama.'</td>
                    </tr>
                    <tr>
                        <td>JENJANG</td>
                        <td>:</td>
                        <td>'.$data->jenjang.'</td>
                    </th>
                    <tr>
                        <td>NO. RUANGAN</td>
                        <td>:</td>
                        <td>'.$data->no_ruangan.'</td>
                    </tr>
                    <tr>
                        <td>NO. BANGKU</td>
                        <td>:</td>
                        <td>'.$data->no_bangku.'</td>
                    </tr>
                </table>
            </td>
            <td style="text-align:right;width:60px"><img src="'.$base_url.'/img/upload/'.$data->pas_foto.'" height="70" width="55"></td>
        </tr>
    </table>
    <hr style="background-color: black; height: 0.25px; border: 0;">
    <h2 style="font-size:8px;text-align:center"><b>MATERI UJIAN</b></h2>
    <table style="font-size:8px;">
        <tr>
            <td style="vertical-align: top;width:3px">1.</td>
            <td>Tes Potensi Akademik (TPA). (Matematika, Bahasa Indonesia, Bahasa inggris, IPA, IPS).</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Tes Membaca Dan Menulis Al-Qur\'an (BTQ).</td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Wawancara dengan orang tua calon santri.</td>
        </th>
        
    </table>
    <hr style="background-color: black; height: 0.25px; border: 0;">
    <h2 style="font-size:8px;text-align:center"><b>WAKTU UJIAN</b></h2>
    <table class="table" style="font-size:8px;">
        <tr>
            <td>TANGGAL : '.$data->tanggal.'</td>
        </tr>
        
        <tr>
            <td>07.30 - 08.00 Registrasi ulang</td>
        </tr>
        <tr>
            <td>08.00 - 10.00 Tes Potensi Akademik</td>
        </tr>
        <tr>
            <td>10.00 - 11.30 Tes BTQ dan wawancara</td>
        </tr>
    </table>
    <hr style="background-color: black; height: 0.25px; border: 0;">
    <table class="table" style="font-size:7px;width:100%">
        <tr>
            <td style="width:73px">
                <p>Tanda Tangan Peserta</p>
                <br>
                <br>
                <hr style="background-color: black; height: 0.25px; border: 0;">
            </td>
            <td style="width:10%">
                
            </td>
            <td>
                <table class="table" style="font-size:7px;">
                    <tr>
                        <td style="width:1px">*</td>
                        <td>Catatan:</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;width:1px">-</td>
                        <td> Pada saat ujian kartu ini harus dibawa.</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;width:1px">-</td>
                        <td> Peserta harus hadir 30 menit sebelum ujian dimulai.</td>
                    </tr>
                    
                </table>
            </td>
            <td style="text-align:center;"><p>Panitia PSB Pondok Mufidah Santi Asromo</p><img src="'.$base_url.'/img/asromologo.png" height="35"></td>
        </tr>
    </table>
    </div>
    
</body>
</html>';
			
			
	        $dompdf->setPaper('A6', 'potrait');
		    $options = new Options();
		    $options->set('isRemoteEnabled', true);
		    $options->set('isHtml5ParserEnabled', true);
		    $dompdf->setOptions($options);
		    $dompdf->loadHtml($html);
		    $dompdf->render();
		    $dompdf->stream("kartu_ujian_santi_asromo.pdf", array("Attachment" => 0));

		} catch (Exception $e) {
			echo $e;
		}
	}
}