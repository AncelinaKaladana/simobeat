<?php
/**
 * Forward Chaining deterministik untuk basis pengetahuan SIMOBEAT.
 * Mengembalikan aturan aktif pertama (urut kode) yang seluruh gejalanya YA.
 * Tidak menghitung persentase keyakinan: metode ini hanya menguji terpenuhi/tidak.
 */
function infer_forward_chaining(PDO $pdo,array $answers):array{
    $facts=[];foreach($answers as $id=>$answer){if($answer==='YA')$facts[(int)$id]=true;}
    $q=$pdo->query("SELECT a.id rule_id,a.kode rule_kode,a.kerusakan_id,k.kode kerusakan_kode,k.nama kerusakan_nama,k.deskripsi kerusakan_deskripsi,ad.gejala_id,g.kode gejala_kode,g.nama gejala_nama FROM aturan a JOIN kerusakan k ON k.id=a.kerusakan_id LEFT JOIN aturan_detail ad ON ad.aturan_id=a.id LEFT JOIN gejala g ON g.id=ad.gejala_id WHERE a.status='aktif' ORDER BY a.kode,a.id,g.kode");
    $rules=[];foreach($q->fetchAll(PDO::FETCH_ASSOC) as $row){$id=(int)$row['rule_id'];if(!isset($rules[$id]))$rules[$id]=['id'=>$id,'kode'=>$row['rule_kode'],'kerusakan_id'=>(int)$row['kerusakan_id'],'kerusakan_kode'=>$row['kerusakan_kode'],'kerusakan_nama'=>$row['kerusakan_nama'],'kerusakan_deskripsi'=>$row['kerusakan_deskripsi'],'gejala'=>[]];if($row['gejala_id']!==null)$rules[$id]['gejala'][]=['id'=>(int)$row['gejala_id'],'kode'=>$row['gejala_kode'],'nama'=>$row['gejala_nama']];}
    foreach($rules as $rule){$required=array_column($rule['gejala'],'id');if($required&&count(array_filter($required,static fn($gid)=>isset($facts[(int)$gid])))===count($required)){
        $s=$pdo->prepare('SELECT id,deskripsi FROM solusi WHERE kerusakan_id=? ORDER BY id');$s->execute([$rule['kerusakan_id']]);$solutions=$s->fetchAll(PDO::FETCH_ASSOC);
        return ['terpenuhi'=>true,'aturan'=>$rule,'kerusakan'=>['id'=>$rule['kerusakan_id'],'kode'=>$rule['kerusakan_kode'],'nama'=>$rule['kerusakan_nama'],'deskripsi'=>$rule['kerusakan_deskripsi']],'gejala_aturan'=>$rule['gejala'],'solusi'=>$solutions,'fakta_ya'=>array_map('intval',array_keys($facts))];
    }}
    return ['terpenuhi'=>false,'aturan'=>null,'kerusakan'=>null,'gejala_aturan'=>[],'solusi'=>[],'fakta_ya'=>array_map('intval',array_keys($facts))];
}
