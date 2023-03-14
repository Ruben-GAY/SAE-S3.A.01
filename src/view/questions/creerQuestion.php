<body>
<!--        $_POST['titre'],
            $_POST['description'],
            $_POST['dateDebutVote'],
            $_POST['dateFinVote'],
            $_POST['dateDebutReponse'],
            $_POST['dateFinReponse'], -->
    <div class="page-container flex-col">
        <form method="POST" action="frontController.php?controller=question&action=created" >
            <div class='top-div'>
                <h1 class='page-title'>Créer votre question</h1>

                <div class="title-div">
                    <h3>Titre : </h3>
                    <input  class='text text-title' type="text" name="titre" id="titre" placeholder="" required="true" >
                    <div class="private-check flex-row"> 
                        <input class="checkInput" type="checkbox" name="isPrivate" required="true" id="">
                        Privée
                    </div>
                </div>
                <div class='txt-area'>
                    <h3> Description : </h3>
                    <textarea required="true"   class='text' name="description" id="desc" cols="30" rows="10" required="true" ></textarea>
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
                            <input type="date" name="dateDebutReponse" class="date-input" required="true" >
                            <p>Fin</p>
                            <input type="date" name="dateFinReponse" class="date-input" required="true" >
                        </div>
                    </div>
                    <div class="date">
                        <h3>Date période des votes</h3>
                        <div class="date-info flex-row">
                            <p>Debut</p>
                            <input type="date" name="dateDebutVote" class="date-input" required="true" >
                            <p>Fin</p>
                            <input type="date" name="dateFinVote" class="date-input" required="true" >
                        </div>
                    </div>
                </div>
            </div>
            <div class='bot-div '>
                <div class='txt-area flex-col'>
                    <input class="sec-title input" type="text" name="sections[0][titre]" placeholder="Titre de la section..">
                    <textarea required="true"   class='text' name="sections[0][contenu]" id="intro" cols="30" rows="10"></textarea>
                </div>
                <div class='txt-area flex-col'>
                    <input class="sec-title input" type="text" name="sections[1][titre]" placeholder="Titre de la section..">
                    <textarea required="true"  class='text' name="sections[1][contenu]" id="sect1" cols="30" rows="10"></textarea>

                </div>
                <div class='blue-round'> Section +</div>
            </div>
        </div>
        <div class="footer">
            <button class='blue-round btn big' type="submit"> Publier </button>
        </div>
    </form>
</body>
