<?php

function language()
{
    if (\Session::has('language')) {
        return \Session::get('language');
    } else {
        return env('APP_LANGUAGE', 'en');
    }
}

function candyAttribute($data, $attribute)
{
    $language = language();
    $channel = env('APP_CHANNEL', 'get-candy');

    if (isset($data['attribute_data'][$attribute][$channel][$language])) {
        return $data['attribute_data'][$attribute][$channel][$language];
    }
    return '';
}

function candyRoute($data)
{
    $language = language();

    if (isset($data['routes']['data'])) {
        foreach ($data['routes']['data'] as $route) {
            if ($route['locale'] == $language) {
                return $route['slug'];
            }
        }
    }

    return '/';
}

function candyPrimaryImage($data)
{
    if (isset($data['thumbnail']['data']['url'])) {
        return $data['thumbnail']['data']['url'];
    }

    return '/images/no-image.png';
}

function candyPrimaryThumbnail($data)
{
    if (isset($data['thumbnail']['data']['thumbnail'])) {
        return $data['thumbnail']['data']['thumbnail'];
    }

    return '/images/no-image.png';
}

function userHasRole($role)
{
    $user = \Session::get('user');
    if(!isset($user['groups']['data'])){return false;}

    foreach($user['groups']['data'] as $group) {
        if ($group['handle'] == $role) {
            return true;
        }
    }
    return false;
}

function priceConverter($gbp, $floor = true)
{
    $rate = env('CONVERSION_RATE', 1);
    if ($floor) {
        return floor($gbp / $rate);
    }
    return round($gbp / $rate, 2);
}
