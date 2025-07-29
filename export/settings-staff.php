<?php

return [
    "name" => "staff",
    "title" => "Gebruikers",
    "description" => "Gebruikers module",
    "module_name" => [
        "single" => "Gebruiker",
        "multiple" => "Gebruikers"
    ],
    "tabtitle" => "title",
    "tab_title" => "title",
    "type" => "tool",
    "active" => true,
    "locale" => "nl",
    "data" => [],
    "sort" => 999,
    "route" => null,
    "url" => null,
    "icon" => null,
    "rights" => null,
    "ereg" => [],
    "settings" => [],
    "fields" => [
        "locale" => [
            "active" => false,
            "type" => "text",
            "title" => "Taal",
            "read" => true,
            "required" => false,
            "edit" => true,
        ],
        "active" => [
            "active" => true,
            "type" => "checkbox",
            "title" => "Actief",
            "read" => true,
            "read_type" => "bool",
            "required" => false
        ],
        "name" => [
            "active" => true,
            "type" => "text",
            "title" => "Naam",
            "read" => true,
            "required" => true
        ],
        "email" => [
            "active" => true,
            "type" => "email",
            "title" => "Email",
            "read" => true,
            "required" => true
        ],
        "password" => [
            "active" => true,
            "type" => "password",
            "title" => "Wachtwoord",
            "required" => true
        ],
        "admin" => [
            "active" => true,
            "type" => "checkbox",
            "title" => "Admin",
            "read" => true,
            "read_type" => "bool",
            "required" => false
        ],
        "developer" => [
            "active" => true,
            "type" => "checkbox",
            "title" => "Developer",
            "read" => true,
            "read_type" => "bool",
            "required" => false
        ],
        "comments" => [
            "active" => true,
            "type" => "textarea",
            "title" => "Opmerkingen",
            "read" => true,
            "required" => false
        ]
    ],
];
