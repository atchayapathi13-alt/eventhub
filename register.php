<?php $title='Register'; require 'includes/header.php'; $err=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!csrf_ok())$err[]='Invalid session. Reload and retry.';
 $n=trim($_POST['name']??'');$em=strtolower(trim($_POST['email']??''));$ph=trim($_POST['phone']??'');$p=$_POST['password']??'';
 if(strlen($n)<2)$err[]='Enter your name.';if(!filter_var($em,FILTER_VALIDATE_EMAIL))$err[]='Invalid email.';
 if(strlen($p)<8||!preg_match('/\d/',$p)||!preg_match('/[a-zA-Z]/',$p))$err[]='Password: 8+ chars with letters and numbers.';
 if($p!==($_POST['confirm']??''))$err[]='Passwords do not match.';if(empty($_POST['terms']))$err[]='Accept the terms.';
 if(!$err){$c=$pdo->prepare('SELECT 1 FROM users WHERE email=?');$c->execute([$em]);if($c->fetch())$err[]='Email already registered.';}
 if(!$err){$pdo->prepare('INSERT INTO users(name,email,phone,password_hash) VALUES(?,?,?,?)')->execute([$n,$em,$ph,password_hash($p,PASSWORD_DEFAULT)]);$_SESSION['flash']='Account created. Please log in.';header('Location: login.php');exit;}}
foreach($err as $x)echo '<div class="alert alert-danger">'.e($x).'</div>';?>
<form method="post" class="col-md-5 mx-auto"><h3>Create account</h3><?=csrf_field()?>
<input class="form-control mb-2" name="name" placeholder="Full name" required value="<?=e($_POST['name']??'')?>"><input class="form-control mb-2" type="email" name="email" placeholder="Email" required value="<?=e($_POST['email']??'')?>">
<input class="form-control mb-2" name="phone" placeholder="Phone"><input class="form-control mb-2" type="password" name="password" placeholder="Password" required><input class="form-control mb-2" type="password" name="confirm" placeholder="Confirm password" required>
<label class="mb-2"><input type="checkbox" name="terms"> I accept the Terms</label><br><button class="btn btn-primary">Register</button></form><?php require 'includes/footer.php';
