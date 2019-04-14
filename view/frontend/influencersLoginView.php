<!DOCTYPE html>
<html>
	<head>
		<title>Enregistrement</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="public/css/fonts.css" rel="stylesheet" />
		<link href="public/css/influencer.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
				<!-- login -->
				<div id="login_container" class="col-lg-5 offset-lg-1">
					<h2>Connexion</h2>
					<form action="influencers_login.php?action=getLoginURL" method="post">
						<button type="submit" id="btn_login" class="btn btn-primary">Se connecter avec instagram</button>
					</form>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="public/js/influencer.js"></script>
	</body>
</html>