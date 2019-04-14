<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Espace admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="public/css/fonts.css" rel="stylesheet" />
        <link href="public/css/backend.css" rel="stylesheet" /> 
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    </head>
    <body>
        <div id="container">
            <!-- header -->
            <header>
                <h1>Administration</h1>
                <nav>
                    <div id="btn_site"><a href="index.php" target="_blank">Aller sur le site</a></div>
                    <div id="btn_logout"><a href="admin.php?action=logout"><i class="fas fa-times"></i></a></div>
                </nav>
            </header>

            <!-- editor TinyMCE -->
            <form action="<?= $formAction ?>" method="post" class="admin_post_form add_post">
                <input type="text" class="admin_post_title" name="post_title" placeholder="Saisissez votre titre ici" value="<?= htmlspecialchars($formTitle) ?>" />
                <input type="text" class="admin_post_place" name="post_place" placeholder="Lieu de l'événement" value="<?= htmlspecialchars($formPlace) ?>" />
                <input type="text" class="admin_post_address" name="post_address" placeholder="Adresse" value="<?= htmlspecialchars($formAddress) ?>" />
                <input type="datetime-local" class="admin_post_date" name="post_date" value="<?= htmlspecialchars($formDate) ?>" />
                <textarea id="post_content" name="post_content" ><?= $formContent ?></textarea>
                <?php 
				while ($data = $categories->fetch()) 
				{
				?>
				<div class="col-lg-6">
					<input type="checkbox" id="<?= $data['category'] ?>" name="category_id[]" value="<?= $data['id'] ?>">
					<label for="<?= $data['category'] ?>"><?= $data['category'] ?></label>
				</div>
				<?php
				}
				?>
                <button type="submit" id="btn_post">Envoyer</button>
                <button type="button" id="btn_cancel">Annuler</button>
            </form>

            <!-- top influencers list -->
            <section id="top_influencers_container">
            <?php
            while ($data = $topInfluencers->fetch())
            {
            ?>
                <div class="top_influencer">
                    <div class="profile_picture">
                        <a href="http://www.instagram.com/<?= htmlspecialchars($data['username']) ?>" target="_blank"><img src="<?= $data['profile_picture'] ?>" alt="Profile picture" /></a>
                    </div>
                    <div class="username"><a href="http://www.instagram.com/<?= htmlspecialchars($data['username']) ?>" target="_blank"><?= htmlspecialchars($data['username']) ?></a></div>
                </div>
            <?php
            }
            $topInfluencers->closeCursor();
            ?> 
            </section>

            <!-- posts/comments list -->
            <section id="posts_container">
            
            <?php
            while ($data = $posts->fetch())
            {
            ?>
                <div class="post">
                    <div class="post_header">
                        <div class="post_details">
                            <div class="picto_cat"><?= $picto_cat ?></div>
                            <div class="title"><?= $data['title'] ?></div>
                            <div class="post_date">Posté le <?= $data['creation_date_fr'] ?></div>
                        </div>
                        <div class="control">
                            <div class="control_btn"><i class="fas fa-ellipsis-v"></i></div>
                            <div class="control_list">
                            <ul>
                                <li class="edit_control"><a href="admin.php?action=editPost&amp;id=<?= $data['id'] ?>"><i class="fas fa-edit"></i>Éditer</a></li>
                                <li class="delete_control"><a href="admin.php?action=deletePost&amp;id=<?= $data['id'] ?>"><i class="fas fa-times"></i>Supprimer</a></li>
                            </ul>
                        </div>
                        </div>   
                    </div>

                    <div class="post_body">
                        <div class="post_content"><?= $data['content'] ?></div>
                    </div>
                    <div class="post_event_details">
                        <div class="date"><i class="far fa-clock"></i><?= $data['date_event'] ?></div>
                        <div class="place"><i class="fas fa-map-marker-alt"></i><?= $data['place'] ?> - <?= $data['address_event'] ?></div>
                    </div>
                </div>
            <?php
            }
            $posts->closeCursor();
            ?> 

            </section>
        </div>

        <script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=nb0d4opnyr0m2r8cqzluz18pcienj856no2g6z1guc21ax20"></script>
        <script>
            tinymce.init({
                selector: '#post_content',
                plugins : 'advlist autolink link image code lists charmap print preview emoticons',
                selector: 'textarea', // change this value according to your HTML
                menubar: false,
                toolbar: [
                    'undo redo | styleselect | bold italic | alignleft aligncenter alignright | emoticons | image code',
                ],
                
                // without images_upload_url set, Upload tab won't show up
                images_upload_url: 'admin.php?action=uploadImage',
                
                // override default upload handler to simulate successful upload
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;
                
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', 'admin.php?action=uploadImage');
                
                    xhr.onload = function() {
                        var json;
                    
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                    
                        json = JSON.parse(xhr.responseText);
                    
                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                    
                        success(json.location);
                    };
                
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                
                    xhr.send(formData);
                },
                content_style: 'img {max-width: 150px; height: auto;}',
            });
        </script>
        <script src="https://code.jquery.com/jquery.min.js" ></script>
        <script src="public/js/backend.js"></script>
    </body>
</html>