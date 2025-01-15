<?php

return [
    'csp' => [
        'default-src' => ["'self'"],
        'script-src' => ["'self'", 'https://cdnjs.cloudflare.com', 'https://cdn.jsdelivr.net'],
        'style-src' => ["'self'", 'https://fonts.googleapis.com', 'https://cdnjs.cloudflare.com'],
        'img-src' => ["'self'", 'data:', 'https:'],
        'font-src' => ["'self'", 'https://fonts.gstatic.com', 'https://cdnjs.cloudflare.com'],
        'connect-src' => ["'self'"],
        'form-action' => ["'self'"], // Importante para o formulário
    ],
];
