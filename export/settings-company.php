<?php

return [
    "name" => "company",
    "title" => "Bedrijven",
    "description" => "Bedrijven module",
    "module_name" => [
        "single" => "Bedrijf",
        "multiple" => "Bedrijven"
    ],
    "tabtitle" => "company",
    "tab_title" => "company",
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
        "uploads" => [
            "active" => true,
            "type" => "",
            "title" => "Uploads",
            "read" => false,
            "required" => false,
            "edit" => false,
        ],
        "active" => [
            "active" => true,
            "type" => "checkbox",
            "title" => "Actief",
            "read" => true,
            "read_type" => "bool",
            "required" => false
        ],
        "user_nr" => [
            "active" => true,
            "type" => "text",
            "title" => "Gebruiker nummer",
            "read" => true,
            "required" => false
        ],
        "number" => [
            "active" => true,
            "type" => "text",
            "title" => "Nummer",
            "read" => true,
            "required" => false
        ],
        "sex" => [
            "active" => true,
            "type" => "select",
            "title" => "Geslacht",
            "read" => true,
            "required" => false,
            "options" => [
                "m" => "Man",
                "f" => "Vrouw",
                "x" => "Anders"
            ]
        ],
        "initials" => [
            "active" => true,
            "type" => "text",
            "title" => "Voorletters",
            "read" => true,
            "required" => false
        ],
        "lastname" => [
            "active" => true,
            "type" => "text",
            "title" => "Achternaam",
            "read" => true,
            "required" => false
        ],
        "firstnames" => [
            "active" => true,
            "type" => "text",
            "title" => "Voornamen",
            "read" => true,
            "required" => false
        ],
        "nameInsertion" => [
            "active" => true,
            "type" => "text",
            "title" => "Tussenvoegsel",
            "read" => true,
            "required" => false
        ],
        "company" => [
            "active" => true,
            "type" => "text",
            "title" => "Bedrijfsnaam",
            "read" => true,
            "required" => false
        ],
        "companyNr" => [
            "active" => true,
            "type" => "text",
            "title" => "Bedrijfsnummer",
            "read" => true,
            "required" => false
        ],
        "taxNr" => [
            "active" => true,
            "type" => "text",
            "title" => "BTW nummer",
            "read" => true,
            "required" => false
        ],
        "address" => [
            "active" => true,
            "type" => "text",
            "title" => "Adres",
            "read" => true,
            "required" => false
        ],
        "housenumber" => [
            "active" => true,
            "type" => "text",
            "title" => "Huisnummer",
            "read" => true,
            "required" => false
        ],
        "addressSuffix" => [
            "active" => true,
            "type" => "text",
            "title" => "Toevoeging",
            "read" => true,
            "required" => false
        ],
        "zipcode" => [
            "active" => true,
            "type" => "text",
            "title" => "Postcode",
            "read" => true,
            "required" => false
        ],
        "city" => [
            "active" => true,
            "type" => "text",
            "title" => "Plaats",
            "read" => true,
            "required" => false
        ],
        "country" => [
            "active" => true,
            "type" => "text",
            "title" => "Land",
            "read" => true,
            "required" => false,
            "default" => "nl"
        ],
        "state" => [
            "active" => true,
            "type" => "text",
            "title" => "Provincie/Staat",
            "read" => true,
            "required" => false
        ],
        "email" => [
            "active" => true,
            "type" => "text",
            "title" => "E-mail",
            "read" => true,
            "required" => false
        ],
        "birthdate" => [
            "active" => true,
            "type" => "date",
            "title" => "Geboortedatum",
            "read" => true,
            "required" => false
        ],
        "birthcity" => [
            "active" => true,
            "type" => "text",
            "title" => "Geboorteplaats",
            "read" => true,
            "required" => false
        ],
        "phone" => [
            "active" => true,
            "type" => "text",
            "title" => "Telefoon",
            "read" => true,
            "required" => false
        ],
        "phone2" => [
            "active" => true,
            "type" => "text",
            "title" => "Telefoon 2",
            "read" => true,
            "required" => false
        ],
        "url" => [
            "active" => true,
            "type" => "text",
            "title" => "Url",
            "read" => true,
            "required" => false
        ],
        "website" => [
            "active" => true,
            "type" => "text",
            "title" => "Website",
            "read" => true,
            "required" => false
        ],
        "bsn" => [
            "active" => true,
            "type" => "text",
            "title" => "BSN",
            "read" => true,
            "required" => false
        ],
        "iban" => [
            "active" => true,
            "type" => "text",
            "title" => "IBAN",
            "read" => true,
            "required" => false
        ],
        "title" => [
            "active" => true,
            "type" => "text",
            "title" => "Titel",
            "read" => true,
            "required" => false
        ],
        "excerpt" => [
            "active" => true,
            "type" => "textarea",
            "title" => "Inleiding",
            "read" => true,
            "required" => false
        ],
        "description" => [
            "active" => true,
            "type" => "textarea",
            "title" => "Omschrijving",
            "read" => true,
            "required" => false
        ],
        "google_maps_embed" => [
            "active" => true,
            "type" => "text",
            "title" => "Google Maps Embed",
            "read" => true,
            "required" => false
        ],
        "latitude" => [
            "active" => true,
            "type" => "number",
            "title" => "Breedtegraad",
            "read" => true,
            "required" => false,
            "step" => "0.00000001",
            "min" => "-90",
            "max" => "90"
        ],
        "longitude" => [
            "active" => true,
            "type" => "number",
            "title" => "Lengtegraad",
            "read" => true,
            "required" => false,
            "step" => "0.00000001",
            "min" => "-180",
            "max" => "180"
        ]
    ],
];
