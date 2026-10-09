<?php require 'includes/functions.php';
$st=$pdo->prepare(EV_SQL.' WHERE e.id=?');$st->execute([(int)($_GET['id']??0)]);$ev=$st->fetch();
if(!$ev){http_response_code(404);$title='Not found';require 'includes/header.php';echo '<h3>Event not found</h3>';require 'includes/footer.php';exit;}
$title=$ev['title'];require 'includes/header.php';
$tt=$pdo->prepare('SELECT * FROM ticket_types WHERE event_id=?');$tt->execute([$ev['id']]);$tt=$tt->fetchAll();
$open=$ev['status']==='published'&&strtotime($ev['start_at'])>time()&&$ev['avail']>0;?>
<div class="ev-img rounded mb-3" style="height:200px"><?=e($ev['category'])?></div><h2><?=e($ev['title'])?></h2>
<p><?=date('l, d M Y H:i',strtotime($ev['start_at']))?> · <?=e($ev['venue'])?>, <?=e($ev['city'])?> · by <?=e($ev['organizer'])?></p><div class="cd h5 text-primary" data-t="<?=date('c',strtotime($ev['start_at']))?>"></div>
<p><?=nl2br(e($ev['description']))?></p>
<table class="table"><tr><th>Ticket</th><th>Price</th><th>Left</th></tr><?php foreach($tt as $t)echo '<tr><td>'.e($t['name']).'</td><td>'.money($t['price']).'</td><td>'.(int)$t['remaining_qty'].'</td></tr>';?></table>
<?php if($open)echo '<a class="btn btn-primary" href="booking.php?id='.$ev['id'].'">Book Tickets</a>';else echo '<div class="alert alert-warning">Booking closed: event is '.($ev['status']!=='published'?e($ev['status']):($ev['avail']>0?'completed':'sold out')).'.</div>';?>
<?php require 'includes/footer.php';
