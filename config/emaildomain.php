<?php

return [
    'allowed' => [
        'geofisika.its.ac.id',
        'its.ac.id',
        'student.its.ac.id',
        'gmail.com',
    ],
    'restricted' => [],

    // Domain-based role assignments
    'domain_roles' => [
        'gmail.com' => 'MITRA',
        'student.its.ac.id' => 'MAHASISWA',
        'geofisika.its.ac.id' => 'DOSEN PEMBIMBING',
        // Add more domain-role mappings as needed
        // 'its.ac.id' => 'DEFINED ROLE',
    ],

    // Specific email-based role assignments (takes priority over domain rules)
    'specific_roles' => [
        'zeonkunix@gmail.com' => 'LO-MBKM',
        'dimasfadilah20@gmail.com' => 'ADMIN',
        'rafif.zeon@gmail.com' => 'MAHASISWA',
        // Add more specific email-role mappings as needed
        // 'admin@its.ac.id' => 'ADMIN',
    ],
];
