<?php
require_once __DIR__.'/../../includes/user-check.php';$flow=$_SESSION['diagnosis_flow']??null;
if(!$flow||count($flow['answers'])!==count($flow['questions'])){flash('error','Jawab semua pertanyaan sebelum melihat hasil.');redirect('/user/diagnosis/pertanyaan.php');}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Menyimpan diagnosis</title></head><body><form id="finish" method="post" action="<?= e(BASE_URL) ?>/user/proses-diagnosis.php"><?= csrf_field() ?><input type="hidden" name="motor_id" value="<?= (int)$flow['motor_id'] ?>"><?php foreach($flow['answers'] as $gid=>$answer): ?><input type="hidden" name="jawaban[<?= (int)$gid ?>]" value="<?= e($answer) ?>"><?php endforeach ?></form><script>document.getElementById('finish').submit();</script><noscript><button form="finish">Lanjutkan untuk menyimpan diagnosis</button></noscript></body></html>
