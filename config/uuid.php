<?php
function username_to_uuid($username) {
    $profile = username_to_profile($username);
    if (is_array($profile) and isset($profile['id'])) {
        return $profile['id'];
    }
    return false;
}
function username_to_profile($username) {
    if (is_valid_username($username)) {
        $json = file_get_contents('https://api.mojang.com/users/profiles/minecraft/' . $username);
        if (!empty($json)) {
            $data = json_decode($json, true);
            if (is_array($data) and !empty($data)) {
                return $data;
            }
        }
    }
    return false;
}
function uuid_to_username($uuid) {
    $uuid = minify_uuid($uuid);
    if (is_string($uuid)) {
        $json = file_get_contents('https://api.mojang.com/user/profiles/' . $uuid . '/names');
        if (!empty($json)) {
            $data = json_decode($json, true);
            if (!empty($data) and is_array($data)) {
                $last = array_pop($data);
                if (is_array($last) and isset($last['name'])) {
                    return $last['name'];
                }
            }
        }
    }
    return false;
}
function is_valid_username($string) {
    return is_string($string) and strlen($string) >= 2 and strlen($string) <= 16 and ctype_alnum(str_replace('_', '', $string));
}
function minify_uuid($uuid) {
    if (is_string($uuid)) {
        $minified = str_replace('-', '', $uuid);
        if (strlen($minified) === 32) {
            return $minified;
        }
    }
    return false;
}
function format_uuid($uuid) {
    $uuid = minify_uuid($uuid);
    if (is_string($uuid)) {
        return substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
    }
    return false;
}