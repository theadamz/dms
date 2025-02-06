<?php

return [
    'userIdExceptions' => ['00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000001'],
    'roleIdExceptions' => ['00000000-0000-0000-0000-000000000000', '00000000-0000-0000-0000-000000000001'],
    'groups' => [
        ['code' => 'main', 'name' => 'Main', 'visible' => true],
        ['code' => 'setting', 'name' => 'Setting', 'visible' => true],
    ],
    'menus' => [
        /* Main */
        ['group_code' => 'main', 'parent_menu_code' => 'category', 'code' => 'category', 'name' => 'Categories', 'description' => 'Categories', 'path' => '/basics/categories', 'icon' => 'fas fa-long-arrow-alt-right', 'visible' => true,  'children' => null],
        ['group_code' => 'main', 'parent_menu_code' => 'category-sub', 'code' => 'category-sub', 'name' => 'Sub Categories', 'description' => 'Sub Categories', 'path' => '/basics/category-subs', 'icon' => 'fas fa-level-down-alt', 'visible' => true,  'children' => null],
        ['group_code' => 'main', 'parent_menu_code' => 'approval-set', 'code' => 'approval-set', 'name' => 'Approval Sets', 'description' => 'Approval Sets', 'path' => '/basics/approval-sets', 'icon' => 'fas fa-bars', 'visible' => true,  'children' => null],
        ['group_code' => 'main', 'parent_menu_code' => 'doc', 'code' => 'doc', 'name' => 'Documents', 'description' => 'All Documents', 'path' => '/documents', 'icon' => 'fas fa-file-alt', 'visible' => true,  'children' => null],
        [
            'group_code' => 'main',
            'parent_menu_code' => 'my-doc',
            'code' => 'my-doc',
            'name' => 'My Documents',
            'description' => 'My Documents - Parent',
            'path' => null,
            'icon' => 'fas fa-book-open',
            'visible' => true,
            'children' => [
                ['group_code' => 'main', 'parent_menu_code' => 'my-doc', 'code' => 'my-doc-new', 'name' => 'New', 'description' => 'Create new document', 'path' => '/documents/new', 'icon' => 'fas fa-plus', 'visible' => true,  'children' => null],
                ['group_code' => 'main', 'parent_menu_code' => 'my-doc', 'code' => 'my-doc-list', 'name' => 'List', 'description' => 'List of documents', 'path' => '/documents/list', 'icon' => 'fas fa-list', 'visible' => true,  'children' => null],
            ]
        ],
        [
            'group_code' => 'main',
            'parent_menu_code' => 'my-approval',
            'code' => 'my-approval',
            'name' => 'My Approval',
            'description' => 'My Approval - Parent',
            'path' => null,
            'icon' => 'fas fa-file-signature',
            'visible' => true,
            'children' => [
                ['group_code' => 'main', 'parent_menu_code' => 'my-approval', 'code' => 'my-approval-wait', 'name' => 'Waiting', 'description' => 'List waiting for approval', 'path' => '/documents/approvals/waiting', 'icon' => 'fas fa-hourglass-half', 'visible' => true,  'children' => null],
                ['group_code' => 'main', 'parent_menu_code' => 'my-approval', 'code' => 'my-approval-list', 'name' => 'List', 'description' => 'List of approval documents', 'path' => '/documents/approvals/list', 'icon' => 'fas fa-list', 'visible' => true,  'children' => null],
            ]
        ],
        [
            'group_code' => 'main',
            'parent_menu_code' => 'my-review',
            'code' => 'my-review',
            'name' => 'My Reviews',
            'description' => 'My Review - Parent',
            'path' => null,
            'icon' => 'fas fa-search',
            'visible' => true,
            'children' => [
                ['group_code' => 'main', 'parent_menu_code' => 'my-review', 'code' => 'my-review-wait', 'name' => 'Waiting', 'description' => 'List waiting for review', 'path' => '/documents/reviews/waiting', 'icon' => 'fas fa-hourglass-half', 'visible' => true,  'children' => null],
                ['group_code' => 'main', 'parent_menu_code' => 'my-review', 'code' => 'my-review-list', 'name' => 'List', 'description' => 'List of reviewed documents', 'path' => '/documents/reviews/list', 'icon' => 'fas fa-list', 'visible' => true,  'children' => null],
            ]
        ],
        [
            'group_code' => 'main',
            'parent_menu_code' => 'my-acknowledge',
            'code' => 'my-acknowledge',
            'name' => 'My Acknowledge',
            'description' => 'My Review - Parent',
            'path' => null,
            'icon' => 'fas fa-search',
            'visible' => true,
            'children' => [
                ['group_code' => 'main', 'parent_menu_code' => 'my-review', 'code' => 'my-acknowledge-wait', 'name' => 'Waiting', 'description' => 'List waiting for acknowledgement', 'path' => '/documents/acknowledge/waiting', 'icon' => 'fas fa-hourglass-half', 'visible' => true,  'children' => null],
                ['group_code' => 'main', 'parent_menu_code' => 'my-review', 'code' => 'my-acknowledge-list', 'name' => 'List', 'description' => 'List of acknowledged documents', 'path' => '/documents/acknowledge/list', 'icon' => 'fas fa-list', 'visible' => true,  'children' => null],
            ]
        ],

        /* Konfigurasi */
        [
            'group_code' => 'setting',
            'parent_menu_code' => 'config',
            'code' => 'config',
            'name' => 'Configurations',
            'description' => 'Menu Parent Configuration',
            'path' => null,
            'icon' => 'fas fa-cog',
            'visible' => true,
            'children' => [
                ['group_code' => 'setting', 'parent_menu_code' => 'config', 'code' => 'config-department', 'name' => 'Departments', 'description' => 'Departments', 'path' => '/configs/departments', 'icon' => 'fas fa-user-circle', 'visible' => true,  'children' => null],
                ['group_code' => 'setting', 'parent_menu_code' => 'config', 'code' => 'config-role', 'name' => 'Roles', 'description' => 'Roles', 'path' => '/configs/roles', 'icon' => 'fas fa-users', 'visible' => true,  'children' => null],
                ['group_code' => 'setting', 'parent_menu_code' => 'config', 'code' => 'config-role-access', 'name' => 'Role Accesses', 'description' => 'Role Accesses', 'path' => '/configs/accesses', 'icon' => 'fas fa-user-cog', 'visible' => true,  'children' => null],
                ['group_code' => 'setting', 'parent_menu_code' => 'config', 'code' => 'config-user', 'name' => 'Users', 'description' => 'Users', 'path' => '/configs/users', 'icon' => 'fas fa-user-plus', 'visible' => true,  'children' => null],
                ['group_code' => 'setting', 'parent_menu_code' => 'config', 'code' => 'config-user-access', 'name' => 'User Accesses', 'description' => 'User Accesses', 'path' => '/configs/user-accesses', 'icon' => 'fas fa-user-cog', 'visible' => true,  'children' => null],
            ],
        ],
    ],
    'roleList' => [
        /* ========== Access Menu ========== */
        /* Basic Data */
        ['code' => 'category', 'name' => 'Category', 'permissions' => ['read', 'create', 'edit', 'delete', 'import', 'export']],
        ['code' => 'category-sub', 'name' => 'Sub Category', 'permissions' => ['read', 'create', 'edit', 'delete', 'import', 'export']],
        ['code' => 'approval-set', 'name' => 'Approval Sets', 'permissions' => ['read', 'create', 'edit', 'delete', 'export']],
        ['code' => 'doc', 'name' => 'Documents', 'permissions' => ['read', 'create', 'edit', 'cancel', 'approve', 'reject', 'recall']],

        /* My Documents */
        ['code' => 'my-doc', 'name' => 'Menu Parent My Documents', 'permissions' => ['access']],
        ['code' => 'my-doc-new', 'name' => 'Menu My Documents - New', 'permissions' => ['access']],
        ['code' => 'my-doc-list', 'name' => 'Menu My Documents - List', 'permissions' => ['access']],

        /* My Approval */
        ['code' => 'my-approval', 'name' => 'Menu Parent My Approval', 'permissions' => ['access']],
        ['code' => 'my-approval-wait', 'name' => 'My Approval - Waiting', 'permissions' => ['access']],
        ['code' => 'my-approval-list', 'name' => 'My Approval - List', 'permissions' => ['access']],

        /* My Acknowledge */
        ['code' => 'my-acknowledge', 'name' => 'Menu Parent My Acknowledge', 'permissions' => ['access']],
        ['code' => 'my-acknowledge-wait', 'name' => 'My Acknowledge - Waiting', 'permissions' => ['access']],
        ['code' => 'my-acknowledge-list', 'name' => 'My Acknowledge - List', 'permissions' => ['access']],

        /* Configurations */
        ['code' => 'config', 'name' => 'Menu Parent Configurations', 'permissions' => ['read']],
        /* Configurations - Core */
        ['code' => 'config-department', 'name' => 'Menu Configurations - Department', 'permissions' => ['read', 'create', 'edit', 'delete', 'import', 'export']],
        ['code' => 'config-role', 'name' => 'Menu Configurations - Roles', 'permissions' => ['read', 'create', 'edit', 'delete']],
        ['code' => 'config-role-access', 'name' => 'Menu Configurations - Role Accesses', 'permissions' => ['read', 'create', 'edit', 'delete']],
        ['code' => 'config-user', 'name' => 'Menu Configurations - Users', 'permissions' => ['read', 'create', 'edit', 'delete']],
        ['code' => 'config-user-access', 'name' => 'Menu Configurations - User Accesses', 'permissions' => ['read', 'create', 'edit', 'delete']],
    ],
    'userList' => [
        ['code' => 'approve-doc', 'name' => 'Document Approval', 'permissions' => ['approve', 'reject', 'cancel', 'recall']],
    ],
];
