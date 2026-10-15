<?php
require_once __DIR__.'/../../includes/user-check.php';if($_SERVER['REQUEST_METHOD']!=='POST')redirect('/user/diagnosis/index.php');verify_csrf();
$flow=$_SESSION['diagnosis_flow']??null;if(!$flow){flash('error','Sesi diagnosis berakhir. Silakan mulai kembali.');redirect('/user/diagnosis/index.php');}
$action=$_POST['action']??'';$step=(int)$flow['step'];$total=count($flow['questions']);
if($action==='cancel'){unset($_SESSION['diagnosis_flow']);flash('success','Diagnosis dibatalkan.');redirect('/user/dashboard.php');}
if($action==='back'){if($step>0){$flow['step']=$step-1;$_SESSION['diagnosis_flow']=$flow;}redirect('/user/diagnosis/pertanyaan.php');}
if($action!=='answer'||!in_array($_POST['jawaban']??'', ['YA','TIDAK'],true)){flash('error','Jawaban tidak valid.');redirect('/user/diagnosis/pertanyaan.php');}
$gid=(int)$flow['questions'][$step];$flow['answers'][$gid]=$_POST['jawaban'];$flow['step']=$step+1;$_SESSION['diagnosis_flow']=$flow;redirect($flow['step']>=$total?'/user/diagnosis/selesai.php':'/user/diagnosis/pertanyaan.php');
