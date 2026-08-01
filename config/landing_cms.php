<?php

$hero = [
    'label' => 'Hero',
    'fields' => [
        'hero_eyebrow' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:150'],
        'hero_title' => ['label' => 'Judul', 'type' => 'textarea', 'rules' => 'required|string|max:200'],
        'hero_description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'rules' => 'nullable|string|max:1000'],
        'hero_image' => ['label' => 'Gambar Hero', 'type' => 'image'],
        'hero_image_alt' => ['label' => 'Alt Gambar', 'type' => 'text', 'rules' => 'required|string|max:200'],
    ],
];

return [
    'catalog' => [
        'label' => 'Catalog', 'view' => 'landing.catalog',
        'settings' => [$hero,
            ['label' => 'Catalog Sections', 'fields' => [
                'category_heading' => ['label' => 'Heading Kategori', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'browse_heading' => ['label' => 'Heading Hewan', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'all_label' => ['label' => 'Label Semua', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'all_image' => ['label' => 'Gambar Semua', 'type' => 'image'],
                'all_image_alt' => ['label' => 'Alt Gambar Semua', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
        ],
        'collections' => [],
    ],
    'about' => [
        'label' => 'About', 'view' => 'landing.about',
        'settings' => [$hero,
            ['label' => 'Who We Are', 'fields' => [
                'about_label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'about_heading' => ['label' => 'Heading', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'about_paragraph_1' => ['label' => 'Paragraf 1', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'about_paragraph_2' => ['label' => 'Paragraf 2', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'about_image' => ['label' => 'Gambar', 'type' => 'image'],
                'about_image_alt' => ['label' => 'Alt Gambar', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
            ['label' => 'Vision & Mission', 'fields' => [
                'vision_mission_label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'vision_mission_heading' => ['label' => 'Heading', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'vision_title' => ['label' => 'Judul Vision', 'type' => 'text', 'rules' => 'required|string|max:150'],
                'vision_description' => ['label' => 'Isi Vision', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'vision_image' => ['label' => 'Gambar Vision', 'type' => 'image'],
                'vision_image_alt' => ['label' => 'Alt Vision', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'mission_title' => ['label' => 'Judul Mission', 'type' => 'text', 'rules' => 'required|string|max:150'],
                'mission_image' => ['label' => 'Gambar Mission', 'type' => 'image'],
                'mission_image_alt' => ['label' => 'Alt Mission', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
            ['label' => 'Leadership', 'fields' => [
                'leadership_label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:100'],
                'leadership_heading' => ['label' => 'Heading', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'leadership_paragraph_1' => ['label' => 'Paragraf 1', 'type' => 'textarea', 'rules' => 'required|string|max:5000'],
                'leadership_paragraph_2' => ['label' => 'Paragraf 2', 'type' => 'textarea', 'rules' => 'required|string|max:5000'],
            ]],
        ],
        'collections' => ['mission_items' => ['label' => 'Daftar Mission', 'fields' => ['description', 'sort_order']]],
    ],
    'information_logistic' => [
        'label' => 'Information – Logistic', 'view' => 'landing.information-logistic',
        'settings' => [$hero,
            ['label' => 'Commitment', 'fields' => [
                'commitment_label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:150'],
                'commitment_paragraph_1' => ['label' => 'Paragraf 1', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'commitment_paragraph_2' => ['label' => 'Paragraf 2', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'commitment_image' => ['label' => 'Gambar', 'type' => 'image'],
                'commitment_image_alt' => ['label' => 'Alt Gambar', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
            ['label' => 'Freight Sections', 'fields' => [
                'air_heading' => ['label' => 'Heading Air Freight', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'air_note' => ['label' => 'Catatan Air Freight', 'type' => 'textarea', 'rules' => 'required|string|max:2000'],
                'sea_heading' => ['label' => 'Heading Sea Freight', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'sea_note' => ['label' => 'Catatan Sea Freight', 'type' => 'textarea', 'rules' => 'required|string|max:2000'],
            ]],
        ],
        'collections' => [
            'air_steps' => ['label' => 'Langkah Air Freight', 'fields' => ['title', 'description', 'image', 'alt', 'sort_order']],
            'sea_steps' => ['label' => 'Langkah Sea Freight', 'fields' => ['title', 'description', 'image', 'alt', 'sort_order']],
        ],
    ],
    'information_procurement' => [
        'label' => 'Information – Procurement', 'view' => 'landing.information-procurement',
        'settings' => [$hero,
            ['label' => 'Commitment', 'fields' => [
                'commitment_heading' => ['label' => 'Heading', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'commitment_paragraph_1' => ['label' => 'Paragraf 1', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'commitment_paragraph_2' => ['label' => 'Paragraf 2', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'commitment_image_1' => ['label' => 'Gambar 1', 'type' => 'image'],
                'commitment_image_1_alt' => ['label' => 'Alt Gambar 1', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'commitment_image_2' => ['label' => 'Gambar 2', 'type' => 'image'],
                'commitment_image_2_alt' => ['label' => 'Alt Gambar 2', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
            ['label' => 'Sources & Standards', 'fields' => [
                'sources_heading' => ['label' => 'Heading Sources', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'sources_paragraph_1' => ['label' => 'Paragraf Sources 1', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'sources_paragraph_2' => ['label' => 'Paragraf Sources 2', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'sources_image' => ['label' => 'Gambar Sources', 'type' => 'image'],
                'sources_image_alt' => ['label' => 'Alt Sources', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'sources_note' => ['label' => 'Catatan Sources', 'type' => 'textarea', 'rules' => 'required|string|max:2000'],
                'standards_heading' => ['label' => 'Heading Standards', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
        ],
        'collections' => [
            'quality_items' => ['label' => 'Quality Items', 'fields' => ['title', 'description', 'sort_order']],
            'source_cards' => ['label' => 'Source Cards', 'fields' => ['title', 'image', 'alt', 'sort_order']],
            'standards' => ['label' => 'Preparation Standards', 'fields' => ['title', 'description', 'sort_order']],
        ],
    ],
    'information_live_export' => [
        'label' => 'Information – Live Export', 'view' => 'landing.information-live-export',
        'settings' => [$hero,
            ['label' => 'Intro & Commitment', 'fields' => [
                'intro' => ['label' => 'Intro', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
                'commitment_heading' => ['label' => 'Heading Commitment', 'type' => 'text', 'rules' => 'required|string|max:200'],
                'commitment_description' => ['label' => 'Isi Commitment', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
            ]],
        ],
        'collections' => [
            'steps' => ['label' => 'Langkah Live Export', 'fields' => ['title', 'description', 'image', 'alt', 'sort_order']],
            'highlights' => ['label' => 'Commitment Highlights', 'fields' => ['title', 'sort_order']],
        ],
    ],
    'future_projects' => [
        'label' => 'Future Projects', 'view' => 'landing.future-projects',
        'settings' => [$hero,
            ['label' => 'Roadmap', 'fields' => [
                'section_label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:150'],
                'section_heading' => ['label' => 'Heading', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
        ],
        'collections' => ['projects' => ['label' => 'Daftar Proyek', 'fields' => ['description', 'sort_order']]],
    ],
    'gallery' => [
        'label' => 'Gallery', 'view' => 'landing.gallery',
        'settings' => [$hero,
            ['label' => 'Gallery Section', 'fields' => [
                'section_label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:150'],
                'section_heading' => ['label' => 'Heading', 'type' => 'text', 'rules' => 'required|string|max:200'],
            ]],
        ],
        'collections' => ['items' => [
            'label' => 'Foto Gallery',
            'fields' => ['title', 'description', 'image', 'alt', 'sort_order'],
            'optional_fields' => ['description'],
        ]],
    ],
];
