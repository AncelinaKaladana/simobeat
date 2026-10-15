<?php
require_once __DIR__.'/../includes/user-check.php';
require_once __DIR__.'/../helpers/forward-chaining.php';
if($_SERVER['REQUEST_METHOD']!=='POST')redirect('/user/diagnosis.php');
verify_csrf();
$motorId=(int)($_POST['motor_id']??0);$answers=$_POST['jawaban']??[];
if(!is_array($answers)){$answers=[];}
$q=$pdo->query('SELECT id FROM gejala ORDER BY id');$expected=array_map('intval',$q->fetchAll(PDO::FETCH_COLUMN));$posted=array_map('intval',array_keys($answers));sort($expected);sort($posted);
if(!$motorId||$expected!==$posted){flash('error','Pilih motor dan jawab setiap gejala satu kali.');redirect('/user/diagnosis.php');}
$normalized=[];foreach($answers as $gid=>$answer){$gid=(int)$gid;if(!in_array($answer,['YA','TIDAK'],true)){flash('error','Ada jawaban gejala yang tidak valid. Silakan ulangi diagnosis.');redirect('/user/diagnosis.php');}$normalized[$gid]=$answer;}
$q=$pdo->prepare('SELECT id FROM motor WHERE id=?');$q->execute([$motorId]);if(!$q->fetchColumn()){flash('error','Motor yang dipilih tidak tersedia.');redirect('/user/diagnosis.php');}
$result=infer_forward_chaining($pdo,$normalized);$faultId=$result['terpenuhi']?(int)$result['kerusakan']['id']:null;$label=$result['terpenuhi']?$result['kerusakan']['nama']:'Tidak ditemukan aturan yang cocok';
$pdo->beginTransaction();try{$q=$pdo->prepare('INSERT INTO diagnosis(user_id,motor_id,kerusakan_id,hasil) VALUES(?,?,?,?)');$q->execute([(int)$_SESSION['user']['id'],$motorId,$faultId,$label]);$diagnosisId=(int)$pdo->lastInsertId();$q=$pdo->prepare('INSERT INTO diagnosis_gejala(diagnosis_id,gejala_id,jawaban) VALUES(?,?,?)');foreach($normalized as $gid=>$answer)$q->execute([$diagnosisId,$gid,$answer]);$q=$pdo->prepare('INSERT INTO log_aktivitas(user_id,aktivitas) VALUES(?,?)');$q->execute([(int)$_SESSION['user']['id'],'Diagnosis '.$diagnosisId.' diproses'.($result['terpenuhi']?' dengan aturan '.$result['aturan']['kode']:' tanpa aturan cocok')]);$pdo->commit();unset($_SESSION['diagnosis_flow']);redirect('/user/hasil-diagnosis.php?id='.$diagnosisId);}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();throw $e;}
