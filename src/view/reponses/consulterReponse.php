<body>

    <div class="page-container flex-col">
        <h3 class='page-title'> <?=$reponse->getTitre()?></h3>
        <div class="normal-text"> Auteur(s) : Geoffrey et Pierre</div>
            
            <?php foreach ($sections as $section): ?>
                <div class='info-question flex-col'>
                    <div class="infoQ-title"> <?=$section->getTitre()?>  </div>
                    <div class="contenu-question">
                        <?=$section->getContenu()?>
                    </div>
                </div>
            <?php endforeach?>
    </div>

    <footer>
    <button class="grey-btn rep"> <a href="frontController.php?controller=reponse&action=update&id=<?= $reponse->getId() ?>" > Modifier </a></button>
    </footer>
</body>