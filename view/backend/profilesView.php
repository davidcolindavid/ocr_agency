<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Espace admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="public/css/fonts.css" rel="stylesheet" />
        <link href="public/css/backend_profiles.css" rel="stylesheet" /> 
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

            <!-- categories list -->
            <div id="categories_container">
                <ul>
                    <li><a href="admin.php?action=influencers">Tous</a></li>
                    <?php 
                    while ($data = $categories->fetch()) 
                    {
                    ?>
                    <li><a href="admin.php?action=influencers&amp;cat=<?= $data['id'] ?>"><?= $data['category'] ?></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>

            <!-- top influencers list -->
            <section id="influencers_container">
            <?php
            while ($data = $influencers->fetch())
            {
            ?>
                <div class="influencer">
                    <div class="profile_picture">
                        <a href="http://www.instagram.com/<?= htmlspecialchars($data['username']) ?>" target="_blank"><img src="<?= $data['profile_picture'] ?>" alt="Profile picture" /></a>
                    </div>
                    <div class="username"><a href="http://www.instagram.com/<?= htmlspecialchars($data['username']) ?>" target="_blank"><?= htmlspecialchars($data['username']) ?></a></div>
                    <div class="fullname"><?= htmlspecialchars($data['fullname']) ?></div>
                    <div class="followers"><?= htmlspecialchars($data['followers']) ?></div>
                </div>
            <?php
            }
            $influencers->closeCursor();
            ?> 
            </section>


        </div>

        <script src="https://code.jquery.com/jquery.min.js" ></script>
        <script src="public/js/backend_profiles.js"></script>
    </body>
</html>