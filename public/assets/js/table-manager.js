/*INIZIALIZZAZIONE CLICK E DBLCLICK SU RIGHE TABELLA
Il controller passa (con override del metodo view di BaseController) "route" che contiene la parte di URL da concatenare all'ID per il redirect (es: "fornitori", "clienti", "tipi", "versioni").
Le tabelle devono avere le righe con classe "data-row" e l'attributo "data-id" con l'ID da passare al redirect.
Nelle view composte (es. show con più tabelle), ogni <tr class="data-row"> può sovrascrivere la route globale
tramite l'attributo data-route="segmento/url". Se data-route è presente ma vuoto (""), il redirect è disabilitato.
Se data-route è assente, viene usata la window.route derivata automaticamente dal BaseController.
*/


const tableRows = document.querySelectorAll('.data-row');
const route = window.route || '';
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
        const baseUrlVal = window.baseUrl || '';
        // Se la riga ha data-route usa quello, altrimenti usa la route globale del controller.
        // data-route="" (stringa vuota) disabilita il redirect per quella tabella.
        const rowRoute = this.getAttribute('data-route');
        const targetRoute = (rowRoute !== null) ? rowRoute : route;
        if (!targetRoute) return;
        console.log("Redirecting to: " + targetRoute + "/" + selectedID);
        window.location.href = `${baseUrlVal}${targetRoute}/${selectedID}`;
    });
});