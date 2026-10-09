<?php require 'includes/functions.php'; need_login();
$eid=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT * FROM events WHERE id=? AND status='published' AND start_at>NOW()");$s->execute([$eid]);$ev=$s->fetch();
if(!$ev){http_response_code(404);exit('Event unavailable.');}
$tts=$pdo->prepare('SELECT * FROM ticket_types WHERE event_id=?');$tts->execute([$eid]);$tts=$tts->fetchAll();$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  if(!csrf_ok())throw new Exception('Invalid or duplicate submission.');
  $tid=(int)$_POST['ticket_type'];$q=(int)$_POST['qty'];$names=$_POST['aname']??[];$mails=$_POST['aemail']??[];
  if($q<1||$q>6)throw new Exception('Choose 1 to 6 tickets.');
  for($i=0;$i<$q;$i++){if(strlen(trim($names[$i]??''))<2||!filter_var($mails[$i]??'',FILTER_VALIDATE_EMAIL))throw new Exception('Enter valid details for every attendee.');}
  if(empty($_POST['terms']))throw new Exception('Accept the booking terms.');
  $pdo->beginTransaction();
  // Lock the row so concurrent bookings cannot oversell; price is read from DB, never from the client.
  $l=$pdo->prepare('SELECT * FROM ticket_types WHERE id=? AND event_id=? FOR UPDATE');$l->execute([$tid,$eid]);$t=$l->fetch();
  if(!$t)throw new Exception('Invalid ticket type.');if($t['remaining_qty']<$q)throw new Exception('Only '.$t['remaining_qty'].' tickets left.');
  $sub=$t['price']*$q;$fee=round($sub*0.05,2);$ref='EH'.strtoupper(bin2hex(random_bytes(5)));
  $pdo->prepare('UPDATE ticket_types SET remaining_qty=remaining_qty-? WHERE id=?')->execute([$q,$tid]);
  // DEMO: no real payment is processed; booking is confirmed as a simulated payment.
  $pdo->prepare("INSERT INTO bookings(ref,user_id,event_id,ticket_type_id,qty,subtotal,fee,total,status) VALUES(?,?,?,?,?,?,?,?,'confirmed')")->execute([$ref,uid(),$eid,$tid,$q,$sub,$fee,$sub+$fee]);
  $bid=$pdo->lastInsertId();$ia=$pdo->prepare('INSERT INTO attendees(booking_id,ticket_no,name,email) VALUES(?,?,?,?)');
  for($i=0;$i<$q;$i++)$ia->execute([$bid,'T'.strtoupper(bin2hex(random_bytes(6))),trim($names[$i]),$mails[$i]]);
  $pdo->commit();unset($_SESSION['t']);header('Location: ticket.php?b='.$ref);exit;
 }catch(Throwable $x){if($pdo->inTransaction())$pdo->rollBack();$err=($x instanceof PDOException)?'Booking failed. Please try again.':$x->getMessage();}}
$title='Book '.$ev['title'];require 'includes/header.php';if($err)echo '<div class="alert alert-danger">'.e($err).'</div>';?>
<h3>Book: <?=e($ev['title'])?></h3><div class="alert alert-info small">Demo mode: no real payment is taken. Service fee 5%.</div>
<form method="post" class="col-md-6"><?=csrf_field()?>
<select name="ticket_type" id="tt" class="form-select mb-2"><?php foreach($tts as $t)echo '<option value="'.$t['id'].'" data-p="'.$t['price'].'">'.e($t['name']).' — '.money($t['price']).' ('.(int)$t['remaining_qty'].' left)</option>';?></select>
<select name="qty" id="qty" class="form-select mb-2"><?php for($i=1;$i<=6;$i++)echo "<option>$i</option>";?></select><div id="att"></div>
<p>Total (incl. fee): <b id="tot"></b> <small class="text-muted">— final amount is recalculated on the server</small></p>
<label class="mb-2"><input type="checkbox" name="terms"> I accept the booking terms</label><br><button class="btn btn-primary">Confirm booking</button></form>
<script>const tt=document.getElementById('tt'),qty=document.getElementById('qty'),att=document.getElementById('att');
function r(){const q=+qty.value;let h='';for(let i=0;i<q;i++)h+=`<div class="row g-2 mb-2"><div class="col"><input class="form-control" name="aname[]" placeholder="Attendee ${i+1} name" required></div><div class="col"><input class="form-control" type="email" name="aemail[]" placeholder="Email" required></div></div>`;att.innerHTML=h;
const p=+tt.selectedOptions[0].dataset.p*q;document.getElementById('tot').textContent='₹'+(p*1.05).toFixed(2);}
tt.onchange=qty.onchange=r;r();</script><?php require 'includes/footer.php';
