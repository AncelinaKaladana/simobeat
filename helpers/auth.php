<?php
function require_login():void{if(empty($_SESSION['user'])){flash('error','Silakan masuk terlebih dahulu.');redirect('/auth/login.php');}}
function require_role(string $role):void{require_login();if(($_SESSION['user']['role']??'')!==$role){http_response_code(403);exit('Akses ditolak.');}}
function log_activity(PDO $pdo,?int $userId,string $activity):void{$q=$pdo->prepare('INSERT INTO log_aktivitas(user_id,aktivitas) VALUES(?,?)');$q->execute([$userId,$activity]);}
function attempt_login(PDO $pdo,string $email,string $password):bool{$q=$pdo->prepare('SELECT id,nama,email,password,role FROM users WHERE email=? LIMIT 1');$q->execute([$email]);$u=$q->fetch();if(!$u||!password_verify($password,$u['password']))return false;unset($u['password']);session_regenerate_id(true);$_SESSION['user']=$u;log_activity($pdo,(int)$u['id'],'Login');return true;}
