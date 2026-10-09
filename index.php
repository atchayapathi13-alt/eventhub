<?php $title='Home'; require 'includes/header.php';
$s=$pdo->query("SELECT (SELECT COUNT(*) FROM events WHERE status='published') ev,(SELECT COUNT(*) FROM attendees a JOIN bookings b ON b.id=a.booking_id WHERE b.status='confirmed') att,(SELECT COUNT(*) FROM categories) cat")->fetch();
$f=$pdo->query(EV_SQL." WHERE e.status='published' AND e.start_at>NOW() AND e.featured=1 ORDER BY e.start_at LIMIT 3")->fetchAll();
$u=$pdo->query(EV_SQL." WHERE e.status='published' AND e.start_at>NOW() ORDER BY e.start_at LIMIT 6")->fetchAll();
$cats=$pdo->query("SELECT * FROM categories")->fetchAll();?>
<section class="hero text-center mb-4"><h1>Discover Experiences. Create Memories.</h1><p>Find events, book tickets and get your digital QR ticket.</p>
<form action="events.php" class="row g-2 justify-content-center"><div class="col-md-4"><input name="q" class="form-control" placeholder="Event name"></div><div class="col-md-3"><input name="city" class="form-control" placeholder="City"></div><div class="col-md-2"><button class="btn btn-light w-100">Search</button></div></form>
<p class="mt-3 mb-0"><b><?=$s['ev']?></b> events · <b><?=$s['att']?></b> attendees · <b><?=$s['cat']?></b> categories</p></section>
<h3>Featured Events</h3><div class="row g-3 mb-4"><?php foreach($f as $ev)echo card($ev); if(!$f)echo '<p class="text-muted">No featured events yet.</p>';?></div>
<h3>Browse Categories</h3><p><?php foreach($cats as $c)echo '<a class="btn btn-outline-primary btn-sm m-1" href="events.php?category='.$c['id'].'">'.e($c['name']).'</a>';?></p>
<h3>Upcoming Events</h3><div class="row g-3"><?php foreach($u as $ev)echo card($ev);?></div>
<?php require 'includes/footer.php';
