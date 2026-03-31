<?php

/**
 * Genera gli attributi HTML per il tooltip Bootstrap con info di audit (created/updated).
 *
 * Uso nelle view:
 *   <tr <?= audit_tooltip($entity) ?>>
 *
 * @param array  $entity    Array con chiavi: created_by_name, created_at, updated_by_name, updated_at
 * @param string $placement Posizione del tooltip (top, bottom, left, right)
 */
function audit_tooltip(array $entity, string $placement = 'top'): string
{
    $createdName = $entity['created_by_name'] ?? 'N/A';
    $createdAt   = !empty($entity['created_at']) ? date('d/m/Y H:i', strtotime($entity['created_at'])) : 'N/A';

    $title = "Creato da: {$createdName} il {$createdAt}";

    if (!empty($entity['updated_at'])) {
        $updatedName = $entity['updated_by_name'] ?? 'N/A';
        $updatedAt   = date('d/m/Y H:i', strtotime($entity['updated_at']));
        $title .= " | Ultima modifica da: {$updatedName} il {$updatedAt}";
    }

    return 'data-bs-toggle="tooltip" data-bs-placement="' . $placement . '" title="' . esc($title) . '"';
}
