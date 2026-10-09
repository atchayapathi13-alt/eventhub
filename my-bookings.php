<?php require 'includes/functions.php'; need_login();
if($_SERVER['REQUEST_METHOD']==='POST'&&csrf_ok()){
 try{$pdo->beginTransaction();$s=$pdo->prepare("SELECT b.*,e.start_at FROM bookings b JOIN events e ON e.id=b.event_id WHERE b.id=? AND b.user_id=? AND b.status='confirmed' FOR UPDATE");$s->execute([(int)$_POST['cancel'],uid()]);$b=$s->fetch();
  // Policy: cancellation allowed up to 24h before start. Ownership is enforced in the query.
  if($b&&strtotime($b['start_at'])-time()>86400){$pdo->prepare("UPDATE bookings SET status='cancelled',cancelled_at=NOW() WHERE id=?")->execute([$b['id']]);
   $pdo->prepare('UPDATE ticket_types SET remaining_qty=remaining_qty+? WHERE id=?')->execute([$b['qty'],$b['ticket_type_id']]);$_SESSION['flash']='Booking cancelled.';}
  else $_SESSION['flash']='This booking cannot be cancelled (less than 24h to start).';
  $pdo->commit();}catch(Throwable $x){if($pdo->inTransaction())$pdo->rollBack();}
 header('Location: my-bookings.php');exit;}
$s=$pdo->prepare('SELECT b.*,e.title,e.start_at FROM bookings b JOIN events e ON e.id=b.event_id WHERE b.user_id=? ORDER BY b.created_at DESC');$s->execute([uid()]);$rows=$s->fetchAll();
$title='My Bookings';require 'includes/header.php';?>
<h3>My Bookings</h3><?php if(!$rows)echo '<p class="text-muted">No bookings yet. <a href="events.php">Explore events</a></p>';?>
<div class="table-responsive"><table class="table"><?php foreach($rows as $b){echo '<tr><td>'.e($b['ref']).'</td><td>'.e($b['title']).'<br><small>'.date('d M Y H:i',strtotime($b['start_at'])).'</small></td><td>'.(int)$b['qty'].' × '.money($b['total']).'</td><td>'.e($b['status']).'</td><td><a class="btn btn-sm btn-primary" href="ticket.php?b='.e($b['ref']).'">Tickets</a> ';
 if($b['status']==='confirmed')echo '<form method="post" class="d-inline">'.csrf_field().'<input type="hidden" name="cancel" value="'.$b['id'].'"><button class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Cancel booking?\')">Cancel</button></form>';echo '</td></tr>';}?></table></div><?php require 'includes/footer.php';
