<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'pending';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Completo controllo del sito.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Amministratori delle funzionalità avanzate.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'Utenti generici del sito.',
        ],
        'pending' => [
            'title'       => 'In attesa',
            'description' => 'Utenti in attesa di approvazione.',
        ],
        'dev' => [
            'title'       => 'Developer',
            'description' => 'Utenti con permessi di sviluppo.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'        => 'Può accedere all\'area di amministrazione',
        'admin.settings'      => 'Può modificare le impostazioni di sistema',
        'users.manage-admins' => 'Può gestire gli utenti admin e superadmin',
        'users.create'        => 'Può creare nuovi utenti non admin',
        'users.edit'          => 'Può modificare utenti non admin',
        'users.delete'        => 'Può eliminare utenti non admin',

    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],

        'user' => [],

    ];
}
