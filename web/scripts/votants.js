const votantHandler = () => {
	const addVtButton = document.getElementById('add-vt');
	const searchInput = document.getElementById('search-vt');
	const votantContainer = document.getElementById('votants');
	const suggestionsContainer = document.getElementById('suggestions-vt');
	const searchInputContainer = document.getElementById('search-container-vt');

	const existingDltVtButtons = document.querySelectorAll('.delete-vt');
	existingDltVtButtons.forEach(button => {
		button.addEventListener('click', e => {
			e.target.parentElement.remove();
			updateExistingCollaborateursIds();
		});
	});

	console.log(addVtButton);

	let existingVotantsIds = [];
	const updateExistingVotantsIds = () => {
		const votant = document.querySelectorAll('#votant > input');
		const newExistingVotantsIds = [];
		console.log(votant);
		votant.forEach(votant => {
			const id = votant.value;
			newExistingVotantsIds.push(id);
		});
		existingVotantsIds = newExistingVotantsIds;
	};

	updateExistingVotantsIds();
	console.log(existingVotantsIds);

	addVtButton.addEventListener('click', async e => {
		searchInputContainer.classList.toggle('active');
	});

	const newVotantDOM = (id, username) => {
		const newVotant = document.createElement('div');
		newVotant.classList.add('votant');
		newVotant.innerHTML = `
            <input type="hidden" name="votants[${id}]"  value="${id}">
            <div class="blue-round" id="">${username}</div>
			<div class="delete-vt" id="${id}-delete-vt">X</div>
        `;
		existingVotantsIds.push(id);
		return newVotant;
	};

	const newSuggestionsDOM = (id, username) => {
		const newSuggestion = document.createElement('div');
		newSuggestion.classList.add('suggestion');
		newSuggestion.innerText = username;
		newSuggestion.addEventListener('click', e => {
			const newVotant = newVotantDOM(id, username);
			votantContainer.appendChild(newVotant);

			const deleteVtButton = document.getElementById(`${id}-delete-vt`);

			deleteVtButton.addEventListener('click', e => {
				e.target.parentElement.remove();
				updateExistingVotantsIds();
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
			if (existingVotantsIds.includes('' + user.id)) return;
			const newSuggestion = newSuggestionsDOM(user.id, user.pseudo);
			suggestionsContainer.appendChild(newSuggestion);
		});
	});
};

votantHandler();
