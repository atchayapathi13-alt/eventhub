<?php $title='Explore Events'; require 'includes/header.php';
$w=["e.status='published'"];$a=[];
if(($q=trim($_GET['q']??''))!==''){$w[]='(e.title LIKE ? OR e.venue LIKE ? OR e.organizer LIKE ?)';array_push($a,"%$q%","%$q%","%$q%");}
if(!empty($_GET['category'])){$w[]='e.category_id=?';$a[]=(int)$_GET['category'];}
if(($city=trim($_GET['city']??''))!==''){$w[]='e.city LIKE ?';$a[]="%$city%";}
if(!empty($_GET['from'])){$w[]='DATE(e.start_at)>=?';$a[]=$_GET['from'];}
if(!empty($_GET['to'])){$w[]='DATE(e.start_at)<=?';$a[]=$_GET['to'];}
$pr=$_GET['price']??'';if($pr==='free')$w[]='(SELECT MIN(price) FROM ticket_types WHERE event_id=e.id)=0';if($pr==='paid')$w[]='(SELECT MIN(price) FROM ticket_types WHERE event_id=e.id)>0';
$orders=['date'=>'e.start_at','new'=>'e.id DESC','low'=>'min_price','high'=>'min_price DESC'];$sort=$orders[$_GET['sort']??'date']??'e.start_at';
$where=' WHERE '.implode(' AND ',$w);$per=6;$pg=max(1,(int)($_GET['page']??1));
$c=$pdo->prepare("SELECT COUNT(*) FROM events e$where");$c->execute($a);$n=(int)$c->fetchColumn();
$st=$pdo->prepare(EV_SQL."$where ORDER BY $sort LIMIT $per OFFSET ".(($pg-1)*$per));$st->execute($a);$rows=$st->fetchAll();
$cats=$pdo->query("SELECT * FROM categories")->fetchAll();$qs=$_GET;unset($qs['page']);?>
<form class="row g-2 mb-3"><div class="col-md-3"><input name="q" value="<?=e($q)?>" class="form-control" placeholder="Name, venue, organizer"></div>
<div class="col-md-2"><select name="category" class="form-select"><option value="">All categories</option><?php foreach($cats as $k)echo '<option value="'.$k['id'].'"'.(($_GET['category']??'')==$k['id']?' selected':'').'>'.e($k['name']).'</option>';?></select></div>
<div class="col-md-2"><input name="city" value="<?=e($city)?>" class="form-control" placeholder="City"></div>
<div class="col-md-2"><input type="date" name="from" value="<?=e($_GET['from']??'')?>" class="form-control"></div><div class="col-md-2"><input type="date" name="to" value="<?=e($_GET['to']??'')?>" class="form-control"></div>
<div class="col-md-2"><select name="price" class="form-select"><option value="">Any price</option><option value="free"<?=$pr==='free'?' selected':''?>>Free</option><option value="paid"<?=$pr==='paid'?' selected':''?>>Paid</option></select></div>
<div class="col-md-2"><select name="sort" class="form-select"><option value="date">Upcoming first</option><option value="new">Recently added</option><option value="low">Price low-high</option><option value="high">Price high-low</option></select></div>
<div class="col-md-3"><button class="btn btn-primary">Apply</button> <a class="btn btn-outline-secondary" href="events.php">Clear</a></div></form>
<p><?=$n?> result(s)</p><div class="row g-3"><?php foreach($rows as $ev)echo card($ev); if(!$rows)echo '<p>No events match. Try clearing filters or a different city.</p>';?></div>
<nav class="mt-4"><ul class="pagination"><?php for($i=1;$i<=max(1,ceil($n/$per));$i++)echo '<li class="page-item'.($i==$pg?' active':'').'"><a class="page-link" href="?'.e(http_build_query($qs+['page'=>$i])).'">'.$i.'</a></li>';?></ul></nav>
<?php require 'includes/footer.php';
