<?php $title='Login'; require 'includes/header.php'; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $_SESSION['tries']=$_SESSION['tries']??0;
 if(!csrf_ok())$err='Invalid session.';elseif($_SESSION['tries']>=5)$err='Too many attempts. Close the browser and retry later.';
 else{$s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([strtolower(trim($_POST['email']??''))]);$u=$s->fetch();
  if($u&&password_verify($_POST['password']??'',$u['password_hash'])){session_regenerate_id(true);$_SESSION=['uid'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
   $nx=$_GET['next']??'';header('Location: '.(preg_match('/^[a-z\-]+\.php(\?[\w=&]*)?$/',$nx)?$nx:'index.php'));exit;}
  $_SESSION['tries']++;$err='Wrong email or password.';}}
if($err)echo '<div class="alert alert-danger">'.e($err).'</div>';?>
<form method="post" class="col-md-4 mx-auto"><h3>Login</h3><?=csrf_field()?><input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-2" id="pw" type="password" name="password" placeholder="Password" required><label class="mb-2"><input type="checkbox" onclick="pw.type=this.checked?'text':'password'"> Show password</label><br><button class="btn btn-primary">Login</button></form><?php require 'includes/footer.php';
