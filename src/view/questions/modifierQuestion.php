<body>

<div class="page-container flex-col">
        <form method="POST" action="frontController.php?controller=question&action=updated&id=<?= $question->getId()?>" >
            
            <div class='top-div'>
                <h1 class='page-title'>Modifier votre question</h1>

                <div class="title-div">
                    <h3>Titre : </h3>
                    <input required="true"   class='text text-title' type="text" name="titre" id="titre" value="<?= $question->getTitre() ?>">
                    <!-- Mettre une ternaire pour afficher l'inverse de la visibilité de la question  -->
                    <div class="private-check flex-row"> <input required="true"  class="checkInput" type="checkbox" name="isPrivate" id="" checked>Privée</div>
                </div>
                <div class='txt-area'>
                    <h3> Description : </h3>
                    <textarea required="true"   class='text' name="description" id="desc" cols="30" rows="10" > <?= $question->getDescription() ?> </textarea>
                </div>
                <div class="ppl-container flex-col">
                    <h3> Collaborateurs : </h3>
                    <div class="flex-row">
                        <div class="blue-round"> collaborateur 1</div>
                        <div class="blue-round"> collaborateur 2</div>
                        <div class="blue-round add-button"> + </div>
                    </div>
                </div>
                <div class="ppl-container flex-col">
                    <h3> Votants : </h3>
                    <div class="flex-row">
                        <div class="blue-round"> votant 1</div>
                        <div class="blue-round"> votant 2</div>
                        <div class="blue-round add-button"> + </div>
                    </div>
                </div>
                <div class="date-container flex-col">
                    <div class="date ">
                        <h3>Date période des réponse</h3>
                        <div class="date-info flex-row">
                            <p>Debut</p>
                            <input required="true"  type="date" name="dateDebutReponse" class="date-input" value="<?= $question->getDateDebutReponse() ?>">
                            <p>Fin</p>
                            <input required="true"  type="date" name="dateFinReponse" class="date-input" value="<?= $question->getDateFinReponse() ?>">
                        </div>
                    </div>
                    <div class="date">
                        <h3>Date période des votes</h3>
                        <div class="date-info flex-row">
                            <p>Debut</p>
                            <input required="true"  type="date" name="dateDebutVote" class="date-input" value="<?= $question->getDateDebutVote() ?>">
                            <p>Fin</p>
                            <input required="true"  type="date" name="dateFinVote" class="date-input" value="<?= $question->getDateFinVote() ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class='bot-div '>

                 <?php
                  $i = 0;
                  foreach($sections as $section): ?>
                    <div class='txt-area flex-col'>
                        <input required="true"  type="hidden" name="sections[<?=$i?>][id]" value="<?= $section->getId()  ?>">
                        <input required="true"  class="sec-title input" type="text" name="sections[<?=$i?>][titre]" value="<?= $section->getTitre()?>">
                        <textarea required="true"   class='text' name="sections[<?=$i?>][contenu]" id="intro" cols="30" rows="10"><?= $section->getContenu()?></textarea>
                    </div>
                    <?php $i++; ?>
                <?php endforeach ?> 

               
                <div class='blue-round'> Section +</div>
            </div>
        </div>
        <div class="footer">
            <button class='blue-round btn big' type="submit"> Modifier </button>
        </div>
    </form>
</body>