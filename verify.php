<?php require 'includes/functions.php'; $title='Verify Ticket';
// QR holds only this URL; page reveals no personal data, only validity.
$s=$pdo->prepare('SELECT b.status,e.title,e.start_at FROM attendees a JOIN bookings b ON b.id=a.booking_id JOIN events e ON e.id=b.event_id WHERE a.ticket_no=?');$s->execute([$_GET['t']??'']);$r=$s->fetch();
require 'includes/header.php';
if($r&&$r['status']==='confirmed')echo '<div class="alert alert-success"><b>VALID</b> — '.e($r['title']).', '.date('d M Y H:i',strtotime($r['start_at'])).'</div>';
else echo '<div class="alert alert-danger"><b>INVALID</b> — ticket not found or cancelled.</div>';require 'includes/footer.php';
