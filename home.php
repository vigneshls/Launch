<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IQ Arena | NSCET</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/css/resource/style.css">
    <link rel="stylesheet" href="./assets/css/home/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        #confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 50;
        }
        .main-footer{
            position: absolute;
            width: 100%;
            bottom: 0;
        }
        .top-img{
             position: absolute;
             left: 43%;
             top: 18%;
        }
       
        .main-sub-text{
            margin-top: -9%;
            z-index: 99;
        }
        .words{
           margin-left: -5%;
        }
        
    </style>

      <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

</head>
<body class="min-h-screen flex flex-col font-sans relative">
    <canvas id="confetti"></canvas>
     
    <div class="w-full text-center py-4 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-700 text-white font-bold text-lg md:text-2xl shadow-md z-20 relative">
        THENI MELAPETTAI HINDU NADARGAL URAVINMURAI <br>
        NADAR SARASWATHI COLLEGE OF ENGINEERING AND TECHNOLOGY
        <div style="position: absolute;left:30px;top:0;width:140px;height:80px">
            <img src="./assets/img/main/logo.png" alt="">
        </div>
    </div>

    <!-- Animated background particles -->
    <div class="bg-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Floating shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="top-img">
        <img src="./iq.png" alt="" width="190px" >
    </div>

    <header class="words w-full text-center main-text   py-16 relative z-10 mt-40 ">
        <div class="overlay"></div>
        <div class="text">
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="100"><div class="letter">I</div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="200"><div class="letter">Q</div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="300"><div class="letter"></div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="400"><div class="letter">A</div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="500"><div class="letter">R</div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="600"><div class="letter">E</div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="700"><div class="letter">N</div></div>
            <div class="wrapper"  data-aos="fade-down-right"  data-aos-delay="800"><div class="letter">A</div></div>
        </div>
        <div class="section-divider max-w-md mx-auto"></div>
    </header>
    
    <p class="text-center font-bold text-orange-500 main-sub-text text-5xl -py-100 text-2xl">
        "The Skill Engine for the Next-Gen Engineers"
    </p>



    <!-- <main class="flex flex-col items-center justify-center flex-grow w-full max-w-6xl px-4 mx-auto relative z-10">
        <div class="flex flex-col sm:flex-row gap-6 justify-center" >
            <a href="./login.php" class="btn-primary text-white font-semibold py-4 px-8 rounded-xl text-xl transform transition-all duration-300">
                Portal
            </a>
        </div>
    </main> -->

     <audio id="finalaudio" preload="auto">
      <source src="final.mp3" type="audio/mpeg" />
    </audio>


    <div class="mt-10 main-footer">
        <?php include('./resource/footer.php') ?>
    </div>

    <script>
        // Smooth scrolling
        document.addEventListener('DOMContentLoaded', function() {
            let audio_play=document.querySelector('#finalaudio')
            setTimeout(function() {
      audio_play.play()
    }, 0);
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                      
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                      
                        target.scrollIntoView({behavior: 'smooth', block: 'start'});

                    }
                });
            });

            // Animate cards on view
            const cards = document.querySelectorAll('.card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';

                        }, index * 100);
                    }
                });
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });

            // Parallax effect
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const particles = document.querySelectorAll('.particle');
                particles.forEach((particle, index) => {
                    const speed = 0.5 + (index * 0.1);
                    particle.style.transform = `translateY(${scrolled * speed}px)`;
                });
            });
        });
    </script>

    <!-- Confetti Script (no ribbons) -->
    <script>
          
        var COLORS = [
            ["#df0049", "#660671"],
            ["#00e857", "#005291"],
            ["#2bebbc", "#05798a"],
            ["#ffd200", "#b06c00"],
            ["#ff6f61", "#d32f2f"],
            ["#4caf50", "#2e7d32"]
        ];

        var confetti = {};
        (function() {
            confetti.Context = function(parent) {
                var self = this;
                this.canvas = document.getElementById("confetti");
                this.w = this.canvas.width = window.innerWidth;
                this.h = this.canvas.height = window.innerHeight;
                this.ctx = this.canvas.getContext("2d");
                this.papers = [];

                var confettiPaperCount = 250;

                for (var i = 0; i < confettiPaperCount; i++) {
                    this.papers.push(new confetti.Paper(Math.random() * this.w, Math.random() * this.h));
                }

                window.addEventListener('resize', function() {
                    self.w = self.canvas.width = window.innerWidth;
                    self.h = self.canvas.height = window.innerHeight;
                });
            };

            confetti.Paper = function(x, y) {
                this.x = x;
                this.y = y;
                this.w = 8.0 + Math.random() * 8.0;
                this.h = this.w * 0.6;
                this.angle = Math.random() * 360;
                this.rotationSpeed = (Math.random() * 600 + 200);
                this.speed = (Math.random() * 90 + 30.0);
                this.color = COLORS[Math.floor(Math.random() * COLORS.length)];
                this.frontColor = this.color[0];
                this.backColor = this.color[1];
                this.time = Math.random();
                this.oscillationSpeed = (Math.random() * 2.0 + 1.5);
                this.oscillationDistance = (Math.random() * 40.0 + 40.0);
            };

            confetti.Paper.prototype.Update = function(dt) {
                this.time += dt;
                this.angle += this.rotationSpeed * dt;
                this.y += this.speed * dt;
                this.x += Math.cos(this.time * this.oscillationSpeed) * this.oscillationDistance * dt;
                if (this.y > window.innerHeight) {
                    this.x = Math.random() * window.innerWidth;
                    this.y = -10;
                }
            };

            confetti.Paper.prototype.Draw = function(ctx) {
                ctx.save();
                ctx.translate(this.x, this.y);
                ctx.rotate(this.angle * Math.PI / 180);
                ctx.fillStyle = (Math.cos(this.angle * Math.PI / 180) > 0) ? this.frontColor : this.backColor;
                ctx.fillRect(-this.w / 2, -this.h / 2, this.w, this.h);
                ctx.restore();
            };

            confetti.Context.prototype.Update = function() {
                var self = this;
                var dt = 1.0 / 60.0;

                this.ctx.clearRect(0, 0, this.w, this.h);

                for (var i = 0; i < this.papers.length; i++) {
                    this.papers[i].Update(dt);
                    this.papers[i].Draw(this.ctx);
                }

                requestAnimationFrame(function() { self.Update(); });
            };

        })();

        window.onload = function() {
            var confettiCtx = new confetti.Context(document.body);
            confettiCtx.Update();
        };
    </script>

      <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
