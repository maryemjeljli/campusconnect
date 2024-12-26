<?php

// src/Form/Transformer/StringToIntTransformer.php
namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToIntTransformer implements DataTransformerInterface
{
    /**
     * Transforme la valeur du formulaire (string) en une valeur utilisable (int).
     *
     * @param mixed $value La valeur à transformer
     * 
     * @return int La valeur transformée
     */
    public function transform($value)
    {
        // On retourne la valeur comme une chaîne si nécessaire (bien que dans ce cas ce ne soit pas utilisé)
        return (string) $value;
    }

    /**
     * Transforme la valeur soumise par l'utilisateur (string) en un entier.
     *
     * @param mixed $value La valeur soumise par l'utilisateur (string)
     * 
     * @return int La valeur transformée en entier
     */
    public function reverseTransform($value)
    {
        // On transforme la valeur string en entier
        return (int) $value;
    }
}


