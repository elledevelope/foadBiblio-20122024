console.log("connect");

const formSearch = document.forms.search;
console.log(url);

formSearch.addEventListener('submit', (e) => {
    e.preventDefault();

    const valeur = formSearch.valeur.value.trim();  //strip extra spaces
    const champ = formSearch.champ.value;
    console.log(valeur, champ);

    if (valeur) {
        const url = formSearch.action;

        //search query URL
        fetch(`${url}?valeur=${valeur}&champ=${champ}`)
            .then(response => response.json())
            .then(result => {
                console.log("Search result:", result);

                if (result.length > 0) {
                    // display results in a list
                    let resultHtml = '<ul>';
                    result.forEach(item => {
                        resultHtml += `<li><strong>${item.title}</strong> - ${item.auteur}</li>`;
                    });
                    resultHtml += '</ul>';
                    document.getElementById('results').innerHTML = resultHtml;
                } else {
                    document.getElementById('results').innerHTML = "<p>Aucun résultat trouvé.</p>";
                }
            })

    } else {
        alert("Veuillez saisir une valeur pour rechercher.");
    }
});
