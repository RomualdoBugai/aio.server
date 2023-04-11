<?php

/**
 *
 * @access  public
 * @param   string $string
 * @author  William Novak <williamnvk@gmail.com>
 * @version 1.0
 * @date    2016-12-02
 */
function ownName(string $string) {
    $de     = array(" Dos ", " Das ", " De ", " Do ", " Da ", " Por ");
    $para   = array(" dos ", " das ", " de ", " do ", " da ", " por ");
    $string = (string) trim(ucwords(strtolower($string)));
    $string = str_replace($de, $para, $string);
    return (string) $string;
}

/**
 *
 * @access  public
 * @param   string $ctrl
 * @param   string $message
 * @param   array  $data
 * @author  William Novak <williamnvk@gmail.com>
 * @version 1.0
 * @date    2016-12-02
 */
function message($ctrl = null, $message = null, $data = []) {

    $locale = App::getLocale();

    App::setLocale($locale);

    $ctrl = str_replace('_', '-', $ctrl);

	if ($ctrl == null) {
		return null;
	}

    if ($message == null) {
        return null;
    }

    $map = "index.app.{$ctrl}.";

    if (is_array($data) && count($data) > 0) {
        return trans($map . $message, $data);
    }

    return trans($map . $message);
}

function userSession() {
    return \App\Services\Useful\User::class;
}

function userId() {
    return \App\Services\Useful\User::id();
}

function userName() {
    return \App\Services\Useful\User::name();
}

function haveSession() {
    return \App\Services\Useful\User::haveSession();
}

function dateFormat() {
    return \App\Services\Useful\User::dateFormat();
}

function nationalDate() {
    return "d/m/Y";
}

function nationalDatetime() {
    return "d/m/Y H:i";
}

function inputDateFormat() {
    return \App\Services\Useful\User::inputDateFormat();
}

function isArray($data)
{
    return (bool) ( is_array($data) && count($data) > 0 ? true : false );
}

/**
 *
 * @access  public
 * @param   string $string
 * @author  William Novak <williamnvk@gmail.com>
 * @version 1.0
 * @date    2016-12-02
 */
function getCapitalLetters($string) {
    $data = explode(" ", $string);
    $total = count($data);
    if ($total == 1) {
        return substr($data[0], 0, 1);
    }
    if ($total > 1) {
        return substr($data[0], 0, 1) . substr($data[1], 0, 1);
    }
}


function firstName($string) {
    $data = explode(" ", $string);
    return strtolower($data[0]);
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
