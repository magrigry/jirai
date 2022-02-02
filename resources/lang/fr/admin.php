<?php

return [
    'nav' => [
        'title' => 'Jirai',
        'settings' => 'Paramètres',
    ],

    'permission' => [
        'changelog-post' => 'Jirai: Publier un journal des modifications',
        'issue-post' => 'Jirai: Signaler un problème',
        'issue-edit-self' => 'Jirai: Modifier son poste',
        'issue-edit-others' => 'Jirai: Modifier les postes des autres',
        'issue-delete-self' => 'Jirai: Supprimer ses propres postes',
        'issue-delete-others' => 'Jirai: Supprimer les postes des autres',
        'message-post' => 'Jirai: Repondre aux postes',
        'message-edit-self' => 'Jirai: Modifier ses propres messages',
        'message-edit-others' => 'Jirai: Modifier les messages des autres',
        'message-delete-self' => 'Jirai: Supprimer ses propres messages',
        'message-delete-others' => 'Jirai: Supprimer les messages des autres',
        'admin-settings' => 'Jirai: Voir et gérer les paramètres',
        'post-attachments' => 'Jirai: Poster une capture d’écran lors de l’utilisation de l’éditeur de texte',
    ],

    'settings' => [
        'title' => 'Paramètres du plugin jirai',
        'discord_webhook_for_suggestions' => 'Webhook Discord pour les suggestions',
        'discord_webhook_for_bugs' => 'Webhook Discord pour les bugs',
        'discord_webhook_for_changelogs' => 'Webhook Discord pour changelogs',
        'issues_per_page' => 'Problèmes par page',
        'changelogs_per_page' => 'Journaux de modifications par page',
        'route_prefix' => 'Préfixe d’URL (http://domain.com/{prefixe_ici}/...)',
    ],
];
