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
			<div class="row">
				<div class="col-md-8">
					<div class="mb-3">
						<h3>Asset Information:</h3>
						<table class="table table-borderless">
							<tbody>
								<tr>
									<th>
										<h5 class="fw-bold">DigiAsset ID:</h5>
									</th>
									<td>
										<h5 class="text-break"><?php echo $digiAsset->AssetID ?></h5>
									</td>
								</tr>
								<tr>
									<th>
										<h5 class="fw-bold">Issuance height:</h5>
									</th>
									<td>
										<h5><a class="link" target="_blank" href="<?php echo EXPLORER ?>/block/<?php echo $digiAsset->Height ?>"><?php echo $digiAsset->Height ?></a></h5>
									</td>
								</tr>
								<tr>
									<th>
										<h5 class="fw-bold">IPFS CIDs:</h5>
									</th>
									<td>
										<h5><?php echo sizeof($cids) ?></h5>
									</td>
								</tr>
								<tr>
									<th>
										<h5 class="fw-bold">Holders:</h5>
									</th>
									<td>
										<h5 id="asset-n-holders"></h5>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mb-3 text-center">
						<div class="mb-3">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" id="check-wrapper" onchange="setWrapper(this.checked)">
								<label class="form-check-label" for="check-wrapper">Complete image</label>
							</div>
						</div>
						<div class="img-wrapper img-thumbnail" id="wrapper">
							<img src="<?php echo $image ?>" class="img-fluid" id="asset-img" onerror="if (this.src != '/assets/img/logo.png') this.src = '/assets/img/logo.png';">
						</div>
						<script>
							function setWrapper(value) {
								var wrapper = document.getElementById("wrapper");
								if (!value)
									wrapper.className = "img-wrapper img-thumbnail";
								else
									wrapper.className = "img-thumbnail";
							}
						</script>
					</div>
				</div>
			</div>
			<div class="mb-3">
				<h3>Asset Metadata:</h3>
				<div class="row justify-content-center" id="asset-meta">
					<?php foreach ($cids as $ipfs) : ?>
						<?php if ($ipfs->Data != null) : ?>
							<div class="col-md-10">
								<span class="text-break">
									IPFS:
									<a class="link" href="https://ipfs.io/ipfs/$<?php echo $ipfs->CID ?>"><?php echo $ipfs->CID ?></a>
								</span>
								<pre class="code"><?php echo $ipfs->Data ?></pre>
							</div>
						<?php else : ?>
							<div class="text-center">
								<div class="spinner-border text-primary" id="holders-<?php echo $ipfs->CID ?>">
									<span class="visually-hidden">Loading...</span>
								</div>
							</div>
						<?php endif ?>
					<?php endforeach ?>
				</div>
			</div>
			<div class="mb-3">
				<h3>Asset Holders:</h3>
				<div class="row justify-content-center">
					<div class="col-md-10">
						<table class="table">
							<thead>
								<tr>
									<th scope="col">Address</th>
									<th scope="col">Quantity</th>
								</tr>
							</thead>
							<tbody id="asset-holders">
							</tbody>
						</table>
						<div class="text-center">
							<div class="spinner-border text-primary" id="holders-loading">
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
	<script src="/assets/js/asset.js"></script>
	<script>
		setTimeout(async () => {
			var ipfs = [
				<?php foreach ($cids as $ipfs) : ?>
					<?php if ($ipfs->Data == null) : ?> '<?php echo $ipfs->CID ?>',
					<?php endif ?>
				<?php endforeach ?>
			];
			FetchMetaData('<?php echo API ?>', ipfs);
			FetchHolders('<?php echo API ?>', '<?php echo $digiAsset->AssetID ?>');
		}, 1000);
	</script>
</body>

</html>