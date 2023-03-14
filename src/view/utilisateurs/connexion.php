<body>
    <div class="page-container">
        <form method="POST" action="frontController.php?controller=utilisateur&action=logged">
            <h1 class="page-title">Se connecter</h1>
        <div class="info-box flex-col">
            <label>Pseudo: </label>
            <input required='true' type="text" name="pseudo" class="input">
        </div>
        <div class="flex-col info-box">
            <label>Mot de passe : </label>
            <input required='true' type="password"  name="mot_de_passe" class="input">
       
        </div>
        <button class='blue-round btn big'>Connexion</button>
        </form>

        <div class="redirect-link">
            Vous n'avez pas de compte ? <a href="frontController.php?controller=utilisateur&action=register">Inscrivez-vous</a>
        </div>
    </div>
</body>