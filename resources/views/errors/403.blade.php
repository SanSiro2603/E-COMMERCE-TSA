<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden Forest 3D</title>
    <style>
        body { margin: 0; overflow: hidden; background-color: #111; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* UI Overlay di atas Canvas 3D */
        #ui-layer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            pointer-events: none; /* Supaya mouse tembus ke 3D */
            z-index: 10;
            width: 100%;
        }

        h1 {
            font-size: 8rem;
            margin: 0;
            font-weight: 800;
            letter-spacing: -5px;
            color: #ffcb39;
            text-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h2 {
            font-size: 2rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1rem;
            color: #ccc;
            margin-bottom: 40px;
        }

        .btn {
            pointer-events: auto; /* Tombol bisa diklik */
            display: inline-block;
            padding: 15px 40px;
            background: #ffcb39;
            color: #111;
            text-decoration: none;
            font-weight: bold;
            border-radius: 50px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px #ffcb39;
        }

        #canvas-container {
            width: 100%;
            height: 100vh;
            display: block;
        }
    </style>
</head>
<body>

    <div id="ui-layer">
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        <p>Anda memasuki wilayah terlarang hutan voxel.<br>Gajah dan Harimau sedang berjaga.</p>
        <a href="/" class="btn">Mundur Perlahan</a>
    </div>

    <div id="canvas-container"></div>

    <script type="module">
        import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';

        // --- KONFIGURASI SCENE ---
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x1a202c); // Warna langit malam
        scene.fog = new THREE.Fog(0x1a202c, 10, 50); // Kabut untuk kedalaman

        // Kamera
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 5, 12);
        camera.lookAt(0, 2, 0);

        // Renderer
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.shadowMap.enabled = true; // Aktifkan bayangan
        renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        // --- PENCAHAYAAN ---
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
        scene.add(ambientLight);

        const dirLight = new THREE.DirectionalLight(0xffcb39, 1); // Cahaya bulan/lampu
        dirLight.position.set(5, 10, 7);
        dirLight.castShadow = true;
        dirLight.shadow.mapSize.width = 2048;
        dirLight.shadow.mapSize.height = 2048;
        scene.add(dirLight);

        // --- OBJEK (VOXEL STYLE) ---
        
        // Material Dasar
        const matGround = new THREE.MeshStandardMaterial({ color: 0x2e4053 });
        const matElephant = new THREE.MeshStandardMaterial({ color: 0x90a4ae });
        const matTiger = new THREE.MeshStandardMaterial({ color: 0xff9800 });
        const matTigerStripe = new THREE.MeshStandardMaterial({ color: 0x212121 });
        const matWhite = new THREE.MeshStandardMaterial({ color: 0xffffff });
        const matTree = new THREE.MeshStandardMaterial({ color: 0x2e7d32 });
        const matWood = new THREE.MeshStandardMaterial({ color: 0x5d4037 });

        // Tanah
        const ground = new THREE.Mesh(new THREE.PlaneGeometry(100, 100), matGround);
        ground.rotation.x = -Math.PI / 2;
        ground.receiveShadow = true;
        scene.add(ground);

        // FUNGSI MEMBUAT KOTAK (Helper)
        function createBox(colorMat, w, h, d, x, y, z, parent) {
            const geo = new THREE.BoxGeometry(w, h, d);
            const mesh = new THREE.Mesh(geo, colorMat);
            mesh.position.set(x, y, z);
            mesh.castShadow = true;
            mesh.receiveShadow = true;
            if(parent) parent.add(mesh);
            return mesh;
        }

        // --- 1. MEMBUAT GAJAH (VOXEL) ---
        const elephantGroup = new THREE.Group();
        
        // Badan
        createBox(matElephant, 2.5, 2, 3.5, 0, 2, 0, elephantGroup);
        // Kepala
        createBox(matElephant, 2, 1.8, 1.5, 0, 3, 2, elephantGroup);
        // Telinga
        createBox(matElephant, 3.5, 1.5, 0.5, 0, 3, 1.8, elephantGroup);
        // Belalai
        const trunkGroup = new THREE.Group();
        trunkGroup.position.set(0, 2.5, 2.8);
        elephantGroup.add(trunkGroup);
        createBox(matElephant, 0.6, 2, 0.6, 0, -0.5, 0, trunkGroup);
        // Gading
        createBox(matWhite, 0.2, 0.8, 0.2, -0.6, 2.2, 2.8, elephantGroup).rotation.x = 0.5;
        createBox(matWhite, 0.2, 0.8, 0.2, 0.6, 2.2, 2.8, elephantGroup).rotation.x = 0.5;
        // Kaki
        createBox(matElephant, 0.8, 1.5, 0.8, -0.8, 0.75, 1.2, elephantGroup);
        createBox(matElephant, 0.8, 1.5, 0.8, 0.8, 0.75, 1.2, elephantGroup);
        createBox(matElephant, 0.8, 1.5, 0.8, -0.8, 0.75, -1.2, elephantGroup);
        createBox(matElephant, 0.8, 1.5, 0.8, 0.8, 0.75, -1.2, elephantGroup);

        elephantGroup.position.set(-3.5, 0, 0);
        elephantGroup.rotation.y = 0.5;
        scene.add(elephantGroup);


        // --- 2. MEMBUAT HARIMAU (VOXEL) ---
        const tigerGroup = new THREE.Group();
        
        // Badan
        createBox(matTiger, 1.5, 1.2, 2.5, 0, 1.2, 0, tigerGroup);
        // Garis-garis (Stripes) simpel
        createBox(matTigerStripe, 1.52, 1.22, 0.2, 0, 1.2, 0.5, tigerGroup);
        createBox(matTigerStripe, 1.52, 1.22, 0.2, 0, 1.2, -0.5, tigerGroup);
        
        // Kepala
        createBox(matTiger, 1.4, 1.2, 1.2, 0, 1.8, 1.5, tigerGroup);
        // Telinga
        createBox(matTiger, 0.4, 0.4, 0.2, -0.5, 2.5, 1.4, tigerGroup);
        createBox(matTiger, 0.4, 0.4, 0.2, 0.5, 2.5, 1.4, tigerGroup);
        // Moncong
        createBox(matWhite, 0.8, 0.5, 0.2, 0, 1.5, 2.1, tigerGroup);
        
        // Kaki
        createBox(matTiger, 0.5, 1, 0.5, -0.5, 0.5, 1, tigerGroup);
        createBox(matTiger, 0.5, 1, 0.5, 0.5, 0.5, 1, tigerGroup);
        createBox(matTiger, 0.5, 1, 0.5, -0.5, 0.5, -1, tigerGroup);
        createBox(matTiger, 0.5, 1, 0.5, 0.5, 0.5, -1, tigerGroup);
        // Ekor
        const tail = createBox(matTiger, 0.3, 0.3, 1.5, 0, 1.5, -1.5, tigerGroup);
        tail.rotation.x = 0.5;

        tigerGroup.position.set(3.5, 0, 1);
        tigerGroup.rotation.y = -0.5;
        scene.add(tigerGroup);


        // --- 3. MEMBUAT POHON (ENVIRONMENT) ---
        function createTree(x, z) {
            const group = new THREE.Group();
            createBox(matWood, 0.8, 2, 0.8, 0, 1, 0, group); // Batang
            // Daun (Piramida kotak)
            createBox(matTree, 3, 1, 3, 0, 2.5, 0, group);
            createBox(matTree, 2, 1, 2, 0, 3.5, 0, group);
            createBox(matTree, 1, 1, 1, 0, 4.5, 0, group);
            
            group.position.set(x, 0, z);
            scene.add(group);
        }

        // Sebar pohon secara acak
        const treePositions = [
            [-6, -4], [6, -5], [-2, -8], [4, -8], [-8, 2], [8, 4]
        ];
        treePositions.forEach(pos => createTree(pos[0], pos[1]));

        // --- PARTIKEL (KUNANG-KUNANG 3D) ---
        const firefliesGeo = new THREE.BufferGeometry();
        const firefliesCount = 30;
        const posArray = new Float32Array(firefliesCount * 3);
        
        for(let i = 0; i < firefliesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 20; // Sebar area
            if (i % 3 === 1) posArray[i] = Math.random() * 5 + 1; // Tinggi Y (di atas tanah)
        }
        
        firefliesGeo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const firefliesMat = new THREE.PointsMaterial({
            size: 0.15,
            color: 0xc6ff00,
            transparent: true,
            opacity: 0.8
        });
        const firefliesMesh = new THREE.Points(firefliesGeo, firefliesMat);
        scene.add(firefliesMesh);


        // --- ANIMASI & INTERAKSI ---
        let mouseX = 0;
        let mouseY = 0;
        let targetX = 0;
        let targetY = 0;

        // Event listener mouse
        document.addEventListener('mousemove', (event) => {
            mouseX = (event.clientX - window.innerWidth / 2) * 0.001;
            mouseY = (event.clientY - window.innerHeight / 2) * 0.001;
        });

        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const time = clock.getElapsedTime();

            // Animasi Bernapas/Idle (Naik Turun)
            elephantGroup.position.y = Math.sin(time * 2) * 0.05;
            elephantGroup.rotation.z = Math.sin(time * 1.5) * 0.02; // Goyang dikit
            trunkGroup.rotation.x = Math.sin(time * 3) * 0.1; // Belalai goyang

            tigerGroup.position.y = Math.abs(Math.sin(time * 4)) * 0.1; // Cepat seperti siap menerkam

            // Animasi Kunang-kunang (Naik turun random look)
            firefliesMesh.rotation.y = time * 0.1;
            
            // Kamera Follow Mouse (Smooth)
            targetX = mouseX * 5;
            targetY = mouseY * 2;
            
            camera.position.x += (targetX - camera.position.x) * 0.05;
            camera.position.y += (5 + targetY - camera.position.y) * 0.05;
            camera.lookAt(0, 1, 0);

            renderer.render(scene, camera);
        }

        // Responsif Window Resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        animate();
    </script>
</body>
</html>