const collaborateurHandler = () => {
	const existingDltClbButtons = document.querySelectorAll('.delete-clb');
	existingDltClbButtons.forEach(button => {
		button.addEventListener('click', e => {
			e.target.parentElement.remove();
			updateExistingCollaborateursIds();
		});
	});

	const addClbButton = document.getElementById('add-clb');
	const searchInput = document.getElementById('search-clb');
	const collaborateursContainer = document.getElementById('collaborateurs');
	const suggestionsContainer = document.getElementById('suggestions-clb');
	const searchInputContainer = document.getElementById('search-container-clb');

	console.log(addClbButton);
	let existingCollaborateursIds = [];

	const updateExistingCollaborateursIds = () => {
		const collaborateurs = document.querySelectorAll('#collaborateurs > input');
		const newExistingCollaborateursIds = [];
		console.log(collaborateurs);
		collaborateurs.forEach(collaborateur => {
			const id = collaborateur.value;
			newExistingCollaborateursIds.push(id);
		});
		existingCollaborateursIds = newExistingCollaborateursIds;
	};
	updateExistingCollaborateursIds();
	console.log(existingCollaborateursIds);

	addClbButton.addEventListener('click', async e => {
		searchInputContainer.classList.toggle('active');
	});

	const newCollaborateurDOM = (id, username) => {
		const newCollaborateur = document.createElement('div');
		newCollaborateur.classList.add('collaborateur');
		newCollaborateur.innerHTML = `
            <input type="hidden" name="collaborateurs[${id}]"  value="${id}">
            <div class="blue-round">${username}</div>
			<div class="delete-clb" id="${id}-delete-clb">X</div>
        `;

		existingCollaborateursIds.push(id);
		return newCollaborateur;
	};

	const newSuggestionsDOM = (id, username) => {
		const newSuggestion = document.createElement('div');
		newSuggestion.classList.add('suggestion');
		newSuggestion.innerText = username;
		newSuggestion.addEventListener('click', e => {
			const newCollaborateur = newCollaborateurDOM(id, username);
			const collaborateursCtn = document.getElementById('collaborateurs');
			collaborateursCtn.appendChild(newCollaborateur);

			const deleteClbButton = document.getElementById(`${id}-delete-clb`);
			console.log(deleteClbButton);

			deleteClbButton.addEventListener('click', e => {
				e.target.parentElement.remove();
				updateExistingCollaborateursIds();
			});
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
			if (existingCollaborateursIds.includes('' + user.id)) return;

			const newSuggestion = newSuggestionsDOM(user.id, user.pseudo);
			suggestionsContainer.appendChild(newSuggestion);
		});
	});
};
collaborateurHandler();
