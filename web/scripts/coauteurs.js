const coauteurHandler = () => {
	const addClbButton = document.getElementById('add-ct');
	const searchInput = document.getElementById('search-ct');
	const coauteurContainer = document.getElementById('coauteurs');
	const suggestionsContainer = document.getElementById('suggestions-ct');
	const searchInputContainer = document.getElementById('search-container-ct');

	console.log(addClbButton);

	let existingCoauteursIds = [];

	const updateExistingCoauteursIds = () => {
		const collaborateurs = document.querySelectorAll('#collaborateurs > input');
		const newExistingCoauteursIds = [];
		console.log(collaborateurs);
		collaborateurs.forEach(collaborateur => {
			const id = collaborateur.value;
			newExistingCoauteursIds.push(id);
		});
		existingCoauteursIds = newExistingCoauteursIds;
	};

	updateExistingCoauteursIds();
	console.log(existingCoauteursIds);

	addClbButton.addEventListener('click', async e => {
		searchInputContainer.classList.toggle('active');
	});

	const newCoauteurDOM = (id, username) => {
		const newCoauteur = document.createElement('div');
		newCoauteur.classList.add('coauteur');
		newCoauteur.innerHTML = `
            <input type="hidden" name="coauteurs[${id}]"  value="${id}">
            <div class="blue-round" id="">${username}</div>
			<div class="delete-ct" id="${id}-delete-ct">X</div>
        `;
		existingCoauteursIds.push(id);
		return newCoauteur;
	};

	const newSuggestionsDOM = (id, username) => {
		const newSuggestion = document.createElement('div');
		newSuggestion.classList.add('suggestion');
		newSuggestion.innerText = username;
		newSuggestion.addEventListener('click', e => {
			const newCoauteur = newCoauteurDOM(id, username);
			coauteurContainer.appendChild(newCoauteur);
			e.target.remove();
		});
		return newSuggestion;
	};

	searchInput.addEventListener('input', async e => {
		const username = e.target.value;
		if (username.length < 3) return (suggestionsContainer.innerHTML = '');
		const usersSearch = await fetch(
			`https://webinfo.iutmontp.univ-montp2.fr/~campsa/SAE-S3.A.01/web/frontController.php?controller=utilisateur&action=queryJSON&pseudo=${username}`
		);
		const usersSearchJson = await usersSearch.json();
		suggestionsContainer.innerHTML = '';
		console.log(usersSearchJson);
		usersSearchJson.forEach(user => {
			if (existingCoauteursIds.includes('' + user.id)) return;
			const newSuggestion = newSuggestionsDOM(user.id, user.pseudo);
			suggestionsContainer.appendChild(newSuggestion);
		});
	});

	coauteur?.forEach(c => {
		const newSuggestion = newSuggestionsDOM(c.id, c.pseudo);
		suggestionsContainer.appendChild(newSuggestion);
	});
};

coauteurHandler();
