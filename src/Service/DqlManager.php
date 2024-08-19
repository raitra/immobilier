<?php 
    namespace App\Service;
    
    class DqlManager
    {
        public function generateAlias(string $entityName): string
        {
            // Utilisation de preg_match_all pour récupérer les lettres majuscules
            preg_match_all('/[A-Z]/', $entityName, $matches);
            // $matches[0] contient un tableau avec les lettres majuscules
            $uppercaseLetters = implode('', $matches[0]);
            // Afficher le résultat
            return $uppercaseLetters;
        }
    } 

