<?php

namespace Database\Seeders;

use App\Models\LandingPageItem;
use App\Models\LandingPageSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LandingPagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->settings() as $page => $settings) {
            foreach ($settings as $key => $value) {
                LandingPageSetting::firstOrCreate(
                    ['page' => $page, 'key' => $key],
                    str_ends_with($key, '_image') || preg_match('/_image_\d$/', $key)
                        ? ['asset_path' => $value]
                        : ['value_en' => $value]
                );
            }
        }

        foreach ($this->items() as $page => $sections) {
            foreach ($sections as $section => $items) {
                foreach ($items as $index => $item) {
                    $source = $item['title_en'] ?? $item['description_en'] ?? 'item';
                    $key = $item['item_key'] ?? substr(Str::slug($source), 0, 80).'-'.($index + 1);
                    LandingPageItem::firstOrCreate(
                        ['page' => $page, 'section' => $section, 'item_key' => $key],
                        array_merge($item, ['sort_order' => $item['sort_order'] ?? $index + 1, 'is_active' => true])
                    );
                }
            }
        }
    }

    private function settings(): array
    {
        return [
            'about' => [
                'hero_eyebrow' => 'About',
                'hero_title' => "Tunas Sejahtera\nAdhi Perkasa",
                'hero_description' => 'Committed to wildlife conservation, sustainable breeding, and responsible international trade for a better future',
                'hero_image' => 'images/about-banner-nicobar-pigeon.png',
                'hero_image_alt' => 'Nicobar pigeon in its natural habitat',
                'hero_position' => '78% top',
                'about_label' => 'About Us',
                'about_heading' => 'Who We Are',
                'about_paragraph_1' => 'PT. Tunas Sejahtera Adhiperkasa is one of the companies that supports the Government program in the field of Animal Husbandry, officially registered with the Indonesian Government, and committed to educating the public about the importance of understanding our environment in order to preserve wildlife habitats.',
                'about_paragraph_2' => 'We breed protected and non-protected animals and open opportunities for cooperation and partnerships with other companies or institutions that support our primary mission of preserving animals worldwide.',
                'about_image' => 'images/whoweare.png',
                'about_image_alt' => 'Tunas Sejahtera Adhi Perkasa breeding center',
                'vision_mission_label' => 'Vision & Mission',
                'vision_mission_heading' => 'Vision & Mission',
                'vision_title' => 'Our Vision',
                'vision_description' => 'To become a leading and trusted global wildlife breeding center company in the legal wildlife trade and contribute to the sustainable conservation of biodiversity, especially animals.',
                'vision_image' => 'images/about-vision-sumatran-rabbit.png',
                'vision_image_alt' => 'Sumatran striped rabbit in rainforest',
                'mission_title' => 'Our Mission',
                'mission_image' => 'images/about-mission-linsang.png',
                'mission_image_alt' => 'Banded linsang in rainforest',
                'leadership_label' => 'Company Founder',
                'leadership_heading' => 'Our Leadership',
                'leadership_paragraph_1' => 'The founder of PT. Tunas Sejahtera Adhiperkasa started from the love of Mrs. Rina Fitriani (President Commissioner) for cattle which over time her livestock increased, because of her love for animals she added several exotic animal collections, so she had many exotic animals that were traditionally farmed and over time her livestock population increased from day to day, based on her success in raising animals on January 13, 2020 she registered the animal breeding company with a notary so that it has a legal basis and official legality from the government and the authorities.',
                'leadership_paragraph_2' => 'PT Tunas Sejahtera Adhiperkasa is a company engaged in the Breeding Center and international wildlife trade (export and import) of various types of animals including Birds, Mammals, and Reptiles on a global scale, as an officially certified business actor in the wildlife trade industry, we carry out all activities in accordance with applicable national and international regulations, including those stipulated by CITES (Convention on International Trade in Endangered Species of Wild Fauna and Flora).',
            ],
            'information_logistic' => [
                'hero_eyebrow' => 'Information › Logistic & Delivery',
                'hero_title' => 'Logistic & Delivery',
                'hero_description' => 'Safe, professional and reliable logistics solutions for the live animal transportation worldwide',
                'hero_image' => 'images/banner-logistic-delivery.png',
                'hero_image_alt' => 'Live animal logistics and delivery',
                'hero_position' => 'center center',
                'commitment_label' => 'Our Commitment',
                'commitment_paragraph_1' => 'We professionally oversee the loading and shipping of animals in optimal conditions to meet our customers needs. As a token of our commitment to government certification, we can also provide technical support and guidance to optimize animal health, welfare, and performance. Our experience and network enable us to efficiently select quality animals for orders of varying sizes.',
                'commitment_paragraph_2' => 'We are passionate about delivering quality, adaptable products that positively contribute to our customers goals through careful preparation and a focus on the health and well-being of our customers and your animals.',
                'commitment_image' => 'images/logistic-commitment.png',
                'commitment_image_alt' => 'Our commitment logistics process',
                'air_heading' => 'Air Freight Services',
                'air_note' => 'Our air freight partners and routes are carefully selected to ensure the fastest, safest, and most comfortable travel for the animals. All shipments are handled in accordance with IATA Live Animals Regulations.',
                'sea_heading' => 'Sea Freight Services',
                'sea_note' => 'We work with trusted shipping lines and experienced logistics partners to ensure the welfare of the animals throughout the sea journey and compliance with international regulations.',
            ],
            'information_procurement' => [
                'hero_eyebrow' => 'Information › Procurement & Preparation',
                'hero_title' => 'Procurement & Preparation',
                'hero_description' => '',
                'hero_image' => 'images/procurement-banner-new.png',
                'hero_image_alt' => 'Procurement and preparation',
                'hero_position' => 'center center',
                'commitment_heading' => 'Our Commitment',
                'commitment_paragraph_1' => 'After careful selection of animals, we tailor our approach to preparation, continually reviewing and refining our stringent quality assurance procedures to ensure livestock are prepared not only in accordance with the regulatory and animal welfare requirements of the exporting and importing countries, but also to ensure safe and secure transportation and optimal animal performance for our customers production systems.',
                'commitment_paragraph_2' => 'All animals are identified with a closed ring or individual electronic tag (microchip) connected to a database to record their ancestry, breed, health and welfare data.',
                'commitment_image_1' => 'images/procurement-commitment-1.png',
                'commitment_image_1_alt' => 'Parrot inspection close-up',
                'commitment_image_2' => 'images/procurement-commitment-2.png',
                'commitment_image_2_alt' => 'Digital animal identification scanner',
                'sources_heading' => 'Sources of Livestock',
                'sources_paragraph_1' => 'We work closely with accredited breeding facilities, conservation organizations, and certified breeders to obtain healthy, ethically bred, and high-quality animals.',
                'sources_paragraph_2' => 'All breeding partners are carefully selected based on their reputation, facilities, animal welfare practices, and compliance with national and international regulations.',
                'sources_image' => 'images/sources-side.png',
                'sources_image_alt' => 'Breeding center aerial view',
                'sources_note' => 'Our procurement and preparation processes are designed to ensure the highest standards of animal health, welfare, and safety before export.',
                'standards_heading' => 'Our Preparation Standards',
            ],
            'information_live_export' => [
                'hero_eyebrow' => 'Information › Live Export Process',
                'hero_title' => 'Live Export Process',
                'hero_description' => 'A clear and transparent process to ensure safe, legal and ethical live animal export',
                'hero_image' => 'images/live-export-banner-new.png',
                'hero_image_alt' => 'Live export handling process',
                'hero_position' => 'center center',
                'intro' => 'We are committed to conducting live animal exports in full compliance with national and international regulations, including CITES, IATA Live Animals Regulations (LAR), and the animal welfare standards of importing countries. Below is our step-by-step export process from start to finish.',
                'commitment_heading' => 'Our Commitment',
                'commitment_description' => 'We ensure every process is conducted with the highest standards of animal welfare, safety, and full compliance with all applicable laws and regulations.',
            ],
            'future_projects' => [
                'hero_eyebrow' => 'Future Projects',
                'hero_title' => 'Future Projects',
                'hero_description' => 'Strategic development plans to strengthen our legal, certified, and sustainable wildlife breeding operations.',
                'hero_image' => 'images/procurement-banner-new.png',
                'hero_image_alt' => 'Future wildlife breeding projects',
                'hero_position' => 'center center',
                'section_label' => 'Future Projects',
                'section_heading' => 'Development Roadmap',
            ],
            'gallery' => [
                'hero_eyebrow' => 'Gallery',
                'hero_title' => 'Our Gallery',
                'hero_description' => 'A preview collection of our wildlife, breeding center, and export handling activities.',
                'hero_image' => 'images/catalog-banner.png',
                'hero_image_alt' => 'TSA wildlife gallery',
                'hero_position' => 'center center',
                'section_label' => 'Gallery',
                'section_heading' => 'Photo Collection',
            ],
        ];
    }

    private function items(): array
    {
        $step = fn (string $title, string $description, ?string $image = null, ?string $alt = null) => [
            'title_en' => $title, 'description_en' => $description, 'image_path' => $image,
            'metadata' => $alt ? ['alt' => $alt] : null,
        ];

        return [
            'about' => ['mission_items' => [
                ['description_en' => 'Build strategic partnerships with governments, conservation institutions, certified breeders, and official organizations worldwide.'],
                ['description_en' => 'Support global biodiversity conservation through responsible and sustainable wildlife trade practices.'],
                ['description_en' => 'Provide professional, reliable, and high-quality services while ensuring security for clients worldwide.'],
            ]],
            'information_logistic' => [
                'air_steps' => [
                    $step('Animal Pickup', 'Careful collection of animals from our breeding center with strict handling procedures.', 'images/air-freight-01.png', 'Animal pickup'),
                    $step('Airport Handling', 'Professional handling and transfer of animals to the airport cargo facility.', 'images/airport-handling.png', 'Airport handling'),
                    $step('Loading Process', 'Animals are loaded into the aircraft under optimal conditions with temperature control.', 'images/air-freight-03.png', 'Aircraft loading process'),
                    $step('Safe Delivery', 'We ensure safe and timely delivery to the destination airport with full compliance.', 'images/air-freight-04.png', 'Safe animal delivery'),
                ],
                'sea_steps' => [
                    $step('Container Preparation', 'Specialized containers are prepared with ventilation, temperature control, and appropriate flooring.', 'images/sea-freight-01.png', 'Container preparation'),
                    $step('Loading & Securing', 'Animals are carefully loaded and secured to ensure safety during the voyage.', 'images/sea-freight-02.png', 'Loading and securing'),
                    $step('Ocean Transport', 'Shipment via reliable shipping lines with regular monitoring and care during transit.', 'images/sea-freight-03.png', 'Ocean transport'),
                    $step('Arrival & Customs Clearance', 'Coordination with local agents for smooth customs clearance and final delivery.', 'images/sea-freight-04.png', 'Arrival and customs clearance'),
                ],
            ],
            'information_procurement' => [
                'quality_items' => [
                    $step('Quality Assurance', 'Strict quality control at every stage.'),
                    $step('Animal Welfare', 'We prioritize the health and well-being.'),
                    $step('Individual Identification', 'Microchip tracking for complete traceability.'),
                    $step('Regulatory Compliance', 'Meet international regulations & standards.'),
                ],
                'source_cards' => [
                    $step('Accredited Breeding Partners', '', 'images/source-card-01.png', 'Accredited breeding partners'),
                    $step('Ethical Breeding Practices', '', 'images/source-card-02.png', 'Ethical breeding practices'),
                    $step('Health & Genetic Screening', '', 'images/source-card-03.png', 'Health and genetic screening'),
                    $step('Hygiene & Biosecurity', '', 'images/source-card-04.png', 'Hygiene and biosecurity'),
                    $step('Continuous Monitoring', '', 'images/source-card-05.png', 'Continuous monitoring'),
                ],
                'standards' => [
                    $step('Health Check', 'Comprehensive veterinary examination and health certification.'),
                    $step('Vaccination', 'Appropriate vaccinations according to destination country requirements.'),
                    $step('Nutrition', 'Balanced diet to ensure optimal condition and stamina.'),
                    $step('Quarantine', 'Isolation and monitoring to prevent disease transmission.'),
                    $step('Export Ready', 'Fully prepared and certified for safe international transportation.'),
                ],
            ],
            'information_live_export' => [
                'steps' => [
                    $step('Initial Consultation', 'We discuss your needs, specifications, quantity, and logistics requirements. A detailed proposal will be sent within 21 days.', 'images/live-step-01.png', 'Initial consultation'),
                    $step('Contract Agreement', 'A comprehensive contract is reviewed and signed by both parties before any work begins.', 'images/live-step-02.png', 'Contract agreement'),
                    $step('Pro Forma Invoice', 'The Pro Forma Invoice will be issued after the contract is signed. An initial payment is required to secure your order.', 'images/live-step-03.png', 'Pro forma invoice'),
                    $step('Import Permits', 'We handle the import permit process in the destination country. Requirements may vary depending on each country\'s regulations.', 'images/live-step-04.png', 'Import permits'),
                    $step('Livestock Procurement Begins', 'Selection and preparation of animals begin in accordance with the agreed specifications.', 'images/live-step-05.png', 'Livestock procurement'),
                    $step('Selection and On Farm Testing', 'Genetic, physical, health, and temperament evaluations are conducted for approximately 2 weeks.', 'images/live-step-06.png', 'On farm testing'),
                    $step('Animal Quarantine', 'Animals are transferred to a government-licensed quarantine facility.', 'images/live-step-07.png', 'Animal quarantine'),
                    $step('Vet Checks, Testing and Vaccination', 'Veterinary examinations, laboratory testing, and vaccinations are carried out during the quarantine period.', 'images/live-step-08.png', 'Veterinary checks'),
                    $step('Final Payment', 'Final payment is made to confirm air shipment and government export booking. All shipments are CIP (Carriage and Insurance Paid).', 'images/live-step-09.png', 'Final payment'),
                    $step('Final Government Vet Inspection and Delivery', 'Final inspection by government veterinarian, delivery to destination port/airport, coordination with import agent, and all documents are sent 48 hours before shipment.', 'images/live-step-10.png', 'Final inspection and delivery'),
                ],
                'highlights' => array_map(fn ($title) => ['title_en' => $title], ['Legal Compliance', 'Animal Welfare', 'Quality Assurance', 'Safe Delivery']),
            ],
            'future_projects' => ['projects' => [
                ['description_en' => 'In the process of registering Appendix I permits at the CITES International organization.'],
                ['description_en' => 'In the licensing process for private animal quarantine facilities at the Indonesian Quarantine Agency.'],
            ]],
            'gallery' => ['items' => [
                $this->galleryItem('Aves Collection', 'Wildlife', 'images/nicobar-pigeon.png'),
                $this->galleryItem('Mamalia Collection', 'Wildlife', 'images/binturong.png'),
                $this->galleryItem('Reptile Collection', 'Wildlife', 'images/reptil.jpeg'),
                $this->galleryItem('Hybrid & Mutation', 'Wildlife', 'images/hybrid.jpeg'),
                $this->galleryItem('Breeding Center', 'Facility', 'images/whoweare.png'),
                $this->galleryItem('Airport Handling', 'Logistic', 'images/airport-handling.png'),
                $this->galleryItem('Procurement Process', 'Preparation', 'images/procurement-commitment-1.png'),
                $this->galleryItem('Live Export Process', 'Export', 'images/live-step-10.png'),
                $this->galleryItem('Sea Freight Handling', 'Logistic', 'images/sea-freight-03.png'),
            ]],
        ];
    }

    private function galleryItem(string $title, string $category, string $image): array
    {
        return ['title_en' => $title, 'image_path' => $image, 'metadata' => ['category' => $category, 'alt' => $title]];
    }
}
