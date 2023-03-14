<body>

<div class="page-container flex-col">
        <form method="POST" action="frontController.php?controller=reponse&action=updated&id=<?= $reponse->getId()?>" >
            <input type="hidden" name="id" value=" <?= $reponse->getId() ?>">
            <input type="hidden" name="idQuestion" value=" <?= $reponse->getIdQuestion() ?>">
            <div class='top-div'>
                <h1 class='page-title'>Modifier votre réponse</h1>

                <div class="title-div">
                    <h3>Titre : </h3>
                    <input  class='text text-title' type="text" name="titre" id="titre" value="<?= $reponse->getTitre() ?>">
                </div>
                
            <div class='bot-div '>

                 <?php
                  $i = 0;
                  foreach($sections as $section): ?>
                    <div class='txt-area flex-col'>
                        <input type="hidden" name="sections[<?=$i?>][id]" value="<?= $section->getId()  ?>">
                        <div class="infoQ-title"> <?= $section->getTitre() ?> </div>
                        <textarea required="true"   class='text' name="sections[<?=$i?>][contenu]" id="intro" cols="30" rows="10"><?= $section->getContenu()?></textarea>
                    </div>
                    <?php $i++; ?>
                <?php endforeach ?> 
            </div>
        </div>
        <div class="footer">
            <button class='blue-round btn big' type="submit"> Modifier </button>
        </div>
    </form>
</body>