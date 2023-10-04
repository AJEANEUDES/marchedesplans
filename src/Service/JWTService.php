<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    //pour la génération des tokens, se rendre sur le site de https://www.jwt.io
    //génération du token
    //JWT= JSON Web Token
    //3heures=10800s comme validité.
    //NB: Il est modifiable. On change juste le nombre de secondes.

    /**
     * Génération du JWT
     * @param array $header
     * @param array $payload
     * @param array $secret
     * @param array $validity
     * @return string
     */


    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        // if($validity <= 0)
        // {
        //     //retourne une chaine vide
        //     return "";
        // }

        if($validity > 0)
        {
            $now = new DateTimeImmutable();
            $exp = $now->getTimeStamp() + $validity;
    
            //iat= issue at 
            //exp = expiration
    
            $payload['iat'] = $now->getTimeStamp();
            $payload['exp'] = $exp;
        }

        //On encode en base 64 car les JWT sont de l'encodage en base 64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //après on nettoie les valeurs encodées(retrait des + , / et = )

        $base64Header = str_replace(['+', '/', '='], ['-', '_',  ''],
        $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_',  ''],
        $base64Payload);

        // Génération de la signature; donc il faut un secret

        $secret = base64_encode($secret);
        //fonction hash_hmac() pour faire un hash
        //sha256 utilisé pour les fichiers encode JSON

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
        
        $base64Signature = base64_encode($signature);

        //après on nettoie les valeurs encodées(retrait des + , / et = )

        $base64Signature = str_replace(['+', '/', '='], ['-', '_',  ''],
        $base64Signature);

        //création du token

        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }


    //on vérifie que le token fonctionne correctement( corectement formé et non valide)

    public function isValid(string $token):bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/', $token
        ) == 1;
    }


    //vérifions si le token est expiré
    //récupération du payload(qui permet de savoir si le token est expiré)

    public function getPayload(string $token):array
    {
        //on démonte le token
        $array = explode('.', $token);

        //on décode le payload

        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;

    }


     // On récupère le Header
     public function getHeader(string $token): array
     {
         // On démonte le token
         $array = explode('.', $token);
 
         // On décode le Header
         $header = json_decode(base64_decode($array[0]), true);
 
         return $header;
     }


     
    //on vérifie si le token a expiré

    public function isExpired(string $token):bool
    {
        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] <  $now->getTimeStamp();

    }

    //on vérifie la signature du token

    public function check(string $token, string $secret)
    {
        //on récupère le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);

        //régénération du token (revérification de la signature)

        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token == $verifToken;
    }

}