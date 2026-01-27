 <?php
    log_message('info', 'View tabAggiornamenti variabile aggiornamenti' . print_r($aggiornamenti, true));
    if (!empty($aggiornamenti)): ?>
     <table class="table table-bordered table-hover table-striped align-middle datatable">
         <thead>
             <tr>
                 <th>ID</th>
                 <th>Data aggiornamento</th>
                 <th>Versione</th>
                 <th>Azioni</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach ($aggiornamenti as $a): ?>
                 <tr class="aggiornamento-row" data-id="<?= esc($a['id']) ?>" style="cursor:pointer;">
                     <td><?= ($a['id']) ?></td>
                     <td><?= date('d/m/Y', strtotime($a['dt_agg'])) ?></td>
                     <td><?= esc($a['versione']) ?></td>

                     <td>
                         <a href="/aggiornamenti/visualizza/<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary" title="Visualizza">
                             <i class="bi bi-eye"></i>
                             <a href="/aggiornamenti/modifica/<?= $a['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Modifica">
                                 <i class="bi bi-pencil"></i>
                             </a>
                             <a href="/aggiornamenti/elimina/<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger" title="Elimina" onclick=" return confirm('Sei sicuro di voler eliminare questo aggiornamento?');">
                                 <i class="bi bi-trash"></i>
                             </a>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 <?php else: ?>
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle text-dark me-2"></i>
    Nessun Aggiornamento per questa licenza.
</div>
     </div>
 <?php endif; ?>