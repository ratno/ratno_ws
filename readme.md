Ratno WS
========
ratno_ws ini ecosystem paling sederhana untuk menciptakan webservice dengan format keluaran json (string)

Instalasi
---------
1. [Download](https://github.com/ratno/ratno_ws/zipball/master)
2. Ekstrak
3. Set write permission pada `md5.txt` dan `generated_ws.php`

    $ chmod 777 md5.txt
    $ chmod 777 generated_ws.php

4. Edit `ws.yml`, dan voila... webservice anda sudah siap
5. Buka `http://localhost/folder_ws_anda/index.php` untuk mengakses webservice point 
6. Buka `http://localhost/folder_ws_anda/client.php` untuk melihat halaman test client (terproteksi password webservice)
7. Buka `http://localhost/folder_ws_anda/data_dict.php` untuk melihat halaman informasi data dictionary (terproteksi password webservice)

Panduan ws.yml
--------------
Struktur ws.yml terdiri atas tiga bagian utama:

1. config, menyimpan semua konfigurasi sistem terkait dengan koneksi ke database yang nantinya diimplement oleh adodb
2. ws, menyimpan semua konfigurasi umum webservice
3. services, menyimpan konfigurasi service yang akan disediakan 

#### config
* `driver`, database driver type (contoh: mysql, oci8, postgresql, sqlserver)
* `server`, alamat database server (contoh: 167.205.xxx.yyy atau db.itb.ac.id)
* `username`, username untuk mengakses database
* `password`, password untuk mengakses database
* `database`, nama database
* `oraclesid`, khusus untuk oracle, jika menggunakan SID set ke true, jika tidak set ke false

#### ws
* `password`, password umum web service, bisa di override oleh settingan per service
* `unit`, nama unit yang mengeluarkan webservice
* `nama`, nama wsdl (contoh: unit.wsdl)
* `class`, nama class yg akan di-generate (contoh: unit_ws)
* `ns`, link ke halaman service (contoh: http://localhost/soap/ratno_ws/index.php)
* `zip_data`, setting apakah data yang dikirim akan di zip/compress, default false, namun bisa dioverride per-request by client