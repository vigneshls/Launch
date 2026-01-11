<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Collector Launch Overlay – Demo</title>
    <style>
        /* ======= Base & Layout ======= */
        :root {
            --red-deep: #7a0610;
            /* deep crimson */
            --red-velvet: #b10e1e;
            /* velvet mid */
            --red-ribbon: #e0162c;
            /* highlight */
            --orange: #ff6b00;
            /* button base */
            --gold: #ffb84d;
            /* warm gold */
            --gold-bright: #ffd36e;
            /* bright gold */
            --shadow: rgba(0, 0, 0, .55);
            --edge-glow: rgba(255, 140, 0, .55);
            --overlay-bg: #ffb84d;
            /* stage background */
            --transition: 1100ms cubic-bezier(.22, .8, .26, 1);
        }

        /* Prevent scroll while overlay is active */
        html.overlay-active,
        body.overlay-active {
            overflow: hidden;
        }

        /* Page filler behind overlay for demo */
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Arial, sans-serif;
        }

        .page-content {
            min-height: 120vh;
            display: grid;
            place-items: center;
            background: radial-gradient(1200px 600px at 50% 20%, #1b1b1f 0%, #0f1115 60%, #0a0b0d 100%);
            color: #eaeef7
        }

        /* ======= Overlay ======= */
        #collector-launch-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: var(--overlay-bg);
            display: grid;
            place-items: center;
            overflow: hidden;
            opacity: 1;
            transition: opacity 600ms ease 300ms;
        }

        #collector-launch-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .curtain-stage {
            position: absolute;
            inset: 0;
        }

        .curtain {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            background:
                radial-gradient(120% 140% at 0% 50%, rgba(0, 0, 0, .35) 0 40%, transparent 41%),
                radial-gradient(120% 140% at 100% 50%, rgba(0, 0, 0, .35) 0 40%, transparent 41%),
                repeating-linear-gradient(90deg,
                    var(--red-velvet) 0 26px,
                    var(--red-ribbon) 26px 46px,
                    var(--red-velvet) 46px 72px);
            box-shadow: inset 0 0 50px 20px rgba(0, 0, 0, .45), 0 10px 40px var(--shadow);
            background-blend-mode: multiply, multiply, normal;
            transform: translateX(0);
            transition: transform var(--transition);
        }

        .curtain.left {
            left: 0;
            border-right: 1px solid rgba(255, 255, 255, .06)
        }

        .curtain.right {
            right: 0;
            border-left: 1px solid rgba(255, 255, 255, .06)
        }

        /* Edge glow + sparkle trail */
        .curtain::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            width: 6px;
            pointer-events: none;
            opacity: .85;
            background: #ffb84d;
            filter: drop-shadow(0 0 6px var(--edge-glow));
            animation: glow-pulse 2.2s ease-in-out infinite;
        }

        .curtain.left::after {
            right: -3px
        }

        .curtain.right::after {
            left: -3px
        }

        @keyframes glow-pulse {

            0%,
            100% {
                opacity: .55
            }

            50% {
                opacity: 1
            }
        }

        /* Shimmer flecks along edges */
        .curtain::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .35;
            background: radial-gradient(3px 3px at 10% 20%, var(--gold-bright) 0 40%, transparent 45%),
                radial-gradient(2px 2px at 30% 65%, var(--gold) 0 45%, transparent 55%),
                radial-gradient(2.5px 2.5px at 70% 35%, var(--gold) 0 45%, transparent 55%),
                radial-gradient(3px 3px at 90% 80%, var(--gold-bright) 0 40%, transparent 45%);
            mix-blend-mode: screen;
            animation: shimmer 3.2s linear infinite;
        }

        @keyframes shimmer {
            to {
                transform: translateX(15px)
            }
        }

        /* Animate center content on load */
        .center-wrap {
            animation: fade-in-up 1.2s ease forwards;
            opacity: 0;
            transform: translateY(20px) scale(1.25);
            max-width: 1200px;
            width: 90vw;
            margin-top: 10%;
        }

        @keyframes fade-in-up {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* panel background to make text readable over curtains/ribbon */
        .center-wrap {
            display: grid;
            place-items: center;
            z-index: 140;
            pointer-events: auto;
            padding: 35px;
            transform: scale(3);
        }

        .center-panel {
            width: min(820px, 92%);
            max-width: 820px;
            padding: clamp(18px, 2.2vw, 28px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(6px) saturate(110%);
            -webkit-backdrop-filter: blur(6px) saturate(110%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            text-align: center;
            transition: transform 220ms ease, box-shadow 220ms ease, opacity 220ms ease;
            opacity: 1;
        }

        /* slight elevated header spacing */
        .center-panel .badge {
            margin-top: 2px;
        }

        /* actions row */
        .center-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
            margin-top: 4px;
        }

        /* smaller skip positioning inside panel for clarity */
        .center-panel .skip {
            font-size: .9rem;
            text-decoration: underline;
            color: #ffd;
            opacity: .9;
            margin-left: 6px;
        }

        /* panel foot (extra micro copy) */
        .panel-foot {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            opacity: .95;
        }

        .panel-foot .gold-line {
            width: 48px;
            height: 4px;
            border-radius: 4px;
            background: linear-gradient(90deg, #ffd36e, #ff8e2b);
            box-shadow: 0 6px 18px rgba(255, 140, 40, 0.08);
        }

        /* meta text */
        .panel-foot .meta {
            font-size: .82rem;
            color: #d9c89b;
            opacity: .92;
        }

        /* reduce warm tint (if you want absolutely neutral, override gradient) */
        /* .panel-foot .gold-line { background: linear-gradient(90deg,#e2e2e2,#bdbdbd); } */

        /* accessible focus state */
        .center-panel:focus-within {
            box-shadow: 0 26px 56px rgba(0, 0, 0, 0.7), 0 0 0 6px rgba(255, 255, 255, 0.02);
            outline: none;
        }

        /* responsive tweaks */
        @media (max-width:560px) {
            .center-panel {
                padding: 14px;
                border-radius: 10px;
            }

            .panel-foot .meta {
                font-size: .78rem;
            }

            .center-panel .badge {
                font-size: .78rem;
                padding: 5px 8px;
            }
        }


        /* Curtain smoother + bounce slide */
        body.launched .curtain.left {
            animation: curtain-left 5s cubic-bezier(.22, 1.08, .38, 1) forwards;
        }

        body.launched .curtain.right {
            animation: curtain-right 5s cubic-bezier(.22, 1.08, .38, 1) forwards;
        }

        @keyframes curtain-left {
            to {
                transform: translateX(-100%);
            }
        }

        @keyframes curtain-right {
            to {
                transform: translateX(100%);
            }
        }


        /* ======= Center Content ======= */
        .center-wrap {
            position: relative;
            display: grid;
            gap: 22px;
            place-items: center;
            text-align: center;
            padding: 24px 20px;
            width: 80%;
 transform: scale(2);
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .15);
            background: linear-gradient(180deg, rgba(255, 255, 255, .08), rgba(0, 0, 0, .25));
            color: #ffe3b0;
            letter-spacing: .08em;
            font-size: .8rem;
            text-transform: uppercase
        }

        .title {
            font-weight: 800;
            line-height: 1.1;
            margin: 0;
            letter-spacing: .3px;
            font-size: clamp(1.6rem, 3.4vw + 0.6rem, 3.2rem);
            background: linear-gradient(92deg, #ff8e2b, #ffd36e 45%, #fff1b2 60%, #ff8e2b 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 18px rgba(255, 187, 80, .15);
        }

        .subtitle {
            color: #f9d79a;
            opacity: .9;
            max-width: 72ch
        }

        .launch-btn {
            cursor: pointer;
            user-select: none;
            border: none;
            outline: none;
            padding: 18px 32px;
            border-radius: 999px;
            font-weight: 800;
            letter-spacing: .3px;
            font-size: 1.05rem;
            background: linear-gradient(180deg, #ff7a14, #ff6b00 35%, #e85d00 100%);
            color: #fff;
            box-shadow: 0 8px 28px rgba(255, 107, 0, .35), inset 0 1px 0 rgba(255, 255, 255, .25);
            position: relative;
            transition: transform 220ms ease, box-shadow 220ms ease, filter 220ms ease;
            border: 1px solid rgba(255, 211, 110, .6);
        }

        .launch-btn:hover {
            transform: translateY(-1px) scale(1.02);
            box-shadow: 0 10px 36px rgba(255, 107, 0, .45);
        }

        .launch-btn:active {
            transform: translateY(0) scale(.99)
        }

        .launch-btn:focus-visible {
            box-shadow: 0 0 0 4px rgba(255, 211, 110, .35), 0 8px 28px rgba(255, 107, 0, .35)
        }

        .launch-btn::after {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            pointer-events: none;
            background: conic-gradient(from 0deg, #fff1b2, #ffd36e, #ff8e2b, transparent 65%);
            filter: blur(10px);
            opacity: .0;
            transition: opacity .35s ease;
        }

        .launch-btn:hover::after {
            opacity: .35
        }

        .skip {
            position: absolute;
            right: 14px;
            bottom: 12px;
            color: #ffe7b8;
            opacity: .6;
            font-size: .85rem;
            text-decoration: underline;
            display: none
        }

        @media (prefers-reduced-motion: reduce) {

            .curtain,
            #collector-launch-overlay {
                transition: none
            }

            .curtain::before,
            .curtain::after {
                animation: none
            }
        }

        /* Confetti styling
        .confetti {
            position: fixed;
            top: -10px;
            width: 10px;
            height: 14px;
            opacity: 0.9;
            animation: fall 3s linear forwards;
        } */

        @keyframes fall {
            to {
                transform: translateY(110vh) rotate(720deg);
            }
        }


        /* ======= Small screens ======= */
        @media (max-width:560px) {
            .subtitle {
                font-size: .95rem
            }
        }
    </style>
</head>

<body class="overlay-active">

    <div id="collector-launch-overlay" role="dialog" aria-labelledby="launch-title" aria-modal="true">
        <div class="curtain-stage" aria-hidden="true">
            <div class="curtain left"></div>
            <div class="curtain right"></div>
        </div>

        <div class="center-wrap">
            <div class="center-panel" role="region" aria-labelledby="launch-title" aria-describedby="launch-desc">
                <span class="badge ml-4">Official&nbsp;&nbsp;&nbsp;&nbsp;Unveiling</span>

                <h1 id="launch-title" class="title uppercase">Launching <br>The Skill Engine  </h1>

                <p id="launch-desc" class="subtitle">
                    Thank you for joining us for this special moment. Click the button below to unveil the platform.
                </p>

                <div class="center-actions">
                    <button id="launch-btn" class="launch-btn" aria-label="Launch Platform">Launch Platform</button>
                    <a href="#" id="skip-overlay" class="skip">Skip</a>
                </div>

                <div class="panel-foot">
                    <div class="gold-line" aria-hidden="true"></div>
                    <small class="meta">Designed and Developed by iSPIN </small>
                </div>
            </div>
        </div>

        <div class="nscet_logo">
            <img src="./logo.png" alt="" width="680px">
        </div>


        <!-- Optional audio (replace src with your hosted file or remove entirely) -->
        <audio id="drumroll" preload="auto">
      <source src="audio.mp3" type="audio/mpeg" />
    </audio>
    </div>

    <!-- ========= End Overlay ========= -->

    <script>
        // Fade out black screen overlay after short delay
        window.addEventListener('DOMContentLoaded', function() {
            // Remove black screen logic (no black overlay)
            // If you want a quick orange overlay, add here
        });

        (function() {
            const body = document.body;
            const overlay = document.getElementById('collector-launch-overlay');
            const launchBtn = document.getElementById('launch-btn');
            const skip = document.getElementById('skip-overlay');
            const leftCurtain = overlay.querySelector('.curtain.left');
            const audio = document.getElementById('drumroll');

            const SHOW_ONCE = false; // set true for production if you want it once per device
            const STORAGE_KEY = 'collector_launch_done';
            if (SHOW_ONCE && localStorage.getItem(STORAGE_KEY) === '1') {
                overlay.classList.add('hidden');
                body.classList.remove('overlay-active');
                return;
            }

            function unveil() {
                try {
                    audio && audio.play && audio.play().catch(() => {});
                } catch (e) {}

                // delay before curtain slides
                setTimeout(() => {
                    body.classList.add('launched');
                    // Hide and remove center-wrap
                    var centerWrap = document.querySelector('.center-wrap');
                    if (centerWrap) {
                        centerWrap.style.opacity = '0';
                        centerWrap.style.pointerEvents = 'none';
                        setTimeout(function() { centerWrap.remove(); }, 80); // reduce timing to 80ms
                    }
                    // Change overlay background to mild orange
                    var overlayBg = document.getElementById('collector-launch-overlay');
                    if (overlayBg) {
                        overlayBg.style.background = 'linear-gradient(135deg, #ffb84d 0%, #ff6b00 100%)';
                        setTimeout(function() {
                            overlayBg.style.background = '';
                        }, 80); // orange background for 80ms only
                    }
                }, 40); // anticipation reduced to 40ms

                const onDone = () => {
                    overlay.classList.add('hidden');
                    setTimeout(() => {
                        overlay.remove();
                        body.classList.remove('overlay-active');
                        if (SHOW_ONCE) localStorage.setItem(STORAGE_KEY, '1');
                        // optional confetti burst
                        launchConfetti();
                        setTimeout(() => {
                           
                        }, 10);
                    }, 70);
                    leftCurtain.removeEventListener('animationend', onDone);
                };
                leftCurtain.addEventListener('animationend', onDone);
            }

            /* Simple confetti burst (optional) */
            function launchConfetti() {
                for (let i = 0; i < 50; i++) {
                    const conf = document.createElement("div");
                    conf.className = "confetti";
                    document.body.appendChild(conf);
                    conf.style.left = Math.random() * 100 + "vw";
                    conf.style.animationDelay = Math.random() * 2 + "s";
                    conf.style.background = `hsl(${Math.random() * 360}, 80%, 60%)`;
                    setTimeout(() => conf.remove(), 100);
                }
            }


            launchBtn.addEventListener('click', unveil);
            skip.addEventListener('click', function(e) {
                e.preventDefault();
                unveil();
                           

            });

            // Accessibility: allow Enter/Space to trigger button (usually default) & Esc to skip
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') unveil();
            });
        })();
    </script>
</body>

</html>