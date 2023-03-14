<body>
    
    <div class="profile-page">
        <div class="profile">
            <div class="white-block">
                <div class="top-profil">
                    <div class="page-title">Profil : </div>
                    <!-- rajouter un if pour afficher le type de compte -->
                    <div class="user-type">Compte <?php
                                if ($utilisateur->getRole() == 1) {
                                    echo "Administrateur";
                                } else if ($utilisateur->getRole() == 2) {
                                    echo "Organisateur";
                                } else {
                                    echo "sans doit";
                                }
                                ?> </div>
                </div>
                <div class="user-info flex-col">
                    <div class="info"> Nom : <span><?= $utilisateur->getNom() ?></span> </div>
                    <div class="info"> Prénom : <span><?= $utilisateur->getPrenom() ?></span></div>
                    <div class="info"> Pseudo : <span><?= $utilisateur->getPseudo() ?></span></div>
                    <div class="info"> Email : <span> <?= $utilisateur->getEmail() ?></span></div>
                    <div class="info"> Date de naissance : <span><?= $utilisateur->getDateDeNaissance() ?></span></div>
                </div>
                <div class="profile-btn flex-row">
                    <a class="blue-round" href="frontController.php?controller=utilisateur&action=update">Modifier</a>
                    <a class="blue-round deco" href="frontController.php?controller=utilisateur&action=logout">Déconexion</a>
                </div>
            </div>
        </div>
        </div>
    
</body>