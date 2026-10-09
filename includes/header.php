<?php require_once __DIR__.'/functions.php'; ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title??'EVENTHUB')?> | EVENTHUB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top eh-nav"><div class="container"><a class="navbar-brand fw-bold" href="index.php">EVENTHUB</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nv" aria-label="Menu"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="nv"><ul class="navbar-nav me-auto"><li class="nav-item"><a class="nav-link" href="index.php">Home</a></li><li class="nav-item"><a class="nav-link" href="events.php">Explore Events</a></li><li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li></ul>
<form class="d-flex me-3" action="events.php"><input class="form-control form-control-sm" name="q" placeholder="Search events" aria-label="Search"></form>
<?php if(uid()):?><span class="text-white me-2"><?=e($_SESSION['name'])?></span><a class="btn btn-light btn-sm" href="logout.php">Logout</a>
<?php else:?><a class="btn btn-outline-light btn-sm me-2" href="login.php">Login</a><a class="btn btn-light btn-sm" href="register.php">Register</a><?php endif;?></div></div></nav>
<main class="container py-4"><?php if(!empty($_SESSION['flash'])){echo '<div class="alert alert-success">'.e($_SESSION['flash']).'</div>';unset($_SESSION['flash']);}?>
