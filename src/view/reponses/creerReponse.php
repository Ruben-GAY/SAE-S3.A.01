<body>

    <div class="page-container flex-col">
            <form method="POST" action="frontController.php?controller=reponse&action=created" >
                <input required="true"  type="hidden" name="idQuestion" value="<?= $question->getId() ?>" >
                
                <div class='top-div'>
                    <h1 class='page-title'>Créer votre réponse</h1>
                    <div class="title-div">
                    <h2> <?= $question->getTitre() ?> </h2>
                    </div>
                    <h3>Titre de votre réponse : </h3>
                    <input required="true"   class='text text-title' type="text" name="titre" id="titre" placeholder="">
                </div>

                <div class="ppl-container flex-col">
                <h3> Coauteur : </h3>
                    <div class="flex-row" style="position: relative;">
                        <div class="flex-row" id="coauteurs" ></div>
                        <div class="blue-round add-button" id="add-ct">+</div>
                        <div class="search-container" id="search-container-ct">
                            <input class="search-input" id="search-ct" type="text" placeholder="pseudo">
                            <div class="suggestions" id="suggestions-ct" ></div>
                        </div>
                    </div>
                </div>
                <?php foreach($sections as $i => $section): ?>
                    <div class='txt-area flex-col'>
                        <h3> <?= $section->getTitre()  ?> </h3>
                        <input required="true"  type="hidden" class="sec-title input" type="text" name="<?= "sections[$i][titre]" ?>" value="<?= $section->getTitre()?>">
                        <textarea required="true"   class='text' name=<?= "sections[$i][contenu]" ?> id="intro" cols="30" rows="10"></textarea>
                    </div>
                <?php endforeach ?> 
                <div class="footer">
                    <button class='blue-round btn big' type="submit"> Publier </button>
                </div>
            </form>
    </div>      
    
</body>