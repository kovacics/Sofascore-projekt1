<?php

declare(strict_types=1);

/**
 * Ispisuje siguran string od HTML koda .
 * @param string|null $v
 * @return string
 */
function __(?string $v)
{
    if($v===null) return null;
    return htmlentities($v, ENT_QUOTES, "utf-8");
}

/**
 * Iz URL -a dohvaca parametar imena $v.
 * Ukoliko parametra nema , null vracen .
 * @param string $v
 * @return array|string|null
 */
function get(string $v)
{
    if (isset($_GET[$v])) {
        return $_GET[$v];
    }
    return null;
}

/**
 * Iz tijela HTTP zahtjeva dohvaca parametar imena $v.
 * Ukoliko parametra nema , null vracen .
 * @param string $v
 * @return array|string|null
 */
function post(string $v)
{
    if (isset($_POST[$v])) {
        return $_POST[$v];
    }
    return null;
}

function paramExists($param): bool
{
    if (null !== $param && !empty ($param)) return true;
    return false;
}

/**
 * Usmjeravanje na URL.
 * @param string $url
 */
function redirect(string $url): void
{
    header("Location: " . $url);
    die (); // prekida izvodenje skripte pozivateljice
}

/**
 * Provjera prijavljenosti korisnika .
 * @return bool true ako je korisnik prijavljen , false inace
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['userID']);
}

/**
 * Vraca ID prijavljenog korisnika
 * @return int ako je korisnik prijavljen , null inace
 */
function userID(): ?int
{
    return isLoggedIn() ? $_SESSION['userID'] : null;
}

/**
 * Stavlja string unutar dvostrukih navodnika.
 * @param string $s
 * @return string
 */
function q(string $s): string
{
    return "\"" . $s . "\"";
}

/**
 * Vraca string bez dvostrukih navodnika koji ga okruzuju, ukoliko postoje.
 * Ako ne postoje, vraca izvorni string.
 * @param string $s
 * @return string
 */
function unq(string $s): string
{
    $first = substr($s, 0, 1);
    $last = substr($s, strlen($s) - 1, 1);

    if ($first === "\"" && $last === "\"") {
        return substr($s, 1, strlen($s) - 2);
    }
    return $s;
}