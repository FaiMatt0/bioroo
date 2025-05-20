<?php
// Funzioni di validazione

/**
 * Valida una email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida una password (minimo 8 caratteri)
 */
function validatePassword($password) {
    return strlen($password) >= 8;
}

/**
 * Valida un campo obbligatorio
 */
function validateRequired($field) {
    return !empty(trim($field));
}

/**
 * Valida un numero intero positivo
 */
function validatePositiveInt($number) {
    return filter_var($number, FILTER_VALIDATE_INT) && $number > 0;
}

/**
 * Valida un prezzo (numero decimale positivo)
 */
function validatePrice($price) {
    return is_numeric($price) && $price >= 0;
}

/**
 * Valida l'intero form
 * 
 * @param array $data Array di dati da validare
 * @param array $rules Array di regole (nome_campo => array di funzioni di validazione)
 * @return array Array di errori (vuoto se non ci sono errori)
 */
function validateForm($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $validations) {
        foreach ($validations as $validation) {
            if (!isset($data[$field]) || !$validation($data[$field])) {
                $errors[$field] = "Campo non valido";
                break;
            }
        }
    }
    
    return $errors;
}