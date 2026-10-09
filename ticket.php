<?php require 'includes/functions.php'; need_login();
$s=$pdo->prepare('SELECT b.*,e.title,e.venue,e.start_at,t.name tname FROM bookings b JOIN events e ON e.id=b.event_id JOIN ticket_types t ON t.id=b.ticket_type_id WHERE b.ref=? AND b.user_id=?');
$s->execute([$_GET['b']??'',uid()]);$b=$s->fetch();if(!$b){http_response_code(403);exit('Not found or not yours.');}
$a=$pdo->prepare('SELECT * FROM attendees WHERE booking_id=?');$a->execute([$b['id']]);$title='Ticket';require 'includes/header.php';?>
<h3>Booking <?=e($b['ref'])?> <span class="badge bg-success"><?=e($b['status'])?></span></h3><p>Total paid (demo): <?=money($b['total'])?> <button class="btn btn-sm btn-outline-secondary" onclick="print()">Print</button></p>
<div class="row g-3"><?php foreach($a->fetchAll() as $i=>$t){$url=(isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']),'/').'/verify.php?t='.$t['ticket_no'];?>
<div class="col-md-6"><div class="card p-3"><b>EVENTHUB</b><h5><?=e($b['title'])?></h5><div><?=date('d M Y H:i',strtotime($b['start_at']))?> · <?=e($b['venue'])?></div>
<div>Attendee: <?=e($t['name'])?> · <?=e($b['tname'])?> · No. <?=e($t['ticket_no'])?></div><div id="qr<?=$i?>" class="mt-2"></div><script>window.qrs=(window.qrs||[]).concat([['qr<?=$i?>',<?=json_encode($url)?>]]);</script></div></div><?php }?></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script><script>qrs.forEach(([id,u])=>new QRCode(document.getElementById(id),{text:u,width:128,height:128}));</script><?php require 'includes/footer.php';
