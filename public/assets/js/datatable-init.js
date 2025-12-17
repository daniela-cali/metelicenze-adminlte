const datatableDefaults = {

    responsive: true,
    paging: true,
    lengthMenu: [25, 50, 100, { label: 'Tutti', value: -1 }],
    //Disabilito ordinamento per ID
    order: [],
    language: {
        url: baseUrl + 'assets/datatables/i18n/it-IT.json',

    },
    buttons: [
        {
            extend: 'copy',
            text: '<i class="bi bi-clipboard"></i> Copia',
            className: 'btn btn-sm btn-outline-primary',
            exportOptions: { columns: ':not(.notexport)' }
        },
        {
            extend: 'csv',
            text: '<i class="bi bi-filetype-csv"></i> CSV',
            className: 'btn btn-sm btn-outline-success',
            exportOptions: { columns: ':not(.notexport)' }
        },
        {
            extend: 'excel',
            text: '<i class="bi bi-file-earmark-excel"></i> Excel',
            className: 'btn btn-sm btn-outline-success',
            exportOptions: { columns: ':not(.notexport)' }
        },
        {
            extend: "pdf",
            text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
            className: 'btn btn-sm btn-outline-danger',
            exportOptions: {
                columns: ':not(.notexport)',
                format: {
                    body: function (data, row, col, node) {
                        const isDanger = $(node).closest('tr').hasClass('table-danger');
                        // metto un marker solo nella prima colonna (col 0) per riconoscere la riga
                        if (isDanger && col === 0) return '__DANGER__' + data;
                        return $(node).text().trim();
                    }
                }
            },

            customize: function (doc) {
                const body = doc.content[1].table.body; // di solito qui c'è la tabella

                for (let i = 1; i < body.length; i++) { // i=1 per saltare header
                    const row = body[i];
                    const first = row[0];

                    if (first && typeof first.text === 'string' && first.text.startsWith('__DANGER__')) {
                        // rimuovo marker dalla prima cella
                        first.text = first.text.replace('__DANGER__', '');

                        // coloro tutta la riga (colore simile a Bootstrap table-danger)
                        row.forEach(cell => {
                            cell.fillColor = '#f8d7da';
                        });
                    }
                }
            }
        }
        ,


        {
            extend: 'print',
            text: '<i class="bi bi-printer"></i> Stampa',
            className: 'btn btn-sm btn-outline-secondary',
            exportOptions: { columns: ':not(.notexport)' }
        }

    ],
    layout: {
        topStart: 'buttons',
        topEnd: 'search',
        bottomStart: ['info', 'pageLength'],
        bottomEnd: 'paging'
    }


};


$(document).ready(function () {
    var table = $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: "pdfHtml5",
                customize: function (doc) {
                    for (var i = 1; i < doc.content[1].table.body.length; i++) {
                        if (doc.content[1].table.body[i][2].text === 'London') {
                            doc.content[1].table.body[i].forEach(function (h) {
                                h.fillColor = 'red';
                            });
                        }
                    }
                }
            }
        ]
    });
});
