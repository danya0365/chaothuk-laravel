<?php

/**
 * Mock image catalog — photorealistic photos generated locally via ComfyUI
 * (flux-schnell Q4 GGUF). See app/Console/Commands/GenerateMockImages.php.
 *
 * Each spec:
 *  - slug:   filename base (deterministic seed = crc32(slug))
 *  - title:  Thai label for CLI progress logging
 *  - prompt: positive-only realistic prompt (flux has no negative prompt, cfg=1)
 */

return [
    // ComfyUI server (flux-schnell Q4 GGUF workflow)
    'comfyui_url' => env('COMFYUI_URL', 'http://127.0.0.1:8188'),

    // canonical generated assets (gitignored) — copied to storage at seed time
    'output_dir' => database_path('images/mock'),

    // serving dir under storage/app/public → DB value "storage/{public_base}/..."
    'public_base' => 'mock',

    'sizes' => [
        'card'   => ['width' => 1024, 'height' => 768],   // work/recruit/portfolio
        'banner' => ['width' => 1344, 'height' => 576],   // cropped to 1200x400
    ],

    // WorkType.title => bucket slug prefix
    'work_type_slugs' => [
        'รถกะบะ'     => 'pickup',
        'รถบรรทุก'   => 'truck',
        'รถสิบล้อ'    => 'tenwheel',
        'มอเตอร์ไซค์' => 'motorbike',
    ],

    'buckets' => [

        // ─── Works: 24 (4 types × 6) ───────────────────────────────────────
        'work' => [
            ['slug' => 'pickup-1', 'title' => 'กะบะโหลดของ ลานกลางแจ้ง', 'prompt' => 'photorealistic photo of a white Isuzu D-Max pickup truck parked on a concrete service yard in Thailand, two Thai workers in short-sleeve shirts loading cardboard boxes into the cargo bed, bright daylight, sharp details, 35mm lens'],
            ['slug' => 'pickup-2', 'title' => 'กะบะขนย้าย ขับชนบท', 'prompt' => 'photorealistic photo of a white pickup truck carrying furniture and a mattress in its cargo bed driving on a rural Thai road, green rice fields and palm trees, midday sun, motion in the wheels, high detail'],
            ['slug' => 'pickup-3', 'title' => 'กะบะตลาดสด โหลดผัก', 'prompt' => 'photorealistic photo of a pickup truck parked at a Thai fresh market in the morning loading crates of vegetables, market stalls with tarps in the background, soft morning light, realistic texture'],
            ['slug' => 'pickup-4', 'title' => 'กะบะส่งแอร์หน้าบ้าน', 'prompt' => 'photorealistic photo of a white pickup truck delivering a large appliance box to a suburban Thai house, driver in a blue polo shirt carrying the box from the truck bed, tropical garden, daylight'],
            ['slug' => 'pickup-5', 'title' => 'กะบะกลางคืน โหลดข้าวสาร', 'prompt' => 'photorealistic photo of a pickup truck parked at night under a streetlight in front of a closed Thai shop, workers loading rice bags into the cargo bed, warm tungsten lighting, night atmosphere'],
            ['slug' => 'pickup-6', 'title' => 'กะบะทางหลวง', 'prompt' => 'photorealistic photo of a white pickup truck cruising on an empty Thai highway, overcast sky, green roadside, slight motion blur on the road, clean composition, high detail'],

            ['slug' => 'truck-1', 'title' => 'รถบรรทุก 6 ล้อทางหลวง', 'prompt' => 'photorealistic photo of a six-wheel box truck with a closed cargo body driving on an empty Thai highway, rolling countryside hills, driver in a blue polo shirt visible, clear sunny day, sharp detail'],
            ['slug' => 'truck-2', 'title' => 'รถบรรทุกโหลดที่โกดัง', 'prompt' => 'photorealistic photo of a six-wheel box truck backing into a warehouse loading dock, two workers with a hand truck unloading cardboard cartons, industrial setting, daylight'],
            ['slug' => 'truck-3', 'title' => 'รถบรรทุกตลาดเช้า', 'prompt' => 'photorealistic photo of a six-wheel truck parked at an early morning Thai wholesale market unloading vegetable crates, vendors arranging produce, misty morning light, realistic scene'],
            ['slug' => 'truck-4', 'title' => 'รถบรรทุกฝนตก', 'prompt' => 'photorealistic photo of a six-wheel box truck driving on a wet road in light rain in Thailand, windshield wipers, overcast sky, water splashes, moody realistic weather'],
            ['slug' => 'truck-5', 'title' => 'รถบรรทุกหน้าโรงงาน', 'prompt' => 'photorealistic photo of a six-wheel truck parked at a factory gate with shipping containers, security guard booth beside it, industrial concrete yard, bright afternoon'],
            ['slug' => 'truck-6', 'title' => 'รถบรรทุกเย็นค่ำ', 'prompt' => 'photorealistic photo of a six-wheel truck driving on a Thai highway at dusk with headlights on, orange skyline in the background, long exposure feel, realistic night driving scene'],

            ['slug' => 'tenwheel-1', 'title' => 'สิบล้อขนเหล็กไซต์ก่อสร้าง', 'prompt' => 'photorealistic photo of a ten-wheel truck loaded with steel pipes parked at a construction site, workers securing straps over the load, dirt and gravel ground, bright daylight'],
            ['slug' => 'tenwheel-2', 'title' => 'สิบล้อขนดิน ทางลูกรัง', 'prompt' => 'photorealistic photo of a ten-wheel dump truck carrying soil on a rural dirt road in Thailand, dust clouds behind, late afternoon golden light, powerful realistic truck photo'],
            ['slug' => 'tenwheel-3', 'title' => 'สิบล้อรถพ่วงทางหลวง', 'prompt' => 'photorealistic photo of a ten-wheel trailer truck with a fuel tank on an open Thai highway, wide landscape view, blue sky with clouds, sunny day, high detail'],
            ['slug' => 'tenwheel-4', 'title' => 'สิบล้อโหลดพาเลท', 'prompt' => 'photorealistic photo of a ten-wheel truck at a logistics warehouse dock, forklift loading wooden pallets into the cargo bed, workers in reflective vests, daylight'],
            ['slug' => 'tenwheel-5', 'title' => 'สิบล้อพักกลางคืน', 'prompt' => 'photorealistic photo of a ten-wheel truck parked at a highway rest area at night, cabin interior lit, fuel station lights in the background, realistic night scene'],
            ['slug' => 'tenwheel-6', 'title' => 'สิบล้อข้ามสะพาน', 'prompt' => 'photorealistic photo of a ten-wheel truck crossing a concrete bridge over a wide river in Thailand, elevated wide view, morning haze, realistic transport photography'],

            ['slug' => 'motorbike-1', 'title' => 'วินมอเตอร์ไซค์ส่งของถนนกรุงเทพ', 'prompt' => 'photorealistic photo of a Thai motorcycle courier with a large delivery box strapped to the rear seat riding on a busy Bangkok street, helmet on, slight motion blur of traffic behind, urban atmosphere'],
            ['slug' => 'motorbike-2', 'title' => 'วินมอเตอร์ไซค์ซอยแคบ', 'prompt' => 'photorealistic photo of a motorcycle courier riding through a narrow Bangkok alley between shophouses, baskets full of packages, morning light, realistic Thai street scene'],
            ['slug' => 'motorbike-3', 'title' => 'วินมอเตอร์ไซค์รอหน้าร้านอาหาร', 'prompt' => 'photorealistic photo of a food delivery motorcycle with an insulated delivery box parked in front of a Thai restaurant, rider waiting by the door, warm ambient light, detailed urban scene'],
            ['slug' => 'motorbike-4', 'title' => 'วินมอเตอร์ไซค์ฝนตก', 'prompt' => 'photorealistic photo of a motorcycle courier in a raincoat riding through heavy rain on a Thai city street, water splashing, blurred bokeh city lights, dramatic realistic weather shot'],
            ['slug' => 'motorbike-5', 'title' => 'วินมอเตอร์ไซค์กลางคืน', 'prompt' => 'photorealistic photo of a motorcycle courier at night with the headlight on, neon signs glowing on a Bangkok street, urban night atmosphere, sharp rider detail'],
            ['slug' => 'motorbike-6', 'title' => 'วินมอเตอร์ไซค์บรรทุกพัสดุ', 'prompt' => 'photorealistic photo of a courier packing cardboard packages into a large motorcycle cargo box at a small Thai delivery shop, straps and bags, daylight, realistic street photo'],
        ],

        // ─── Recruits: 24 (4 types × 6) — hiring/availability angle ────────
        'recruit' => [
            ['slug' => 'pickup-1', 'title' => 'หาคนขับกะบะ หน้ารวมพนักงาน', 'prompt' => 'photorealistic photo of a clean white pickup truck parked in a company yard, a Thai driver in a neat uniform standing beside it holding a clipboard, other employees in the background, bright day, job hiring vibe'],
            ['slug' => 'pickup-2', 'title' => 'หาคนขับกะบะ ประจำบริษัท', 'prompt' => 'photorealistic photo of a white pickup truck parked in a logistics company parking lot, driver uniform hanging beside the truck, office building behind, clear daylight, realistic workplace photo'],
            ['slug' => 'pickup-3', 'title' => 'หาคนขับกะบะ จอดหน้าร้าน', 'prompt' => 'photorealistic photo of a pickup truck parked in front of a Thai hardware store ready for hire, empty cargo bed, storefront sign soft blurred, morning light'],
            ['slug' => 'pickup-4', 'title' => 'หาคนขับกะบะ ตรวจสภาพรถ', 'prompt' => 'photorealistic photo of a mechanic inspecting the engine of a white pickup truck with the hood open in a service garage, tools on the workbench, realistic workshop lighting'],
            ['slug' => 'pickup-5', 'title' => 'หาคนขับกะบะ มือใหม่', 'prompt' => 'photorealistic photo of a young Thai driver standing confidently next to a parked pickup truck, holding a drivers license and key, parking lot background, natural daylight, hiring advertisement feel'],
            ['slug' => 'pickup-6', 'title' => 'หาคนขับกะบะ ฟาร์ม', 'prompt' => 'photorealistic photo of a pickup truck parked at a Thai farm gate, farmer loading feed sacks, green fields behind, early morning light, realistic rural scene'],

            ['slug' => 'truck-1', 'title' => 'หาคนขับรถบรรทุก หน้าโกดัง', 'prompt' => 'photorealistic photo of a six-wheel box truck parked in front of a warehouse, a Thai driver in a blue uniform standing at the open cargo door, stacks of boxes visible, daylight, hiring atmosphere'],
            ['slug' => 'truck-2', 'title' => 'หาคนขับรถบรรทุก บริษัทขนส่ง', 'prompt' => 'photorealistic photo of a six-wheel truck parked at a transport company depot with several trucks lined up, drivers in safety vests chatting, morning light, realistic logistics scene'],
            ['slug' => 'truck-3', 'title' => 'หาคนขับรถบรรทุก ตรวจยาง', 'prompt' => 'photorealistic photo of a worker checking the tires of a six-wheel truck with a tire gauge at a roadside garage in Thailand, tools laid out, warm afternoon light'],
            ['slug' => 'truck-4', 'title' => 'หาคนขับรถบรรทุก เขตก่อสร้าง', 'prompt' => 'photorealistic photo of a six-wheel box truck at a construction site entrance, safety cones and a signboard, site engineer talking to the driver, dust in the air, daylight'],
            ['slug' => 'truck-5', 'title' => 'หาคนขับรถบรรทุก ตลาดค้าส่ง', 'prompt' => 'photorealistic photo of a six-wheel truck at a Thai wholesale market, merchant waving to the driver at the open cargo door, crates stacked, morning market bustle'],
            ['slug' => 'truck-6', 'title' => 'หาคนขับรถบรรทุก ป้ายรับสมัคร', 'prompt' => 'photorealistic photo of a six-wheel truck parked by a Thai roadside with a recruitment signboard on a stand beside it, highway and fields in background, clear day, hiring scene'],

            ['slug' => 'tenwheel-1', 'title' => 'หาคนขับสิบล้อ หน้าเหมือง', 'prompt' => 'photorealistic photo of a ten-wheel truck parked at a quarry entrance, driver in a reflective vest standing by the cab, dust and gravel, mountains of sand behind, bright daylight'],
            ['slug' => 'tenwheel-2', 'title' => 'หาคนขับสิบล้อ คุมน้ำหนัก', 'prompt' => 'photorealistic photo of a ten-wheel truck on a weighbridge scale at a Thai logistics checkpoint, staff checking a tablet, open sky, midday, realistic industrial photo'],
            ['slug' => 'tenwheel-3', 'title' => 'หาคนขับสิบล้อ โรงงานปูน', 'prompt' => 'photorealistic photo of a ten-wheel truck at a cement factory loading station, cement silos in the background, worker in hard hat guiding the driver, dusty daylight'],
            ['slug' => 'tenwheel-4', 'title' => 'หาคนขับสิบล้อ ป้ายรถบรรทุก', 'prompt' => 'photorealistic photo of a row of ten-wheel trucks parked at a Thai transport depot, drivers walking between trucks with documents, warm late afternoon light, realistic depot scene'],
            ['slug' => 'tenwheel-5', 'title' => 'หาคนขับสิบล้อ กลางคืนปั๊ม', 'prompt' => 'photorealistic photo of a ten-wheel truck refueling at a highway fuel station at night, bright station lights, driver in the foreground, realistic night fuel stop'],
            ['slug' => 'tenwheel-6', 'title' => 'หาคนขับสิบล้อ ริมเขื่อน', 'prompt' => 'photorealistic photo of a ten-wheel truck parked on a gravel road beside a large Thai dam lake, calm water, hills, morning haze, wide realistic landscape'],

            ['slug' => 'motorbike-1', 'title' => 'หาพนักงานวิน ร้านส่งของ', 'prompt' => 'photorealistic photo of a motorcycle with a large delivery box parked outside a Thai delivery service shop, a young rider in a branded shirt holding a helmet, daylight, hiring advertisement feel'],
            ['slug' => 'motorbike-2', 'title' => 'หาพนักงานวิน ถนนธุรกิจ', 'prompt' => 'photorealistic photo of a Thai motorcycle courier standing by his delivery motorcycle on a business district street, office buildings behind, wearing a courier vest, clear day'],
            ['slug' => 'motorbike-3', 'title' => 'หาพนักงานวิน โชว์ห่วย', 'prompt' => 'photorealistic photo of a clean delivery motorcycle parked at a shop display in Bangkok, empty cargo box open, bright store light, realistic urban retail scene'],
            ['slug' => 'motorbike-4', 'title' => 'หาพนักงานวิน ช่วงเช้า', 'prompt' => 'photorealistic photo of a motorcycle courier packing his delivery box with parcels at sunrise in front of a small Thai shop, warm morning light, fresh start atmosphere'],
            ['slug' => 'motorbike-5', 'title' => 'หาพนักงานวิน ซ้อมแซง', 'prompt' => 'photorealistic photo of a motorcycle courier riding through Bangkok traffic, wearing a bright courier shirt, sharp focus on the rider, blurred cars around, dynamic realistic shot'],
            ['slug' => 'motorbike-6', 'title' => 'หาพนักงานวิน จอดริมฟุตบาท', 'prompt' => 'photorealistic photo of a delivery motorcycle parked at a Bangkok sidewalk with packages stacked beside it, rider checking a phone, shophouses in the background, midday'],
        ],

        // ─── Portfolios: 8 — completed work samples ─────────────────────────
        'portfolio' => [
            ['slug' => 'house-move', 'title' => 'ผลงานขนย้ายบ้าน', 'prompt' => 'photorealistic photo of Thai movers carrying furniture out of a house into a moving van on a suburban street, mattress and boxes, teamwork, bright daylight, realistic photo'],
            ['slug' => 'furniture-move', 'title' => 'ผลงานขนเฟอร์นิเจอร์', 'prompt' => 'photorealistic photo of two movers wrapping a sofa in plastic and carrying it through a doorway of a Thai house, careful handling, indoor daylight, detailed realistic shot'],
            ['slug' => 'aircon-install', 'title' => 'ผลงานติดแอร์', 'prompt' => 'photorealistic photo of a Thai technician installing an air conditioner unit on a wall with a ladder, tools and vacuum pump beside him, clean indoor setting, daylight'],
            ['slug' => 'house-paint', 'title' => 'ผลงานทาสีบ้าน', 'prompt' => 'photorealistic photo of a Thai painter on a ladder painting a house wall with a roller, paint buckets and drop cloths below, bright tropical day, realistic work photo'],
            ['slug' => 'plumbing-fix', 'title' => 'ผลงานซ่อมประปา', 'prompt' => 'photorealistic photo of a plumber fixing pipes under a sink with a wrench, tool bag open, flashlight illuminating the work, realistic detailed closeup'],
            ['slug' => 'electric-work', 'title' => 'ผลงานงานไฟฟ้า', 'prompt' => 'photorealistic photo of an electrician working on a circuit breaker panel in a Thai home with a screwdriver and tester, wires neatly organized, realistic closeup lighting'],
            ['slug' => 'welding-work', 'title' => 'ผลงานงานเชื่อม', 'prompt' => 'photorealistic photo of a welder with a protective mask welding steel at a workshop, bright orange sparks flying, dark workshop background, dramatic realistic industrial shot'],
            ['slug' => 'general-labor', 'title' => 'ผลงานรับจ้างทั่วไป', 'prompt' => 'photorealistic photo of two smiling Thai workers in overalls standing at a job site with shovels and wheelbarrow, construction materials around, golden afternoon light'],
        ],

        // ─── Banners: 5 (wide, no text in image) ─────────────────────────────
        'banner' => [
            ['slug' => 'banner-1', 'title' => 'โปรโมชั่นขนส่งราคาพิเศษ', 'prompt' => 'photorealistic wide photo of a white pickup truck and a motorcycle courier together on a Thai road, festive warm sunlight, clear empty sky on the left side for text overlay, vibrant colors, high detail'],
            ['slug' => 'banner-2', 'title' => 'สมัครสมาชิกวันนี้ รับส่วนลด', 'prompt' => 'photorealistic wide photo of a happy Thai driver holding a smartphone next to his truck, smiling, bright background with open space on one side, daylight, cheerful advertisement photo'],
            ['slug' => 'banner-3', 'title' => 'บริการขนส่งทั่วประเทศ', 'prompt' => 'photorealistic wide aerial view of a highway with trucks driving through Thai countryside, green fields and mountains, blue sky with soft clouds, empty sky space, cinematic wide shot'],
            ['slug' => 'banner-4', 'title' => 'แนะนำเพื่อน รับเครดิตฟรี', 'prompt' => 'photorealistic wide photo of two Thai drivers in uniform standing side by side waving at the camera next to their truck, open space on the right side for text, bright daylight, friendly advertisement'],
            ['slug' => 'banner-5', 'title' => 'อัปเดตแอปเวอร์ชั่นใหม่', 'prompt' => 'photorealistic wide photo of a hand holding a smartphone showing a blurred delivery app screen in the foreground, a truck in soft bokeh behind, modern tech advertisement, bright and clean'],
        ],
    ],
];
