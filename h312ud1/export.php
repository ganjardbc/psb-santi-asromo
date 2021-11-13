<?php
// Load file koneksi.php
include "koneksi.php";
// Load plugin PHPExcel nya
require_once 'PHPExcel/PHPExcel.php';
// Panggil class PHPExcel nya
$excel = new PHPExcel();
// Settingan awal file excel
$excel->getProperties()->setCreator('Herudi')
             ->setLastModifiedBy('Herudi')
             ->setTitle("Data Calon Santri")
             ->setSubject("Calon Santri")
             ->setDescription("Berikut adalah data calon santri santiasromo")
             ->setKeywords("Data Calon Santri");
// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
$style_col = array(
  'font' => array('bold' => true), // Set font nya jadi bold
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);
// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);
$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA CALON SANTRI PONDOK MUFIDAH SANTI ASROMO"); // Set kolom A1 dengan tulisan "DATA SISWA"
$excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
// $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1
// Buat header tabel nya pada baris ke 3
$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA"); 
$excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA PANGGILAN");
$excel->setActiveSheetIndex(0)->setCellValue('D3', "JENIS KELAMIN"); 
$excel->setActiveSheetIndex(0)->setCellValue('E3', "TEMPAT LAHIR");
$excel->setActiveSheetIndex(0)->setCellValue('F3', "TANGGAL LAHIR"); 
$excel->setActiveSheetIndex(0)->setCellValue('G3', "ALAMAT"); 
$excel->setActiveSheetIndex(0)->setCellValue('H3', "PROVINSI"); 
$excel->setActiveSheetIndex(0)->setCellValue('I3', "KABUPATEN"); 
$excel->setActiveSheetIndex(0)->setCellValue('J3', "KECAMATAN"); 
$excel->setActiveSheetIndex(0)->setCellValue('K3', "KELURAHAN"); 
$excel->setActiveSheetIndex(0)->setCellValue('L3', "KODE POS"); 
$excel->setActiveSheetIndex(0)->setCellValue('M3', "ASAL SEKOLAH"); 
$excel->setActiveSheetIndex(0)->setCellValue('N3', "JENJANG"); 
$excel->setActiveSheetIndex(0)->setCellValue('O3', "KATEGORI DAFTAR"); 
$excel->setActiveSheetIndex(0)->setCellValue('P3', "PINDAHAN KELAS"); 
$excel->setActiveSheetIndex(0)->setCellValue('Q3', "NAMA IBU"); 
$excel->setActiveSheetIndex(0)->setCellValue('R3', "NAMA AYAH"); 
$excel->setActiveSheetIndex(0)->setCellValue('S3', "NAMA WALI"); 
$excel->setActiveSheetIndex(0)->setCellValue('T3', "PENDIDIKAN IBU"); 
$excel->setActiveSheetIndex(0)->setCellValue('U3', "PENDIDIKAN AYAH"); 
$excel->setActiveSheetIndex(0)->setCellValue('V3', "PEKERJAAN IBU"); 
$excel->setActiveSheetIndex(0)->setCellValue('W3', "PEKERJAAN AYAH"); 
$excel->setActiveSheetIndex(0)->setCellValue('X3', "ALAMAT ORTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('Y3', "PROVINSI ORTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('Z3', "KABUPATEN ORTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('AA3', "KECAMATAN ORTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('AB3', "KELURAHAN ORTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('AC3', "NO HP ORTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('AD3', "STATUS"); 
$excel->setActiveSheetIndex(0)->setCellValue('AE3', "TRANSFER"); 
$excel->setActiveSheetIndex(0)->setCellValue('AF3', "NO. KARTU"); 
$excel->setActiveSheetIndex(0)->setCellValue('AG3', "NO. RUANGAN"); 
$excel->setActiveSheetIndex(0)->setCellValue('AH3', "NO. BANGKU"); 
$excel->setActiveSheetIndex(0)->setCellValue('AI3', "GELOMBANG"); 
$excel->setActiveSheetIndex(0)->setCellValue('AJ3', "TANGGAL UJIAN"); 
$excel->setActiveSheetIndex(0)->setCellValue('AK3', "ASAL SEKOLAH"); 
$excel->setActiveSheetIndex(0)->setCellValue('AL3', "NIK"); 
$excel->setActiveSheetIndex(0)->setCellValue('AM3', "NISN"); 
// Apply style header yang telah kita buat tadi ke masing-masing kolom header
$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('O3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('P3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('Q3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('R3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('S3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('T3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('U3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('V3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('W3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('X3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('Y3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('Z3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AA3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AB3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AC3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AD3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AE3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AF3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AG3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AH3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AI3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AJ3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AK3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AL3')->applyFromArray($style_col);
$excel->getActiveSheet()->getStyle('AM3')->applyFromArray($style_col);
// Set height baris ke 1, 2 dan 3
$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
// Buat query untuk menampilkan semua data siswa
// $sql = $pdo->prepare("select 
//                 IFNULL(k.no_kartu,'belum ada') as no_kartu,
//                 IFNULL(k.no_ruangan,'belum ada') as no_ruangan,
//                 IFNULL(k.no_bangku,'belum ada') as no_bangku,
//                 IFNULL(k.gelombang,'belum ada') as gelombang,
//                 IFNULL(k.tanggal,'belum ada') as tanggal,
//                 p.* 
//                 from psb_pendaftaran p 
//                 left join psb_kartu_ujian k on k.id_pendaftaran = p.id");

$sql = $pdo->prepare("select 
  k.no_kartu,
  k.no_ruangan,
  k.no_bangku,
  k.gelombang,
  k.tanggal, 
  p.* 
from psb_pendaftaran p join psb_kartu_ujian k on k.id_pendaftaran = p.id 
where k.id_session = (select id_session from psb_config limit 1)");
$sql->execute(); // Eksekusi querynya
$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
  $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
  $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['nama']);
  $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['nama_panggilan']);
  $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['jenis_kelamin']);
  $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['tempat_lahir']);
  $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['tanggal_lahir']);
  $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data['alamat']);
  $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data['provinsi']);
  $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data['kabupaten']);
  $excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $data['kecamatan']);
  $excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $data['kelurahan']);
  $excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $data['kode_pos'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $data['asal_sekolah']);
  $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $data['jenjang']);
  $excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $data['kategori_pendaftaran']);
  $excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $data['pindahan_kelas'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $data['nama_ibu']);
  $excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $data['nama_ayah']);
  $excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $data['nama_wali']);
  $excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $data['pendidikan_ibu']);
  $excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $data['pendidikan_ayah']);
  $excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $data['pekerjaan_ibu']);
  $excel->setActiveSheetIndex(0)->setCellValue('W'.$numrow, $data['pekerjaan_ayah']);
  $excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $data['alamat_ortu']);
  $excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $data['provinsi_ortu']);
  $excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $data['kabupaten_ortu']);
  $excel->setActiveSheetIndex(0)->setCellValue('AA'.$numrow, $data['kecamatan_ortu']);
  $excel->setActiveSheetIndex(0)->setCellValue('AB'.$numrow, $data['kelurahan_ortu']);
  $excel->setActiveSheetIndex(0)->setCellValue('AC'.$numrow, $data['no_hp'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AD'.$numrow, $data['status'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AE'.$numrow, $data['nominal_transfer'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AF'.$numrow, $data['no_kartu'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AG'.$numrow, $data['no_ruangan'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AH'.$numrow, $data['no_bangku'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AI'.$numrow, $data['gelombang']);
  $excel->setActiveSheetIndex(0)->setCellValue('AJ'.$numrow, $data['tanggal']);
  $excel->setActiveSheetIndex(0)->setCellValue('AK'.$numrow, $data['nama_sekolah']);
  $excel->setActiveSheetIndex(0)->setCellValue('AL'.$numrow, $data['nik']."",PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->setActiveSheetIndex(0)->setCellValue('AM'.$numrow, $data['nisn'],PHPExcel_Cell_DataType::TYPE_STRING);
  
  // // Khusus untuk no telepon. kita set type kolom nya jadi STRING
  // $excel->setActiveSheetIndex(0)->setCellValueExplicit('E'.$numrow, $data['telp'], PHPExcel_Cell_DataType::TYPE_STRING);
  
  // $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['alamat']);
  
  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
  $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('U'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('V'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('W'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('X'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('Y'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('Z'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AA'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AB'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AC'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AD'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AE'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AF'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AG'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AH'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AI'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AJ'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AK'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AL'.$numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('AM'.$numrow)->applyFromArray($style_row);
  
  $excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);
  
  $no++; // Tambah 1 setiap kali looping
  $numrow++; // Tambah 1 setiap kali looping
}
// Set width kolom
$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(25); // Set width kolom B
$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
$excel->getActiveSheet()->getColumnDimension('D')->setWidth(25); // Set width kolom D
$excel->getActiveSheet()->getColumnDimension('E')->setWidth(25); // Set width kolom E
$excel->getActiveSheet()->getColumnDimension('F')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('G')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('H')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('I')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('J')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('K')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('L')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('M')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('N')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('O')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('P')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('R')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('S')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('T')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('U')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('V')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('W')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('X')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('Y')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('Z')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AA')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AB')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AC')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AD')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AE')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AF')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AG')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AH')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AI')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AK')->setWidth(35); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AL')->setWidth(25); // Set width kolom F
$excel->getActiveSheet()->getColumnDimension('AM')->setWidth(25); // Set width kolom F

// Set orientasi kertas jadi LANDSCAPE
$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
// Set judul file excel nya
$excel->getActiveSheet(0)->setTitle("Laporan Data Calon Santri");
$excel->setActiveSheetIndex(0);
// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Data_calon_santri.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');
$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$write->save('php://output');
?>