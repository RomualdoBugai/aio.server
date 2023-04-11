<?php
return [
    'app' => json_decode(file_get_contents(resource_path() . "/" . "lang" . "/" . "en" . "/" . "translate.json"), true)
];
