<body>

    <div class="page-container">
        <div class="page-title"> Utilisateurs </div>
        <div class="div-container">
            <form method="post" action="">
                <?php

                use App\Feurum\Model\HTTP\Session;

                foreach ($utilisateurs as $user) : ?>
                    <div class="user">
                        <input type="hidden" name="id" value=<?= $user->getId() ?>>
                        <div class="upper-info flex-row">
                            <div class="username"><?= $user->getPseudo() ?></div>
                            <div class="type">

                                <?php if ($user->getRole() == 1) : ?>
                                    Administrateur
                                    <a href="frontController.php?controller=utilisateur&action=switchRole&id=<?= $user->getId() ?>&role=2" class="role-link"> Organisateur </a>
                                    <a href="frontController.php?controller=utilisateur&action=switchRole&id=<?= $user->getId() ?>&role=aucun" class="role-link">Aucun</a>

                                <?php elseif ($user->getRole() == 2) : ?>
                                    <a class="role-link" href="frontController.php?controller=utilisateur&action=switchRole&id=<?= $user->getId() ?>&role=1">Administrateur</a>
                                    Organisateur
                                    <a class="role-link" href="frontController.php?controller=utilisateur&action=switchRole&id=<?= $user->getId() ?>&role=aucun">Aucun</a>

                                <?php else : ?>
                                    <a class="role-link" href="frontController.php?controller=utilisateur&action=switchRole&id=<?= $user->getId() ?>&role=1">Administrateur</a>
                                    <a class="role-link" href="frontController.php?controller=utilisateur&action=switchRole&id=<?= $user->getId() ?>&role=2">Organisateur </a>
                                    Aucun
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="user-mail">
                            <?= $user->getEmail() ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </form>
        </div>
    </div>


</body>