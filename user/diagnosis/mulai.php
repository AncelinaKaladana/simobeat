<?php
require_once __DIR__.'/../../includes/user-check.php';
if($_SERVER['REQUEST_METHOD']!=='POST')redirect('/user/diagnosis/index.php');verify_csrf();
$motorId=(int)($_POST['motor_id']??0);$q=$pdo->prepare('SELECT id FROM motor WHERE id=?');$q->execute([$motorId]);
if(!$q->fetchColumn()){flash('error','Pilih motor yang tersedia.');redirect('/user/diagnosis/index.php');}
$questions=$pdo->query('SELECT id FROM gejala ORDER BY kode,id')->fetchAll(PDO::FETCH_COLUMN);
if(!$questions){flash('error','Belum ada gejala yang dapat digunakan.');redirect('/user/diagnosis/index.php');}
$_SESSION['diagnosis_flow']=['motor_id'=>$motorId,'questions'=>array_map('intval',$questions),'answers'=>[],'step'=>0];redirect('/user/diagnosis/pertanyaan.php');
