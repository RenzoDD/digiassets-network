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
		<div class="my-5">
			<div class="mb-3">
				<h3>Address Information:</h3>
				<table class="table table-borderless">
					<tbody>
						<tr>
							<th>
								<h5 class="fw-bold">Address:</h5>
							</th>
							<td>
								<h5 class="text-break"><?php echo $address ?></h5>
							</td>
						</tr>
						<tr>
							<th>
								<h5 class="fw-bold">Assets:</h5>
							</th>
							<td>
								<h5 id="asset-number">0</h5>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="mb-3">
				<h3>DigiAssets found:</h3>
				<div class="row justify-content-center">
					<div class="col-md-10">
						<table class="table">
							<thead>
								<tr>
									<th scope="col">AssetID</th>
									<th scope="col">Quantity</th>
								</tr>
							</thead>
							<tbody id="asset-list">
							</tbody>
						</table>
						<div class="text-center">
							<div class="spinner-border text-primary" id="assets-loading">
								<span class="visually-hidden">Loading...</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<?php require __VIEW__ . "/.parts/page/footer.php"; ?>

	<script src="/assets/vendor/jquery/jquery.js"></script>
	<script src="/assets/vendor/bootstrap/bootstrap.js"></script>
	<script src="/assets/js/address.js"></script>
	<script>
		FetchAssets('<?php echo API ?>', '<?php echo $address ?>')
	</script>
</body>

</html>