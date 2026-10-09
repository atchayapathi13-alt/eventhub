<?php
// Shared helpers: session, escaping, CSRF, auth guards, event card.
session_start();
require_once __DIR__.'/../config/database.php';
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function csrf_field(){if(empty($_SESSION['t']))$_SESSION['t']=bin2hex(random_bytes(32));return '<input type="hidden" name="t" value="'.$_SESSION['t'].'">';}
function csrf_ok(){return isset($_POST['t'],$_SESSION['t'])&&hash_equals($_SESSION['t'],$_POST['t']);}
function uid(){return $_SESSION['uid']??null;}
function need_login(){if(!uid()){header('Location: login.php?next='.urlencode(basename($_SERVER['REQUEST_URI'])));exit;}}
function money($n){return $n>0?'₹'.number_format($n,2):'Free';}
function card($ev){
 $p=$ev['min_price']??0; $h='<div class="col-md-6 col-lg-4"><div class="card h-100 shadow-sm ev"><div class="ev-img">'.e($ev['category']).'</div><div class="card-body">';
 $h.=($ev['featured']?'<span class="badge bg-warning text-dark mb-1">Featured</span> ':'').'<h5>'.e($ev['title']).'</h5>';
 $h.='<p class="small text-muted mb-1">'.date('D, d M Y H:i',strtotime($ev['start_at'])).'<br>'.e($ev['venue']).', '.e($ev['city']).'</p>';
 $h.='<p class="mb-1"><b>'.($p>0?'From '.money($p):'Free').'</b> · '.(int)$ev['avail'].' left</p>';
 $h.='<div class="cd small text-primary" data-t="'.date('c',strtotime($ev['start_at'])).'"></div></div><div class="card-footer bg-white border-0">';
 $h.='<a class="btn btn-outline-primary btn-sm" href="event-details.php?id='.$ev['id'].'">View Details</a> <a class="btn btn-primary btn-sm" href="booking.php?id='.$ev['id'].'">Book Now</a></div></div></div>';
 return $h;}
const EV_SQL='SELECT e.*,c.name category,(SELECT MIN(price) FROM ticket_types WHERE event_id=e.id) min_price,(SELECT COALESCE(SUM(remaining_qty),0) FROM ticket_types WHERE event_id=e.id) avail FROM events e JOIN categories c ON c.id=e.category_id';
