<!doctype html>
<html lang="en" class="h-100" prefix="og: http://ogp.me/ns#">

<head>
	<?php
	echo MetaHeaders($title, $description);
	echo GoogleAnalytics($pageName);
	?>

	<link href="/assets/vendor/bootstrap/bootstrap.css" rel="stylesheet">
	<link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="/assets/css/style.css" rel="stylesheet">

	<link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico" />
	<title><?php echo $title ?></title>
</head>

<body class="d-flex flex-column h-100">
	<?php require __VIEW__ . "/.parts/page/header.php"; ?>

	<main class="container my-3">
		<h2 class="text-center">
			Showing asets <b><?php echo (($page - 1) * $cant + 1) ?></b> to <b><?php echo ($page * $cant) ?></b> of <b><?php echo $quantity ?></b>
		</h2>
		<div class="my-3 text-center">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="list-group">
						<a href="#" class="list-group-item list-group-item-action active" aria-current="true">
							Page <?php echo $page ?>
						</a>
						<?php foreach ($digiAssets as $da) : ?>
							<a href="/asset/<?php echo $da->AssetID ?>" class="list-group-item list-group-item-action text-break"><?php echo $da->AssetID ?></a>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
		<nav>
			<?php $p = PaginationStart($page, 7, $pages) ?>
			<ul class="pagination justify-content-center">
				<li class="page-item <?php echo $page == 1 ? "disabled" : "" ?>">
					<a class="page-link" href="/assets/<?php echo $page - 1 ?>">Previous</a>
				</li>
				<?php for ($i = $p["start"]; $i <= $p["end"]; $i++) : ?>
					<li class="page-item <?php echo $i == $page ? "active" : "" ?>">
						<a class="page-link" href="/assets/<?php echo $i ?>">
							<?php echo $i; ?>
						</a>
					</li>
				<?php endfor ?>
				<li class="page-item <?php echo $page == $pages ? "disabled" : "" ?>">
					<a class="page-link" href="/assets/<?php echo $page + 1 ?>">Next</a>
				</li>
			</ul>
		</nav>
	</main>

	<?php require __VIEW__ . "/.parts/page/footer.php"; ?>

	<script src="/assets/vendor/jquery/jquery.js"></script>
	<script src="/assets/vendor/bootstrap/bootstrap.js"></script>
	<script src="/assets/js/main.js"></script>
</body>

</html>