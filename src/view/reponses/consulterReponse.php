<?php 

use App\Feurum\Model\DataObject\Utilisateur;
use App\Feurum\Model\HTTP\Session;
use App\Feurum\Model\Repository\QuestionRepository;
use App\Feurum\Model\Repository\UtilisateurRepository;

?>

<body>

    <div class="page-container flex-col">
        <h3 class='page-title'> <?= $reponse->getTitre() ?></h3>
        <div class="normal-text"> Auteur(s) : <?= array_reduce($auteurs, function (?string $acc, mixed $auteur) {
                                                    return $acc . $auteur->getPseudo() . " ";
                                                }) ?>

            <?php foreach ($sections as $section) : ?>
                <div class='info-question flex-col'>
                    <div class="infoQ-title"> <?= $section->getTitre() ?> </div>
                    <div class="contenu-question">
                        <?= $section->getContenu() ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <footer>
            <?php
            if ($user && ($user->getRole() == "1" || UtilisateurRepository::hasRole($user->getId(), 2, $reponse->getId()))) {
        ?>
                <button class="grey-btn rep"> <a href="frontController.php?controller=reponse&action=update&id=<?= $reponse->getId() ?>"> Modifier </a></button>
                <button class="grey-btn rep" id="delete-btn"> <a href="frontController.php?controller=reponse&action=delete&id=<?= $reponse->getId() ?>"> Supprimer </a></button>

        <?php
            }
        ?>
        </footer>

        <script>
            const deleteBtn = document.getElementById("delete-btn");
            deleteBtn.addEventListener("click", () => {
                if (confirm("Voulez vous vraiment supprimer cette question ?")) {
                    window.location.href = "frontController.php?controller=reponse&action=delete&id=<?= $reponse->getId() ?>";
                }
            })
        </script>
</body>