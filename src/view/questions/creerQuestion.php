<body>
    <div class="page-container flex-col">
        <form method="POST" action="frontController.php?controller=question&action=created">
            <div class='top-div'>
                <h1 class='page-title'>Créer votre question</h1>
                <div class="title-div">
                    <h3>Titre : </h3>
                    <input  class='text text-title' type="text" name="titre" id="titre" placeholder="" required="true" >
                    <div class="private-check flex-row">
                        <input class="checkInput" type="checkbox" name="isPrivate" id="">
                        Privée
                    </div>
                </div>
                <div class='txt-area'>
                    <h3> Description : </h3>
                    <textarea required="true"   class='text' name="description" id="desc" cols="30" rows="10" required="true" ></textarea>
                </div>
                <div class="ppl-container flex-col">
                    <h3> Collaborateurs : </h3>
                    <div class="flex-row" style="position: relative;">
                        <div class="flex-row" id="collaborateurs" ></div>
                        <div class="blue-round add-button" id="add-clb">+</div>
                        <div class="search-container" id="search-container-clb">
                            <input class="search-input" id="search-clb" type="text" placeholder="pseudo">
                            <div class="suggestions" id="suggestions-clb" ></div>
                        </div>
                    </div>
                </div>
                <div class="ppl-container flex-col">
                    <h3> Votant : </h3>
                    <div class="flex-row" style="position: relative;">
                        <div class="flex-row" id="votants" ></div>
                        <div class="blue-round add-button" id="add-vt">+</div>
                        <div class="search-container" id="search-container-vt">
                            <input class="search-input" id="search-vt" type="text" placeholder="pseudo">
                            <div class="suggestions" id="suggestions-vt" ></div>
                        </div>
                    </div>
                </div>
                <div class="date-container flex-col">
                    <div class="date ">
                        <h3>Date période des réponse</h3>
                        <div class="date-info flex-row">
                            <p>Debut</p>
                            <input type="date" <?php $dat = date('Y-m-d');
                                echo "min=\"$dat\""?> name="dateDebutReponse" class="date-input" required="true" >
                            <p>Fin</p>
                            <input type="date" <?php $dat = date('Y-m-d');
                                echo "min=\"$dat\""?> name="dateFinReponse" class="date-input" required="true" >
                        </div>
                    </div>
                    <div class="date">
                        <h3>Date période des votes</h3>
                        <div class="date-info flex-row">
                            <p>Debut</p>
                            <input type="date" <?php $dat = date('Y-m-d');
                                echo "min=\"$dat\""?> name="dateDebutVote" class="date-input" required="true" >
                            <p>Fin</p>
                            <input type="date" <?php $dat = date('Y-m-d');
                                echo "min=\"$dat\""?> name="dateFinVote" class="date-input" required="true" >
                        </div>
                    </div>
                </div>
            </div>
            <div id="sec-container" class='bot-div '>
                <div class='txt-area flex-col'>
                    <input class="sec-title input" type="text" name="sections[0][titre]" placeholder="Titre de la section..">
                    <textarea required="true"   class='text' name="sections[0][contenu]" id="intro" cols="30" rows="10"></textarea>
                </div>
                <div class='txt-area flex-col'>
                    <input class="sec-title input" type="text" name="sections[1][titre]" placeholder="Titre de la section..">
                    <textarea required="true"  class='text' name="sections[1][contenu]" id="sect1" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div id="sec-add-btn" class='blue-round'> Section +</div>
        <div class="footer">
          <button class='blue-round btn big' type="submit"> Publier </button>
        </div>
    </form>
    </div>
    
</body>
