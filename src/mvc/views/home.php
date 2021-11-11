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
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<h1><i class="bi bi-binoculars"></i> Search for DigiAssets...</h1>
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="input-group mb-3">
						<input type="text" class="form-control" placeholder="Search for DigiAsset ID" id="search" required>
						<button onclick="search()" class="btn btn-success"><i class="bi bi-search"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h3><i class="bi bi-info-circle"></i> What is a DigiAsset?</h3>
				<p>
					DigiAssets is a secure, scalable layer on top of the <a class="link" href="https://digibyte.org/" target="_blank">DigiByte Blockchain</a> that allows for the decentralized issuance of digital assets, tokens, smart contracts, digital identity and more.
				</p>
			</div>
			<div class="col-md-6">
				<h3><i class="bi bi-diagram-2"></i> Run a DigiAsset Node</h3>
				<p>
					DigiAsset v3 is a public and descentralized protocol. You can run a DigiAsset node in your own computer. Visit <a class="link" href="https://ipfs.digiassetx.com/" target="_blank">DigiAssetsX</a> to get a real-time node map.
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h3><i class="bi bi-box"></i> Is the data stored in a blockchain?</h3>
				<p>
					No, with DigiAssets v3 the network uses the <a class="link" href="https://ipfs.io/" target="_blank">IFPS Protocol</a> to store the metaDada asocieate with every asset. Only the information hash is stored in the blockchain, you can read more about it in the <a class="link" href="https://github.com/DigiByte-Core/DigiAssets-Protocol-Specifications/wiki" target="_blank">DigiAsset Protocol GitHub</a>.
				</p>
			</div>
			<div class="col-md-6">
				<h3><i class="bi bi-cpu"></i> Do I need a good PC to run a node?</h3>
				<p>
					No, the hardware required is very light and the software is click and go. Visit <a class="link" href="https://ipfs.digiassetx.com/#install" target="_blank">DigiAssetsX</a> for more information about the setup.
				</p>
			</div>
		</div>
	</main>

	<?php require __VIEW__ . "/.parts/page/footer.php"; ?>

	<script src="/assets/vendor/jquery/jquery.js"></script>
	<script src="/assets/vendor/bootstrap/bootstrap.js"></script>
	<script src="/assets/js/main.js"></script>
</body>

</html>