<!DOCTYPE html>
<html>
	<head>
		<title><?= $influencer['fullname'] ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="public/css/fonts.css" rel="stylesheet" />
		<link href="public/css/influencer.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>
		<div class="container">
			<div class="logout"><a href="influencers.php?action=logout">déconnexion</i></a></div>
			<img class="profile-picture" src="<?= $influencer['profile_picture'] ?>" alt="Profile picture" />
			<div class="info">
				Nom: <?= htmlspecialchars($influencer['fullname']) ?><br />
				Instagram: @<?= htmlspecialchars($influencer['username']) ?><br />
				Ville: <?= htmlspecialchars($influencer['town']) ?><br />
				Followers: <?= htmlspecialchars($influencer['followers']) ?><br />
				Age: <?= htmlspecialchars($influencer['age']) ?><br /><br />
			</div>
			
			<a href="influencers.php?action=editProfile&amp;id=<?= htmlspecialchars($influencer['id']) ?>">éditer</a>

			<form class="row comment_form" action="influencers.php?action=updateProfile&amp;id=<?= htmlspecialchars($influencer['id']) ?>" method="post">
				<!-- details -->
				<div class="col-lg-6">
				<label for="fullname">Nom:</label>
					<input type="text" id="fullname" name="fullname" placeholder="Nom" value="<?= htmlspecialchars($influencer['fullname']) ?>" />
				</div>
				<div class="col-lg-6">
					<label for="email">Email:</label>
					<input type="email" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($influencer['email']) ?>" />
				</div>
				<div class="form-group col-sm-6">
					<label for="start">Date de naissance:</label>
					<input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($influencer['birthdate']) ?>" />
				</div>
				<div class="col-lg-6">
					<label for="town">Ville:</label>
					<input type="text" id="town" name="town" placeholder="Ville" value="<?= htmlspecialchars($influencer['town']) ?>" />
				</div>


				<!-- categories -->
				<?php 
				while ($data = $categories->fetch()) 
				{
				?>
				<div class="col-lg-6">
					<input type="checkbox" id="<?= $data['category'] ?>" name="category_id[]" value="<?= $data['id'] ?>">
					<label for="<?= $data['category'] ?>">#<?= $data['category'] ?></label>
				</div>
				<?php
				}
				?>
				<div class="col-lg-6 btn_send_container">
					<button id="btn_send">mettre à jour</button>
				</div>
			</form>
			
			<!-- posts list -->
			<?php/*
            while ($post = $posts->fetch())
            {
			?>
                <div class="title"><?= $post['title'] ?></div>
				<div class="post_date">Posté le <?= $post['creation_date_fr'] ?></div>
				<div class="post_content"><?= $post['content'] ?></div>
				<div class="date"><i class="far fa-clock"></i><?= $post['date_event'] ?></div>
                <div class="place"><i class="fas fa-map-marker-alt"></i><?= $post['place'] ?> - <?= $post['address_event'] ?></div>
            <?php
            }
            $posts->closeCursor();
			*/?> 
			



		</div>

		<script src="https://code.jquery.com/jquery.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="public/js/influencer.js"></script>
	</body>
</html>