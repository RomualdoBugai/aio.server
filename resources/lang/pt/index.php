<?php
return [
    'app' => json_decode(file_get_contents(resource_path() . "/" . "lang" . "/" . "pt" . "/" . "translate.json"), true)
];
