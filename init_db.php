<?php
require 'db.php';

$db->exec("CREATE TABLE IF NOT EXISTS bahan (
  id INTEGER PRIMARY KEY,
  nama TEXT NOT NULL,
  deskripsi TEXT NOT NULL,
  harga INTEGER NOT NULL,
  jenis TEXT NOT NULL
)");

$db->exec("INSERT OR IGNORE INTO bahan (id, nama, jenis, deskripsi, harga) VALUES
(1,'Kunyit','Bahan utama','Antioksidan, antiradang, meningkatkan sistem imun, meredakan nyeri haid',1500),
(2,'Jahe','Bahan utama','Menghangatkan tubuh, meredakan nyeri otot, meningkatkan imun, mencegah mual',1200),
(3,'Temulawak','Bahan utama','Melindungi hati, antiinflamasi, meningkatkan nafsu makan',2000),
(4,'Kencur','Bahan utama','Meredakan nyeri, antibakteri, melancarkan pencernaan, meningkatkan nafsu makan',1500),
(5,'Serai','Bahan utama','Meredakan demam, melancarkan pencernaan, mengurangi stres',800),
(6,'Daun Pepaya','Bahan utama','Meningkatkan nafsu makan, membantu pencernaan dengan enzim papain',600),
(7,'Mengkudu','Bahan utama','Mengelola tekanan darah, pereda nyeri, memperbaiki pencernaan',2100),
(8,'Daun Beluntas','Bahan utama','Antibakteri, detoksifikasi, menghilangkan bau badan',800),
(9,'Asam Jawa','Bahan utama','Menurunkan suhu badan, menyegarkan, mendukung kesehatan hati',1000),
(10,'Cengkeh','Rempah tambahan','Mengatasi sakit kepala, antibakteri',800),
(11,'Kayu Manis','Rempah tambahan','Menurunkan gula darah, meningkatkan metabolisme',800),
(12,'Daun Pandan','Rempah tambahan','Memberi aroma harum, membantu pencernaan',800),
(13,'Kapulaga','Rempah tambahan','Melancarkan peredaran darah, meningkatkan nafsu makan',500),
(14,'Bunga Lawang','Rempah tambahan','Memberi aroma khas, membantu pencernaan',500),
(15,'Daun Sirih','Rempah tambahan','Antiseptik, kesehatan mulut dan organ kewanitaan',500),
(16,'Gula Merah','Pemanis','Menambah rasa manis alami, sumber energi',1000),
(17,'Madu','Pemanis','Meningkatkan imun, mempercepat penyembuhan, menambah rasa manis',2000),
(18,'Tebu','Pemanis','Menambah rasa manis alami, mempercepat penyembuhan',1000),
(19,'Lemon','Bahan tambahan','Menambah rasa segar, sumber vitamin C',1200),
(20,'Delima','Bahan tambahan','Antioksidan, meningkatkan stamina',3400),
(21,'Soda','Bahan tambahan','Memberi sensasi segar dan rasa modern pada jamu',1000),
(22,'Mint','Bahan tambahan','Memberi sensasi segar, antibakteri',800),
(23,'Stevia','Pemanis','Menambah rasa manis alami, sumber energi',2000)");

$db->exec("CREATE TABLE IF NOT EXISTS racikan (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nama_racikan TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS racikan_bahan (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  racikan_id INTEGER NOT NULL,
  bahan_id INTEGER NOT NULL,
  porsi INTEGER NOT NULL
)");

echo "Database berhasil dibuat!";