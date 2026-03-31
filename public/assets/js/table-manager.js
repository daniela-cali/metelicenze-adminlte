/*INIZIALIZZAZIONE CLICK E DBLCLICK SU RIGHE TABELLA
Il controller deve passare alla view una variabile "route" che contiene la parte di URL da concatenare all'ID per il redirect (es: "fornitori", "clienti", "tipi", "versioni")
le tabelle devono avere le righe con classe "data-row" e l'attributo "data-id" con l'ID da passare al redirect  
Per convenzione la tabella principale di ogni view è quella che ha le righe con classe "data-row" e ha id #primaryTable
*/


const tableRows = document.querySelectorAll('.data-row');
const route = "<?= $route ?>";
console.log("Route in JS: " + route);
let selectedID = null;
tableRows.forEach(row => {
    row.addEventListener('click', function () {
        tableRows.forEach(r => r.classList.remove('table-primary', 'selected'));
        selectedID = this.getAttribute('data-id');
        console.log("ID selezionato: " + selectedID);
        this.classList.add('table-primary', 'selected');
    });
    row.addEventListener('dblclick', function () {
        selectedID = this.getAttribute('data-id');
        const baseUrl = "<?= base_url() ?>";
        selectedID = this.getAttribute('data-id');
        console.log("Redirecting to ID: " + selectedID);
        alert("Redirecting to ID: " + baseUrl + route + "/" + selectedID); // Debug alert
        window.location.href = `${baseUrl}/${route}/${selectedID}`;
    });
});