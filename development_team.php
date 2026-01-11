<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSCET ISPIN Development Team</title>
    <style>
        :root {
            --gradient-primary: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            --gradient-secondary: linear-gradient(135deg, #FF8C00 0%, #E67700 100%);
            --gradient-accent: linear-gradient(135deg, #FFA500 0%, #FF7F00 100%);
            --color-primary: #F97316;
            --color-primary-hover: #EA580C;
            --color-secondary: #FF8C00;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        /* Animated background particles */
        .bg-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--color-primary);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Header Section */
        .header-section {
            text-align: center;
            padding: 2rem 2rem 1.5rem;
            background: var(--gradient-primary);
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content:center;
            align-items: center; 
        }

        .main-img{
            position: absolute;
            left: 20px;
            top: 30px;
        }
        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            animation: shine 3s ease-in-out infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

       .footer-text {
    font-size: 1.4rem;            /* Bigger size */
    font-weight: 500;             /* Medium bold */
    margin-bottom: 1rem;          /* More spacing below */
    opacity: 1;                   /* Full visibility */
    letter-spacing: 1px;          /* Wider spacing */
    text-transform: uppercase;    /* Stylish uppercase */
    color: #f1f1f1;               /* Light grey-white */
    animation: fadeInUp 1.2s ease-out;
}


        .nscet-brand {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(45deg, #fff, #f0f0f0, #fff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 50px rgba(255,255,255,0.3);
            animation: gradientShift 2s ease-in-out infinite, fadeInUp 1s ease-out 0.3s both;
            letter-spacing: 1px;
            position: relative;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Grid Container */
        .gallery-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
            position: relative;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            animation: fadeIn 1s ease-out 0.6s both;
        }

        /* Image Cards */
        .image-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(145deg, rgba(249,115,22,0.1), rgba(234,88,12,0.1));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(249,115,22,0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInUp 0.6s ease-out both;
        }

        .image-card:nth-child(1) { animation-delay: 0.1s; }
        .image-card:nth-child(2) { animation-delay: 0.2s; }
        .image-card:nth-child(3) { animation-delay: 0.3s; }
        .image-card:nth-child(4) { animation-delay: 0.4s; }
        .image-card:nth-child(5) { animation-delay: 0.5s; }
        .image-card:nth-child(6) { animation-delay: 0.6s; }
        .image-card:nth-child(7) { animation-delay: 0.7s; }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .image-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(249,115,22,0.3);
            border-color: var(--color-primary);
        }

        .image-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            opacity: 0;
            transition: all 0.4s ease;
            z-index: 1;
        }

        .image-card:hover::before {
            left: 0;
            opacity: 0.1;
        }

        .image-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.4s ease;
            position: relative;
            z-index: 2;
        }

        .image-card:hover img {
            transform: scale(1.05);
        }

        /* Card overlay for tech effect */
        .card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem;
            transform: translateY(0);
            transition: all 0.3s ease;
            z-index: 3;
            opacity: 0.95;
        }

        .image-card:hover .card-overlay {
            background: var(--gradient-secondary);
            transform: translateY(0);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-role {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nscet-brand {
                font-size: 1.5rem;
            }

            .footer-text {
                font-size: 0.8rem;
            }

            .header-section {
                padding: 1.5rem 1rem 1rem;
            }

            .gallery-container {
                padding: 2rem 1rem;
            }

            .image-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .image-card img {
                height: 220px;
            }
        }

        @media (max-width: 480px) {
            .nscet-brand {
                font-size: 1.3rem;
            }

            .image-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Tech grid lines effect */
        .tech-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(249,115,22,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(249,115,22,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: -1;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Glitch effect for brand text */
        .nscet-brand:hover {
            animation: glitch 0.3s ease-in-out;
        }

        @keyframes glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }
    </style>
</head>
<body>

<?php include('./main.php') ?>
    <div class="tech-grid"></div>
    <div class="bg-particles"></div>

    <!-- Header Section -->
    <div class="header-section ">
       <div class="main-img">
         <img src="./ispin.png" alt="" width="100px">
       </div>
      <div>
          <p class="footer-text">Ideated, Designed and Developed by</p>
        <div class="nscet-brand">NSCET iSPIN Members</div>
      </div>
    </div>

    <!-- Gallery Container -->
    <div class="gallery-container">
        <div class="image-grid">
            <div class="image-card">
                <img src="./assets/img/main/development_team/sri.png" alt="Team Member">
                <div class="card-overlay">
                    <div class="card-title">A SriHari Prasath</div>
                    <div class="card-role">Information Technology</div>
                </div>
            </div>
           <div class="image-card">
                <img src="./assets/img/main/development_team/santhosh.png" alt="Team Member">
                <div class="card-overlay">
                    <div class="card-title">G Sandhosh</div>
                    <div class="card-role">Artificial Intelligence and Data Science</div>
                </div>
            </div>
                     <div class="image-card">
                <img src="./assets/img/main/development_team/keerthana.png" alt="Team Member">
                <div class="card-overlay">
                    <div class="card-title">T Keerthana</div>
                    <div class="card-role">Artificial Intelligence and Data Science</div>
                </div>
            </div>
            <div class="image-card">
                <img src="./assets/img/main/development_team/josika.png" alt="Team Member">
                <div class="card-overlay">
                    <div class="card-title">P Josika</div>
                    <div class="card-role">Computer Science</div>
                </div>
            </div>
           
        </div>
    </div>

    <div style="display: flex; justify-content: center; align-items: center; width: 100%; gap: 2rem;">
          <div class="image-card">
                <img src="./assets/img/main/development_team/sachin.png" alt="Team Member">
                <div class="card-overlay">
                    <div class="card-title">Sachithanandan S</div>
                    <div class="card-role">Computer Science</div>
                </div>
            </div>
            <div class="image-card">
                <img src="./assets/img/main/development_team/mark.png" alt="Keerthana">
                <div class="card-overlay">
                    <div class="card-title">B Naveen Bharathi </div>
                    <div class="card-role">Information Technology</div>
                </div>
            </div> 
            
            
            <div class="image-card">
                <img src="./assets/img/main/development_team/aas.png" alt="Team Member">
                <div class="card-overlay">
                    <div class="card-title">J S Aaswin</div>
                    <div class="card-role">Information Technology</div>
                </div>
            </div>
    </div>

   

     <audio id="main-audio" preload="auto">
      <source src="main.mp3" type="audio/mpeg" />
    </audio>
    <script>
        // Create floating particles
        function createParticles() {
            const container = document.querySelector('.bg-particles');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
                container.appendChild(particle);
            }
        }

        // Initialize particles on load
        window.addEventListener('load', createParticles);
 

        // Add intersection observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        // Observe all image cards
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.image-card');
            cards.forEach(card => observer.observe(card));
        });

        // Add mouse parallax effect to header
        document.addEventListener('mousemove', (e) => {
            const header = document.querySelector('.header-section');
            const x = (e.clientX / window.innerWidth) * 100;
            const y = (e.clientY / window.innerHeight) * 100;
            
            header.style.backgroundPosition = `${x}% ${y}%`;
        });
    </script>
</body>
<script>
    let main_audio=document.querySelector("#main-audio")
let click_btn=document.querySelector("#launch-btn")
click_btn.addEventListener('click',()=>{
      setTimeout(function() {
       main_audio.play()
    }, 4000);
        setTimeout(function() {
        window.location.href = 'launch-overlay.php';
    }, 12000);
})
</script>
</html>