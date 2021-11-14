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
		<h1 class="text-center">
			<i class="bi bi-flag"></i> About Me <i class="bi bi-flag"></i>
		</h1>
		<div class="row">
			<div class="col-md-6 mb-3">
				<h3 class="text-center mb-3">Who are you?</h3>
				<p>
					I'm a 20-year-old student from Peru. I'm relatively new in the crypto-world and I love programming so, why not combine this two hobbies right?
				</p>
				<p>
					As you may notice, I like to publish my projects, so you can check my <a href="https://www.github.com/RenzoDD" target="_blank" class='link'><i class="bi bi-github"></i> GitHub</a> or visit my <a href="https://www.remadi.net/" target="_blank" class='link'><i class="bi bi-globe"></i> WebSite</a> to find other tech-like projects that you may like.
				</p>
				<p>
					I hope this project will eventually help to the mass adoption of blockchain technology and DigiByte will be it's main character.
				</p>
				<p>
					And thats about it. Here is my <a href="https://www.reddit.com/user/ObjectiveAct4720" target="_blank" class='link'><i class="bi bi-reddit"></i> Reddit</a> and <a href="https://t.me/RenzoDD" target="_blank" class='link'><i class="bi bi-telegram"></i> Telegram</a> if you want to get in touch with me to say hi or give me any suggestion for this or any of my projects. This is my profile and this is me, welcome to my page and I hope you can enjoy using it as much as I enjoyed coding it.
				</p>

				<div class="alert alert-danger mb-3">
					<h4 class="alert-heading"><i class="bi bi-bug-fill"></i> Did you find a bug?</h4>
					<p>Send us an email and we will try to fix it as soon as possible.</p>
					<hr>
					<p class="mb-0 text-center"><i class="bi bi-envelope-fill"></i> <a href="mailto:support@digiassets.network" class="alert-link">support@digiassets.network</a></p>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<h3 class="text-center mb-3">How can I support you?</h3>
				<p>
					As this project is about crypto, here is my personal DigiByte address. It's from a paper wallet I have printed some months ago. I promise, I will not withdraw the funds in a long time.
				</p>
				<p>
					If you liked this project, please consider donating, it makes me see how much impact it has had within the community.
				</p>
				<div class="row justify-content-center mb-3">
					<div class='col-7 col-sm-7 col-md-11 col-lg-9 col-xl-6 text-center mb-3'>
						<?php $address = WALLETS[0]["ADDRESS"] ?>
						<label>Personal address</label>
						<a href="<?php echo "digibyte:$address" ?>">
							<img src="<?php echo "/assets/img/addresses/$address.png" ?>" width="150px" class="d-block mx-auto">
						</a>
						<small style="margin-left: -100%; margin-right: -100%;">
							<a href="<?php echo "digibyte:$address" ?>" class="link">
								<?php echo $address ?>
							</a>
						</small>
						<div class="progress">
							<div id="<?php echo "$address-confirmed" ?>" class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
							<div id="<?php echo "$address-unconfirmed" ?>" class="progress-bar bg-warning" role="progressbar" style="width: 0%"></div>
						</div>
					</div>
				</div>

				<div class="text-center mb-3">
					<a class="btn btn-danger btn-sm mb-2" href="https://www.reddit.com/user/ObjectiveAct4720" target="_blank">
						<i class="bi bi-reddit"></i> DM on Reddit!
					</a>
					<a class="btn btn-primary btn-sm mb-2" href="https://www.t.me/RenzoDD" target="_blank">
						<i class="bi bi-telegram"></i> Say hi on Telegram!
					</a>
					<a class="btn btn-dark btn-sm mb-2" href="https://github.com/RenzoDD" target="_blank">
						<i class="bi bi-github"></i> Follow me on GitHub!
					</a>
				</div>
			</div>
		</div>
	</main>

	<?php require __VIEW__ . "/.parts/page/footer.php"; ?>

	<script src="/assets/vendor/jquery/jquery.js"></script>
	<script src="/assets/vendor/bootstrap/bootstrap.js"></script>
	<script src="/assets/js/main.js"></script>

	<script>
		const url = '<?php echo API ?>';
		const addresses = [
			<?php for ($i = 0; $i < sizeof(WALLETS); $i++) : ?> {
					confirmed: -1,
					unconfirmed: -1,
					max: <?php echo WALLETS[$i]["MAX"] ?>,
					addr: '<?php echo WALLETS[$i]["ADDRESS"] ?>',
					unit: '<?php echo WALLETS[$i]["UNIT"] ?>'
				},
			<?php endfor ?>
		];
		var time = <?php echo (isset($time) ? $time : 0) ?>;
	</script>
	<script src="/assets/js/api.js"></script>
</body>

</html>