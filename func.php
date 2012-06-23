<?php
/**
 * Kumpulan fungsi untuk toolbox aja
 * 
 * @author Ratno Putro Sulistiyono | ratno@comlabs.itb.ac.id | ratno@knoqdown.com
 */


function d($data, $die = false) {
  echo "<pre>";
  print_r($data);
  echo "</pre>";
  if ($die)
    die(":: die at " . date("d/m/Y H:i:s") . " ::");
}

function main_desc_count($judul,$unit) {
  return '<br /><blockquote>' . $judul . '<br />
		From : ' . $unit . '<br>
		To : DataCenter ITB<br>
		Parameter : <blockquote>[password] => string : Password webservice <br />
		[is_compressed] => int : 1=true, 0=false (format data yang akan dikirim apakah dalam bentuk terkompresi atau tidak)</blockquote><br />
		Output : JSON string<br>
		<blockquote>[err_no]   => integer : Kode error ( 0 jika tidak ada error)<br>
		[err_teks]   => string : Teks error ( kosong jika nilai field [err_no]=0 )<br>
		[data] => int : Jumlah data</blockquote><br />
		</blockquote>';
}

function main_desc($judul, $unit, $custom_param = '') {
  if ($custom_param == '') {
    $custom_param = '[password] => string : Password webservice<br />
			[start] => int : indeks awal data yg akan diambil ( dimulai dari 1 )<br />
			[limit] => int : jumlah data yg akan diambil ( maksimal sebanyak [jumlah] dari webservice ***Count() )<br />
			[is_compressed] => int : 1=true, 0=false ( data yang dikirim apakah dalam bentuk terkompresi atau tidak )';
  }

  return '<br /><blockquote>' . $judul . '<br />
	From : ' . $unit . '<br />
	To : DataCenter ITB<br />
	Parameter : <blockquote>' . $custom_param . '</blockquote><br />
	Output : JSON string<br />
	<blockquote>[err_no]   => integer : Kode error ( 0 jika tidak ada error)<br />
	[err_teks]   => string : Teks error ( kosong jika nilai field [err_no]=0 )<br />
	[data] => array (<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[idx] => array (<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ daftar field ]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br />
	)</blockquote>
	</blockquote>';
}