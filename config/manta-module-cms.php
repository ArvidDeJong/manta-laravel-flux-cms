<?php

return [
    'modules' => [
        [
            "active" => false,
            "name" => "translator",
            "routename" => "translator",
            "title" => "Algemene vertalingen",
            "route" => "translator.list",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => true,
            "name" => "staff",
            "routename" => "gebruikers",
            "title" => "Gebruikers",
            "route" => "staff.list",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => true,
            "name" => "settings",
            "routename" => "instellingen",
            "title" => "Instellingen",
            "route" => "cms.options",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => true,
            "name" => "mailtrap",
            "routename" => "mailtrap",
            "title" => "Maillog",
            "route" => "mailtrap.list",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => true,
            "name" => "upload",
            "routename" => "upload",
            "title" => "Upload",
            "route" => "upload.list",
            "location" => null,
            "menu" => ""
        ],
        [
            "active" => false,
            "name" => "user",
            "routename" => "klanten",
            "title" => "Klanten",
            "route" => "user.list",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => true,
            "name" => "routeseo",
            "routename" => "routeseo",
            "title" => "Route SEO",
            "route" => "routeseo.list",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => false,
            "name" => "sandbox",
            "routename" => "zandbak",
            "title" => "Zandbak",
            "route" => "cms.sandbox",
            "location" => null,
            "menu" => "tools"
        ],
        [
            "active" => false,
            "name" => "chatgpt",
            "routename" => "chatgpt",
            "title" => "ChatGPT",
            "route" => "chatgpt.chat",
            "location" => null,
            "menu" => "tools"
        ]
    ]
];
