<?php

namespace App\Support;

class LandingCatalogData
{
    public static function categories(): array
    {
        return [
            [
                'key' => 'aves',
                'name' => 'Aves',
                'desc' => 'Birds from around the world',
                'image' => asset('images/aves.jpeg'),
                'families' => [
                    'Broadbill', 'Bulbul', 'Butcherbird', 'Eclectus', 'Finch', 'Flycatcher', 'Hanging Parrot',
                    'Hornbill', 'Iora', 'Lorikeet', 'Lory', 'Lovebird', 'Myna', 'Oriole', 'Parakeet',
                    'Parrot Cockatoo', 'Parrot Macaw', 'Parrot Medium', 'Peacock', 'Pheasant', 'Pigeon',
                    'Pigeon Dove', 'Pigeon Ducula', 'Pigeon Fruit-dove', 'Pigeon Green-pigeon',
                    'Pigeon Ground Dove', 'Pitta', 'Quail', 'Sibia', 'Singing Bird', 'Starling', 'Sunbird',
                    'Waterfowl', 'Woodpecker',
                ],
            ],
            [
                'key' => 'mammals',
                'name' => 'Mamalia',
                'desc' => 'High-quality mammals from trusted breeders',
                'image' => asset('images/mammals.jpeg'),
                'families' => ['Viverridae', 'Leporidae', 'Marsupilia'],
            ],
            [
                'key' => 'reptiles',
                'name' => 'Reptil',
                'desc' => 'Healthy and unique reptiles with excellent care',
                'image' => asset('images/reptil.jpeg'),
                'families' => ['Tortoise', 'Snake', 'Gecko'],
            ],
            [
                'key' => 'hybrid',
                'name' => 'Hybrid & Mutation',
                'desc' => 'Special hybrid and mutation animals with rare traits',
                'image' => asset('images/hybrid.jpeg'),
                'families' => ['Macaw'],
            ],
        ];
    }

    public static function products(): array
    {
        return [
            [
                'slug' => 'blue-and-gold-macaw',
                'name' => 'Blue and Gold Macaw',
                'latin' => 'Ara ararauna',
                'category' => 'aves',
                'subcategory' => 'Parrot Macaw',
                'price' => 'USD 2,850 / Pair',
                'image' => asset('images/detail-macaw.jpeg'),
                'gallery' => [
                    asset('images/detail-macaw.jpeg'),
                    asset('images/aves.jpeg'),
                    asset('images/about-vision.png'),
                    asset('images/catalog.jpeg'),
                    asset('images/hybrid.jpeg'),
                ],
                'description' => 'A large and intelligent macaw with brilliant blue and gold plumage. Well-socialized and suitable for aviary collections.',
                'details' => 'Age: 12 months. Sex: unsexed. Status: captive bred. Documents: microchip and breeder certificate.',
                'shipping' => 'Shipment handled via IATA-approved carriers with proper crate and veterinary documentation.',
                'other' => 'Minimum order and destination import rules apply based on country regulations.',
                'care' => 'Provide spacious aviary setup, daily enrichment, and balanced nutrition with seeds, nuts, fruits, and fresh water.',
                'legal' => 'Export follows applicable national law and destination import rules, including permit support where required.',
            ],
            [
                'slug' => 'yellow-crested-cockatoo',
                'name' => 'Yellow Crested Cockatoo',
                'latin' => 'Cacatua sulphurea',
                'category' => 'aves',
                'subcategory' => 'Parrot Cockatoo',
                'price' => '$2,900',
                'image' => 'https://images.unsplash.com/photo-1621438578138-3cf41ebf9e09?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1621438578138-3cf41ebf9e09?auto=format&fit=crop&w=1200&q=80',
                    asset('images/about-vision.png'),
                ],
                'description' => 'Highly charismatic cockatoo with vibrant crest and strong social behavior.',
                'details' => 'Age: 10 months. Feed: premium mixed nuts and fruits. Temperament: active.',
                'shipping' => 'Air freight with quarantine and full veterinary checks before departure.',
                'other' => 'Availability depends on seasonal breeding and legal permit approval.',
            ],
            [
                'slug' => 'rhinoceros-hornbill',
                'name' => 'Rhinoceros Hornbill',
                'latin' => 'Buceros rhinoceros',
                'category' => 'aves',
                'subcategory' => 'Hornbill',
                'price' => '$4,250',
                'image' => 'https://images.unsplash.com/photo-1516632664305-eda5d93dd409?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1516632664305-eda5d93dd409?auto=format&fit=crop&w=1200&q=80',
                    asset('images/catalog.jpeg'),
                ],
                'description' => 'Iconic hornbill species with remarkable casque and strong pair-bonding character.',
                'details' => 'Age: 18 months. Diet: fruit dominant with protein supplementation.',
                'shipping' => 'Export process follows CITES and destination-specific authority requirements.',
                'other' => 'Recommended for licensed facilities and experienced keepers.',
            ],
            [
                'slug' => 'fennec-fox',
                'name' => 'Fennec Fox',
                'latin' => 'Vulpes zerda',
                'category' => 'mammals',
                'subcategory' => 'Viverridae',
                'price' => '$3,100',
                'image' => 'https://images.unsplash.com/photo-1598755257130-c2aaca1f061c?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1598755257130-c2aaca1f061c?auto=format&fit=crop&w=1200&q=80',
                    asset('images/whoweare.png'),
                ],
                'description' => 'Small desert-adapted mammal known for its large ears and calm demeanor.',
                'details' => 'Age: 8 months. Health: complete checkup. Origin: captive breeding center.',
                'shipping' => 'Shipped with species-appropriate enclosure and temperature control.',
                'other' => 'Buyers must confirm local ownership legality before booking.',
            ],
            [
                'slug' => 'sugar-glider',
                'name' => 'Sugar Glider',
                'latin' => 'Petaurus breviceps',
                'category' => 'mammals',
                'subcategory' => 'Marsupilia',
                'price' => '$950',
                'image' => 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1548767797-d8c844163c4c?auto=format&fit=crop&w=1200&q=80',
                    asset('images/whoweare.png'),
                ],
                'description' => 'Active nocturnal marsupial with sociable behavior and excellent adaptability.',
                'details' => 'Age: 6 months. Pairing option: available. Diet guidance included.',
                'shipping' => 'Live transport arranged with dedicated carrier and overnight handling.',
                'other' => 'Best adopted in pairs for social well-being.',
            ],
            [
                'slug' => 'angora-rabbit',
                'name' => 'Angora Rabbit',
                'latin' => 'Oryctolagus cuniculus',
                'category' => 'mammals',
                'subcategory' => 'Leporidae',
                'price' => '$480',
                'image' => 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?auto=format&fit=crop&w=1200&q=80',
                ],
                'description' => 'Premium wool rabbit line with strong health profile and gentle temperament.',
                'details' => 'Age: 7 months. Grooming schedule and feed recommendation available.',
                'shipping' => 'Domestic and international routes available depending on permits.',
                'other' => 'Suitable for breeding and companion programs.',
            ],
            [
                'slug' => 'green-tree-python',
                'name' => 'Green Tree Python',
                'latin' => 'Morelia viridis',
                'category' => 'reptiles',
                'subcategory' => 'Snake',
                'price' => '$1,650',
                'image' => 'https://images.unsplash.com/photo-1531386151447-fd76ad50012f?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1531386151447-fd76ad50012f?auto=format&fit=crop&w=1200&q=80',
                    asset('images/reptil.jpeg'),
                ],
                'description' => 'Arboreal python with striking green coloration and excellent display quality.',
                'details' => 'Age: 14 months. Feeding response: good. Captive-bred line.',
                'shipping' => 'Specialized reptile transport with climate-controlled packaging.',
                'other' => 'Handling recommended by experienced keepers only.',
            ],
            [
                'slug' => 'leopard-gecko',
                'name' => 'Leopard Gecko',
                'latin' => 'Eublepharis macularius',
                'category' => 'reptiles',
                'subcategory' => 'Gecko',
                'price' => '$420',
                'image' => 'https://images.unsplash.com/photo-1604161988974-647a7f85d5f2?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1604161988974-647a7f85d5f2?auto=format&fit=crop&w=1200&q=80',
                    asset('images/reptil.jpeg'),
                ],
                'description' => 'Hardy gecko species with stable feeding and strong adaptation for display enclosures.',
                'details' => 'Age: 9 months. Morph: classic. Feeding notes and supplement plan included.',
                'shipping' => 'Packed with thermal safety protocol for short and medium transit routes.',
                'other' => 'Excellent entry-level reptile for licensed collections.',
            ],
            [
                'slug' => 'sulcata-tortoise',
                'name' => 'Sulcata Tortoise',
                'latin' => 'Centrochelys sulcata',
                'category' => 'reptiles',
                'subcategory' => 'Tortoise',
                'price' => '$780',
                'image' => 'https://images.unsplash.com/photo-1627247341456-21853440c8da?auto=format&fit=crop&w=900&q=80',
                'gallery' => [
                    'https://images.unsplash.com/photo-1627247341456-21853440c8da?auto=format&fit=crop&w=1200&q=80',
                    asset('images/reptil.jpeg'),
                ],
                'description' => 'Robust tortoise species with excellent captive growth and stable feeding routine.',
                'details' => 'Age: 11 months. Shell condition: excellent. Diet plan available.',
                'shipping' => 'Ground and air options available with secure and ventilated crates.',
                'other' => 'Long-term habitat planning is strongly recommended.',
            ],
            [
                'slug' => 'blue-gold-macaw-hybrid',
                'name' => 'Blue Gold Macaw Hybrid',
                'latin' => 'Ara ararauna x Ara macao',
                'category' => 'hybrid',
                'subcategory' => 'Macaw',
                'price' => '$3,650',
                'image' => asset('images/hybrid.jpeg'),
                'gallery' => [
                    asset('images/hybrid.jpeg'),
                    asset('images/about-vision.png'),
                ],
                'description' => 'Hybrid macaw line with vibrant color mix, bred from high-quality parent stock.',
                'details' => 'Age: 13 months. Parent lineage data available upon request.',
                'shipping' => 'Handled with premium crate setup and destination compliance process.',
                'other' => 'Best for collectors seeking rare color mutations.',
            ],
            [
                'slug' => 'scarlet-hybrid-macaw',
                'name' => 'Scarlet Hybrid Macaw',
                'latin' => 'Ara macao hybrid line',
                'category' => 'hybrid',
                'subcategory' => 'Macaw',
                'price' => '$3,450',
                'image' => asset('images/hybrid.jpeg'),
                'gallery' => [
                    asset('images/hybrid.jpeg'),
                    asset('images/about-vision.png'),
                ],
                'description' => 'Bright scarlet-toned hybrid macaw with premium feather quality.',
                'details' => 'Age: 12 months. Temperament: socialized and handler-trained.',
                'shipping' => 'Export-ready with veterinary certificate and health records.',
                'other' => 'Limited stock per breeding cycle.',
            ],
        ];
    }

    public static function countries(): array
    {
        return [
            'Indonesia', 'Singapore', 'Malaysia', 'Thailand', 'Vietnam', 'Philippines',
            'United Arab Emirates', 'Qatar', 'Japan', 'South Korea', 'Australia',
            'United Kingdom', 'Germany', 'Netherlands', 'France', 'United States',
        ];
    }
}
