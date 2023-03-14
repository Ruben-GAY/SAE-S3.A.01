const sectionsHandler = () => {
	const secAddBtn = document.getElementById('sec-add-btn');
	const secContainer = document.getElementById('sec-container');
	let secCount = 2;

	const existingDltSecButtons = document.querySelectorAll('.delete-sec');
	existingDltSecButtons.forEach(button => {
		button.addEventListener('click', e => {
			e.target.parentElement.remove();
			console.log("remove");
		});
	});

	

	secAddBtn.addEventListener('click', e => {
		if (secCount < 10) {
			secCount++;
			const newSec = document.createElement('div');
			newSec.classList.add('txt-area', 'flex-col');
			newSec.innerHTML = `<input class="sec-title input" type="text" name="sections[${secCount}][titre]" placeholder="Titre de la section..">
			<div class="delete-sec" id="${secCount}-delete-sec">X</div>
            <textarea required="true"   class='text' name="sections[${secCount}][contenu]" id="intro" cols="30" rows="10"></textarea>`;
			secContainer.appendChild(newSec);
			console.log("testg", `${secCount}-delete-sec`);
			

			const dlt = document.getElementById(`${secCount}-delete-sec`);
			dlt.addEventListener('click', e => {
				e.target.parentElement.remove();
				console.log("remove");
			});			
		}
		if (secCount == 10) {
			secAddBtn.remove();
		}
	});


	
};

sectionsHandler();
