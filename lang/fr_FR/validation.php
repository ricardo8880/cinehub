<?php

return [
    'accepted' => 'Le champ :attribute doit être accepté.',
    'array' => 'Le champ :attribute doit être un tableau.',
    'boolean' => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    'date' => 'Le champ :attribute doit contenir une date valide.',
    'email' => 'Le champ :attribute doit contenir une adresse e-mail valide.',
    'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
    'image' => 'Le champ :attribute doit contenir une image.',
    'integer' => 'Le champ :attribute doit être un nombre entier.',
    'max' => [
        'array' => 'Le champ :attribute ne peut pas contenir plus de :max éléments.',
        'file' => 'Le fichier :attribute ne peut pas dépasser :max Ko.',
        'numeric' => 'Le champ :attribute ne peut pas dépasser :max.',
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'min' => [
        'array' => 'Le champ :attribute doit contenir au moins :min éléments.',
        'file' => 'Le fichier :attribute doit faire au moins :min Ko.',
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :min.',
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'present' => 'Le champ :attribute doit être présent.',
    'prohibited' => 'Le champ :attribute est interdit.',
    'regex' => 'Le format du champ :attribute est invalide.',
    'required' => 'Le champ :attribute est obligatoire.',
    'string' => 'Le champ :attribute doit être du texte.',
    'unique' => 'La valeur du champ :attribute est déjà utilisée.',
    'url' => 'Le champ :attribute doit contenir une URL valide.',
    'attributes' => [
        'name' => 'nom', 'email' => 'e-mail', 'password' => 'mot de passe',
        'body' => 'commentaire', 'subject' => 'sujet', 'message' => 'message',
        'interests' => 'centres d’intérêt',
    ],
];
