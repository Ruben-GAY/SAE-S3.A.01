<body>
    <form method="POST" action="frontController.php?controller=utilisateur&action=updated&id=<?=$utilisateur->getId()?>" >
        <div class="page-container">
            <div class="title-div">
                <h1 class='page-title'>Modifier votre profil</h1>
            </div>
            <div class="info"> Nom : <span><?=$utilisateur->getNom()?></span> </div>
            <div class="info"> Prénom : <span><?=$utilisateur->getPrenom()?></span></div>
            <div class="info"> Email : <span><?=$utilisateur->getEmail()?></span></div>
            <div class="info"> Date de naissance : <span><?=$utilisateur->getDateDeNaissance()?></span></div>
            <div class="info"> Pseudo : </div>
            <input  class='text text-title' type="text" name="titre" id="titre" value="<?=$utilisateur->getPseudo()?>">
            <div class="footer">
                <button class='blue-round btn big' type="submit"> Modifier </button>
            </div>
        </div>
    </form>
</body>