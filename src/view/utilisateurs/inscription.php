<body>
    <div class="page-container" >
        <form method="post" action="frontController.php?controller=utilisateur&action=registered" >
            <h1 class="page-title"> Créer un compte </h1>
            <div class="account-info">
                <div class="info-box flex-col">
                    <label for="Nom">Nom : </label>
                    <input type="text" name="nom" class="input" placeholder="Nom...">
                </div>

                <div class="info-box flex-col">
                    <label for="Prenom">Prénom :</label>
                    <input type="text"  name="prenom" class="input" placeholder="Prénom...">
                </div>

                <div class="info-box flex-col">
                    <label for="Pseudo">Pseudo :</label>
                    <input type="text"  name="pseudo" class="input" placeholder="Pseudo...">
                </div>

                <div class="date flex-col">
                    <div class="date">
                        <h3>Date de naissance</h3>
                        <div class="date-info flex-row">
                            
                            <input type="date" name="date_de_naissance" class="date-input">
                            
                        </div>
                    </div>
                </div>
                <div class="info-box flex-col">
                    <label for="Pseudo">Email :</label>
                    <input type="text" name="email"  class="input" placeholder="email...">
                </div>
                <div class="info-box flex-col">
                    <label for="Pseudo">Mot de passe :</label>
                    <input type="password" name="mot_de_passe" class="input" placeholder="Mot de passe...">
                </div>
                <div class="info-box flex-col">
                    <label for="Pseudo">Confirmer mot de passe :</label>
                    <input type="password" name="mot_de_passe_confirm"  class="input" placeholder="">
                </div>
                <button class='blue-round btn big' type="submit"> S'inscrire </button>
            </div>
        </form>
        <div class="redirect-link">
            Vous avez déjà un compte ? <a href="frontController.php?controller=utilisateur&action=login">Connectez-vous</a>
        </div>
    </div>
</body>