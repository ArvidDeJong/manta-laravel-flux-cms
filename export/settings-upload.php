<?php

return [
    "name" => "upload",
    "title" => "Uploads",
    "description" => "Uploads module",
    "module_name" => [
        "single" => "Upload",
        "multiple" => "Uploads"
    ],
    "tabtitle" => "title",
    "tab_title" => "title",
    "type" => "module",
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
            "active" => true,
            "type" => "locale",
            "title" => "Taal",
            "edit" => false,
            "read" => true,
        ],
        "title" => [
            "active" => true,
            "type" => "text",
            "title" => "Titel intern",
            "read" => true,
            "edit" => true,
        ],
        "slug" => [
            "active" => false,
            "type" => "text",
            "title" => "Slug",
            "read" => true,
            "edit" => false,
        ],
        "content" => [
            "active" => true,
            "type" => "textarea",
            "title" => "Omschrijving",
            "read" => true,
            "edit" => true,
        ],
        "identifier" => [
            "active" => true,
            "type" => "text",
            "title" => "Identifier (NIET AANPASSEN!)",
            "read" => true,
            "edit" => true,
        ],
    ],
];
