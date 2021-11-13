<?php

require __DIR__ . '/controllers_frontend/common.php';
require __DIR__ . '/controllers_frontend/HomeController.php';
require __DIR__ . '/controllers_frontend/BiayaController.php';
require __DIR__ . '/controllers_frontend/SelayangController.php';
require __DIR__ . '/controllers_frontend/AgendaController.php';
require __DIR__ . '/controllers_frontend/PanduanController.php';
require __DIR__ . '/controllers_frontend/PersyaratanController.php';
require __DIR__ . '/controllers_frontend/PendaftaranController.php';
require __DIR__ . '/controllers_frontend/ListpendaftarController.php';
require __DIR__ . '/controllers_frontend/CetakkartuController.php';
require __DIR__ . '/controllers_frontend/HasilseleksiController.php';

require __DIR__ . '/controllers_backend/UserController.php';
require __DIR__ . '/controllers_backend/VerifikasiController.php';
require __DIR__ . '/controllers_backend/DatapendaftaranController.php';
require __DIR__ . '/controllers_backend/SeleksiController.php';
require __DIR__ . '/controllers_backend/SessionController.php';
require __DIR__ . '/controllers_backend/AgendaBackController.php';
require __DIR__ . '/controllers_backend/ConfigController.php';
require __DIR__ . '/controllers_backend/BiayaBackController.php';
require __DIR__ . '/controllers_backend/HasilTestController.php';


//backend
$app->post('/user_login', '\UserController:user_login');
// $app->get('/cek_kuota', '\VerifikasiController:count_jumlah_pendaftar');
$app->group('/api_v1', function () use ($app) {
    $app->get('/auth', '\UserController:auth'); 

    //verifikasi
    $app->get('/verifikasi/{page}', '\VerifikasiController:verifikasi');
    $app->get('/search_verifikasi', '\VerifikasiController:search');
    $app->get('/verifikasi_kartu_ujian/{id_pendaftaran}', '\VerifikasiController:verifikasi_kartu_ujian');
    $app->post('/verifikasi_generate_kartu_ujian', '\VerifikasiController:verifikasi_generate_kartu_ujian');
    $app->post('/batal_verifikasi', '\VerifikasiController:batal_verifikasi');
    $app->get('/cek_kuota', '\VerifikasiController:count_jumlah_pendaftar');

    //data pendaftaran
    $app->get('/datapendaftaran/{page}', '\DatapendaftaranController:datapendaftaran');
    $app->get('/datapendaftaran_id/{id_pendaftaran}', '\DatapendaftaranController:datapendaftaran_id');
    $app->get('/search_datapendaftaran', '\DatapendaftaranController:search');
    $app->post('/hapus_datapendaftaran', '\DatapendaftaranController:hapus_pendaftaran');
    $app->post('/upload_image', '\DatapendaftaranController:upload_image');
    $app->post('/update_pendaftaran', '\DatapendaftaranController:update_pendaftaran');

    //seleksi
    $app->get('/seleksi/{page}', '\SeleksiController:seleksi');
    $app->get('/search_seleksi', '\SeleksiController:search');
    $app->post('/luluskan_seleksi', '\SeleksiController:luluskan');
    $app->post('/batalkan_seleksi', '\SeleksiController:batalkan');

    //session
    $app->get('/session/{page}', '\SessionController:session');
    $app->get('/search_session', '\SessionController:search');
    $app->post('/save_session', '\SessionController:save');
    $app->post('/update_session', '\SessionController:update');
    $app->post('/delete_session', '\SessionController:delete');
    $app->post('/activate_session', '\SessionController:activate');

    //biaya
    $app->get('/biaya_back', '\BiayaBackController:biaya_back');
    $app->post('/save_biaya_back', '\BiayaBackController:save');
    $app->post('/update_biaya_back', '\BiayaBackController:update');
    $app->post('/delete_biaya_back', '\BiayaBackController:delete');
    
    //agenda
    $app->get('/agenda', '\AgendaBackController:agenda');
    $app->post('/update_agenda', '\AgendaBackController:update');

    //agenda
    $app->get('/config', '\ConfigController:config');
    $app->post('/update_config', '\ConfigController:update');

    //hasil test
    $app->get('/hasil', '\HasilTestController:hasil_id');
    $app->post('/save_hasil', '\HasilTestController:save');
    $app->post('/update_hasil', '\HasilTestController:update');
    $app->post('/delete_hasil', '\HasilTestController:delete');
    $app->post('/upload_hasil', '\HasilTestController:upload');
});

//frontend
$app->get('/', '\HomeController:index');
$app->get('/selayang-pandang', '\SelayangController:index');
$app->get('/agenda-psb', '\AgendaController:index');
$app->get('/biaya-pendidikan', '\BiayaController:index');
$app->get('/persyaratan', '\PersyaratanController:index');
$app->get('/panduan', '\PanduanController:index');
$app->get('/pendaftaran', '\PendaftaranController:index');
$app->get('/sukses-daftar/{kode_pendaftaran}', '\PendaftaranController:sukses');
$app->get('/list-pendaftar', '\ListpendaftarController:index');
$app->get('/list-pendaftar-s', '\ListpendaftarController:index_search');
$app->get('/list-pendaftar-detail/{id}', '\ListpendaftarController:detail');
$app->get('/cetak-kartu', '\CetakkartuController:index');
$app->get('/cetak-kartu-s', '\CetakkartuController:index_search');
$app->get('/cetak-kartu-pdf/{id}/{no_ujian}', '\CetakkartuController:cetak_kartu');
$app->get('/hasil-seleksi', '\HasilseleksiController:index');
$app->post('/submit_pdftr', '\PendaftaranController:submit');
$app->post('/upload_foto', '\PendaftaranController:upload_foto');
$app->post('/testaja', function ($request, $response, $args) {
    try {
        $input = $request->getParsedBody();
        $name_photo = $input['name'];
        $target_dir = "img/upload/";
        $ext = strtolower(substr(strrchr(basename($_FILES["file"]["name"]), '.'), 1));
        $target_file = $target_dir.$name_photo.'.'.$ext;
        $filename = $name_photo.'.'.$ext;
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
        return $response->write($filename);
    } catch (Exception $e) {
        return $this->response->withJson([]);
    }
});

//wilayah
$app->group('/wil', function () use ($app) {
    $app->get('/provinsi', function ($request, $response, $args) {
        try {
            $sth = $this->db->prepare("select * from psb_provinsi");
            $sth->execute();
            $data = $sth->fetchAll();
            return $this->response->withJson($data);
        } catch (Exception $e) {
            return $this->response->withJson([]);
        }
    });

    

    $app->get('/kabupaten/{id_prov}', function ($request, $response, $args) {
        try {
            $id_prov = $args['id_prov'];
            $sth = $this->db->prepare("select * from psb_kabupaten where id_prov = '$id_prov'");
            $sth->execute();
            $data = $sth->fetchAll();
            return $this->response->withJson($data);
        } catch (Exception $e) {
            return $this->response->withJson([]);
        }
    });

    $app->get('/kecamatan/{id_kab}', function ($request, $response, $args) {
        try {
            $id_kab = $args['id_kab'];
            $sth = $this->db->prepare("select * from psb_kecamatan where id_kab = '$id_kab'");
            $sth->execute();
            $data = $sth->fetchAll();
            return $this->response->withJson($data);
        } catch (Exception $e) {
            return $this->response->withJson([]);
        }
    });

    $app->get('/kelurahan/{id_kec}', function ($request, $response, $args) {
        try {
            $id_kec = $args['id_kec'];
            $sth = $this->db->prepare("select * from psb_kelurahan where id_kec = '$id_kec'");
            $sth->execute();
            $data = $sth->fetchAll();
            return $this->response->withJson($data);
        } catch (Exception $e) {
            return $this->response->withJson([]);
        }
    });
});
