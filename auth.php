<?php
/**
 * Enkel autentisering
 * Brukernavn og passord for å beskytte chat-siden
 */

session_start();

// Innloggingsinfo
define('AUTH_USERNAME', 'h28');
define('AUTH_PASSWORD', 'hns281209');

/**
 * Sjekk om bruker er innlogget
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Krev innlogging - redirect til login hvis ikke innlogget
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Logg inn bruker
 */
function login($username, $password) {
    if ($username === AUTH_USERNAME && $password === AUTH_PASSWORD) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        return true;
    }
    return false;
}

/**
 * Logg ut bruker
 */
function logout() {
    session_destroy();
}
