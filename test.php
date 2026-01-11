<!DOCTYPE html>
<html lang="en">
<head>
<style>
html, body {
    margin: 0;
    padding: 0;
    background: #000;
    height: 100%;
    width: 100%;
}
#confetti{
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 100%;
    pointer-events: none;
    z-index: 9999;
}
a.iprodev {
    line-height: normal;
    font-family: Varela Round, sans-serif;
    font-weight: 600;
    text-decoration: none;
    font-size: 13px;
    color: #A7AAAE;
    position: absolute;
    left: 20px;
    bottom: 20px;
    border: 1px solid #A7AAAE;
    padding: 12px 20px 10px;
    border-radius: 50px;
    transition: all .1s ease-in-out;
    text-transform: uppercase;
}
a.iprodev:hover {
    background: #A7AAAE;
    color: white;
}
</style>
<canvas id="confetti" width="1" height="1"></canvas>
<a href="http://iprodev.com" target="_blank" class="iprodev">iprodev</a>
<script>
// --- Confetti Ribbon JS ---
var retina = window.devicePixelRatio,
        PI = Math.PI,
        sqrt = Math.sqrt,
        round = Math.round,
        random = Math.random,
        cos = Math.cos,
        sin = Math.sin,
        rAF = window.requestAnimationFrame,
        cAF = window.cancelAnimationFrame || window.cancelRequestAnimationFrame,
        _now = Date.now || function () {return new Date().getTime();};
(function (w) {
    var prev = _now();
    function fallback(fn) {
        var curr = _now();
        var ms = Math.max(0, 16 - (curr - prev));
        var req = setTimeout(fn, ms);
        prev = curr;
        return req;
    }
    var cancel = w.cancelAnimationFrame
    || w.webkitCancelAnimationFrame
    || w.clearTimeout;
    rAF = w.requestAnimationFrame
    || w.webkitRequestAnimationFrame
    || fallback;
    cAF = function(id){ cancel.call(w, id); };
}(window));
document.addEventListener("DOMContentLoaded", function() {
    var speed = 50,
            duration = (1.0 / speed),
            confettiRibbonCount = 11,
            ribbonPaperCount = 30,
            ribbonPaperDist = 8.0,
            ribbonPaperThick = 8.0,
            confettiPaperCount = 95,
            DEG_TO_RAD = PI / 180,
            RAD_TO_DEG = 180 / PI,
            colors = [
                ["#df0049", "#660671"],
                ["#00e857", "#005291"],
                ["#2bebbc", "#05798a"],
                ["#ffd200", "#b06c00"]
            ];
    function Vector2(_x, _y) {
        this.x = _x, this.y = _y;
        this.Length = function() { return sqrt(this.SqrLength()); }
        this.SqrLength = function() { return this.x * this.x + this.y * this.y; }
        this.Add = function(_vec) { this.x += _vec.x; this.y += _vec.y; }
        this.Sub = function(_vec) { this.x -= _vec.x; this.y -= _vec.y; }
        this.Div = function(_f) { this.x /= _f; this.y /= _f; }
        this.Mul = function(_f) { this.x *= _f; this.y *= _f; }
        this.Normalize = function() {
            var sqrLen = this.SqrLength();
            if (sqrLen != 0) {
                var factor = 1.0 / sqrt(sqrLen);
                this.x *= factor;
                this.y *= factor;
            }
        }
        this.Normalized = function() {
            var sqrLen = this.SqrLength();
            if (sqrLen != 0) {
                var factor = 1.0 / sqrt(sqrLen);
                return new Vector2(this.x * factor, this.y * factor);
            }
            return new Vector2(0, 0);
        }
    }
    Vector2.Lerp = function(_vec0, _vec1, _t) {
        return new Vector2((_vec1.x - _vec0.x) * _t + _vec0.x, (_vec1.y - _vec0.y) * _t + _vec0.y);
    }
    Vector2.Distance = function(_vec0, _vec1) {
        return sqrt(Vector2.SqrDistance(_vec0, _vec1));
    }
    Vector2.SqrDistance = function(_vec0, _vec1) {
        var x = _vec0.x - _vec1.x;
        var y = _vec0.y - _vec1.y;
        return (x * x + y * y);
    }
    Vector2.Scale = function(_vec0, _vec1) {
        return new Vector2(_vec0.x * _vec1.x, _vec0.y * _vec1.y);
    }
    Vector2.Min = function(_vec0, _vec1) {
        return new Vector2(Math.min(_vec0.x, _vec1.x), Math.min(_vec0.y, _vec1.y));
    }
    Vector2.Max = function(_vec0, _vec1) {
        return new Vector2(Math.max(_vec0.x, _vec1.x), Math.max(_vec0.y, _vec1.y));
    }
    Vector2.ClampMagnitude = function(_vec0, _len) {
        var vecNorm = _vec0.Normalized;
        return new Vector2(vecNorm.x * _len, vecNorm.y * _len);
    }
    Vector2.Sub = function(_vec0, _vec1) {
        return new Vector2(_vec0.x - _vec1.x, _vec0.y - _vec1.y);
    }
    function EulerMass(_x, _y, _mass, _drag) {
        this.position = new Vector2(_x, _y);
        this.mass = _mass;
        this.drag = _drag;
        this.force = new Vector2(0, 0);
        this.velocity = new Vector2(0, 0);
        this.AddForce = function(_f) { this.force.Add(_f); }
        this.Integrate = function(_dt) {
            var acc = this.CurrentForce(this.position);
            acc.Div(this.mass);
            var posDelta = new Vector2(this.velocity.x, this.velocity.y);
            posDelta.Mul(_dt);
            this.position.Add(posDelta);
            acc.Mul(_dt);
            this.velocity.Add(acc);
            this.force = new Vector2(0, 0);
        }
        this.CurrentForce = function(_pos, _vel) {
            var totalForce = new Vector2(this.force.x, this.force.y);
            var speed = this.velocity.Length();
            var dragVel = new Vector2(this.velocity.x, this.velocity.y);
            dragVel.Mul(this.drag * this.mass * speed);
            totalForce.Sub(dragVel);
            return totalForce;
        }
    }
    function ConfettiPaper(_x, _y) {
        this.pos = new Vector2(_x, _y);
        this.rotationSpeed = (random() * 600 + 800);
        this.angle = DEG_TO_RAD * random() * 360;
        this.rotation = DEG_TO_RAD * random() * 360;
        this.cosA = 1.0;
        this.size = 5.0;
        this.oscillationSpeed = (random() * 1.5 + 0.5);
        this.xSpeed = 40.0;
        this.ySpeed = (random() * 60 + 50.0);
        this.corners = new Array();
        this.time = random();
        var ci = round(random() * (colors.length - 1));
        this.frontColor = colors[ci][0];
        this.backColor = colors[ci][1];
        for (var i = 0; i < 4; i++) {
            var dx = cos(this.angle + DEG_TO_RAD * (i * 90 + 45));
            var dy = sin(this.angle + DEG_TO_RAD * (i * 90 + 45));
            this.corners[i] = new Vector2(dx, dy);
        }
        this.Update = function(_dt) {
            this.time += _dt;
            this.rotation += this.rotationSpeed * _dt;
            this.cosA = cos(DEG_TO_RAD * this.rotation);
            this.pos.x += cos(this.time * this.oscillationSpeed) * this.xSpeed * _dt
            this.pos.y += this.ySpeed * _dt;
            if (this.pos.y > ConfettiPaper.bounds.y) {
                this.pos.x = random() * ConfettiPaper.bounds.x;
                this.pos.y = 0;
            }
        }
        this.Draw = function(_g) {
            if (this.cosA > 0) {
                _g.fillStyle = this.frontColor;
            } else {
                _g.fillStyle = this.backColor;
            }
            _g.beginPath();
            _g.moveTo((this.pos.x + this.corners[0].x * this.size) * retina, (this.pos.y + this.corners[0].y * this.size * this.cosA) * retina);
            for (var i = 1; i < 4; i++) {
                _g.lineTo((this.pos.x + this.corners[i].x * this.size) * retina, (this.pos.y + this.corners[i].y * this.size * this.cosA) * retina);
            }
            _g.closePath();
            _g.fill();
        }
    }
    ConfettiPaper.bounds = new Vector2(0, 0);
    function ConfettiRibbon(_x, _y, _count, _dist, _thickness, _angle, _mass, _drag) {
        this.particleDist = _dist;
        this.particleCount = _count;
        this.particleMass = _mass;
        this.particleDrag = _drag;
        this.particles = new Array();
        var ci = round(random() * (colors.length - 1));
        this.frontColor = colors[ci][0];
        this.backColor = colors[ci][1];
        this.xOff = (cos(DEG_TO_RAD * _angle) * _thickness);
        this.yOff = (sin(DEG_TO_RAD * _angle) * _thickness);
        this.position = new Vector2(_x, _y);
        this.prevPosition = new Vector2(_x, _y);
        this.velocityInherit = (random() * 2 + 4);
        this.time = random() * 100;
        this.oscillationSpeed = (random() * 2 + 2);
        this.oscillationDistance = (random() * 40 + 40);
        this.ySpeed = (random() * 40 + 80);
        for (var i = 0; i < this.particleCount; i++) {
            this.particles[i] = new EulerMass(_x, _y - i * this.particleDist, this.particleMass, this.particleDrag);
        }
        this.Update = function(_dt) {
            var i = 0;
            this.time += _dt * this.oscillationSpeed;
            this.position.y += this.ySpeed * _dt;
            this.position.x += cos(this.time) * this.oscillationDistance * _dt;
            this.particles[0].position = this.position;
            var dX = this.prevPosition.x - this.position.x;
            var dY = this.prevPosition.y - this.position.y;
            var delta = sqrt(dX * dX + dY * dY);
            this.prevPosition = new Vector2(this.position.x, this.position.y);
            for (i = 1; i < this.particleCount; i++) {
                var dirP = Vector2.Sub(this.particles[i - 1].position, this.particles[i].position);
                dirP.Normalize();
                dirP.Mul((delta / _dt) * this.velocityInherit);
                this.particles[i].AddForce(dirP);
            }
            for (i = 1; i < this.particleCount; i++) {
                this.particles[i].Integrate(_dt);
            }
            for (i = 1; i < this.particleCount; i++) {
                var rp2 = new Vector2(this.particles[i].position.x, this.particles[i].position.y);
                rp2.Sub(this.particles[i - 1].position);
                rp2.Normalize();
                rp2.Mul(this.particleDist);
                rp2.Add(this.particles[i - 1].position);
                this.particles[i].position = rp2;
            }
            if (this.position.y > ConfettiRibbon.bounds.y + this.particleDist * this.particleCount) {
                this.Reset();
            }
        }
        this.Reset = function() {
            this.position.y = -random() * ConfettiRibbon.bounds.y;
            this.position.x = random() * ConfettiRibbon.bounds.x;
            this.prevPosition = new Vector2(this.position.x, this.position.y);
            this.velocityInherit = random() * 2 + 4;
            this.time = random() * 100;
            this.oscillationSpeed = random() * 2.0 + 1.5;
            this.oscillationDistance = (random() * 40 + 40);
            this.ySpeed = random() * 40 + 80;
            var ci = round(random() * (colors.length - 1));
            this.frontColor = colors[ci][0];
            this.backColor = colors[ci][1];
            this.particles = new Array();
            for (var i = 0; i < this.particleCount; i++) {
                this.particles[i] = new EulerMass(this.position.x, this.position.y - i * this.particleDist, this.particleMass, this.particleDrag);
            }
        }
        this.Draw = function(_g) {
            for (var i = 0; i < this.particleCount - 1; i++) {
                var p0 = new Vector2(this.particles[i].position.x + this.xOff, this.particles[i].position.y + this.yOff);
                var p1 = new Vector2(this.particles[i + 1].position.x + this.xOff, this.particles[i + 1].position.y + this.yOff);
                if (this.Side(this.particles[i].position.x, this.particles[i].position.y, this.particles[i + 1].position.x, this.particles[i + 1].position.y, p1.x, p1.y) < 0) {
                    _g.fillStyle = this.frontColor;
                    _g.strokeStyle = this.frontColor;
                } else {
                    _g.fillStyle = this.backColor;
                    _g.strokeStyle = this.backColor;
                }
                if (i == 0) {
                    _g.beginPath();
                    _g.moveTo(this.particles[i].position.x * retina, this.particles[i].position.y * retina);
                    _g.lineTo(this.particles[i + 1].position.x * retina, this.particles[i + 1].position.y * retina);
                    _g.lineTo(((this.particles[i + 1].position.x + p1.x) * 0.5) * retina, ((this.particles[i + 1].position.y + p1.y) * 0.5) * retina);
                    _g.closePath();
                    _g.stroke();
                    _g.fill();
                    _g.beginPath();
                    _g.moveTo(p1.x * retina, p1.y * retina);
                    _g.lineTo(p0.x * retina, p0.y * retina);
                    _g.lineTo(((this.particles[i + 1].position.x + p1.x) * 0.5) * retina, ((this.particles[i + 1].position.y + p1.y) * 0.5) * retina);
                    _g.closePath();
                    _g.stroke();
                    _g.fill();
                } else if (i == this.particleCount - 2) {
                    _g.beginPath();
                    _g.moveTo(this.particles[i].position.x * retina, this.particles[i].position.y * retina);
                    _g.lineTo(this.particles[i + 1].position.x * retina, this.particles[i + 1].position.y * retina);
                    _g.lineTo(((this.particles[i].position.x + p0.x) * 0.5) * retina, ((this.particles[i].position.y + p0.y) * 0.5) * retina);
                    _g.closePath();
                    _g.stroke();
                    _g.fill();
                    _g.beginPath();
                    _g.moveTo(p1.x * retina, p1.y * retina);
                    _g.lineTo(p0.x * retina, p0.y * retina);
                    _g.lineTo(((this.particles[i].position.x + p0.x) * 0.5) * retina, ((this.particles[i].position.y + p0.y) * 0.5) * retina);
                    _g.closePath();
                    _g.stroke();
                    _g.fill();
                } else {
                    _g.beginPath();
                    _g.moveTo(this.particles[i].position.x * retina, this.particles[i].position.y * retina);
                    _g.lineTo(this.particles[i + 1].position.x * retina, this.particles[i + 1].position.y * retina);
                    _g.lineTo(p1.x * retina, p1.y * retina);
                    _g.lineTo(p0.x * retina, p0.y * retina);
                    _g.closePath();
                    _g.stroke();
                    _g.fill();
                }
            }
        }
        this.Side = function(x1, y1, x2, y2, x3, y3) {
            return ((x1 - x2) * (y3 - y2) - (y1 - y2) * (x3 - x2));
        }
    }
    ConfettiRibbon.bounds = new Vector2(0, 0);
    confetti = {};
    confetti.Context = function(id) {
        var i = 0;
        var canvas = document.getElementById(id);
        var canvasParent = canvas.parentNode;
        var canvasWidth = canvasParent.offsetWidth;
        var canvasHeight = canvasParent.offsetHeight;
        canvas.width = canvasWidth * retina;
        canvas.height = canvasHeight * retina;
        var context = canvas.getContext('2d');
        var interval = null;
        var confettiRibbons = new Array();
        ConfettiRibbon.bounds = new Vector2(canvasWidth, canvasHeight);
        for (i = 0; i < confettiRibbonCount; i++) {
            confettiRibbons[i] = new ConfettiRibbon(random() * canvasWidth, -random() * canvasHeight * 2, ribbonPaperCount, ribbonPaperDist, ribbonPaperThick, 45, 1, 0.05);
        }
        var confettiPapers = new Array();
        ConfettiPaper.bounds = new Vector2(canvasWidth, canvasHeight);
        for (i = 0; i < confettiPaperCount; i++) {
            confettiPapers[i] = new ConfettiPaper(random() * canvasWidth, random() * canvasHeight);
        }
        this.resize = function() {
            canvasWidth = canvasParent.offsetWidth;
            canvasHeight = canvasParent.offsetHeight;
            canvas.width = canvasWidth * retina;
            canvas.height = canvasHeight * retina;
            ConfettiPaper.bounds = new Vector2(canvasWidth, canvasHeight);
            ConfettiRibbon.bounds = new Vector2(canvasWidth, canvasHeight);
        }
        this.start = function() {
            this.stop()
            var context = this;
            this.update();
        }
        this.stop = function() {
            cAF(this.interval);
        }
        this.update = function() {
            var i = 0;
            context.clearRect(0, 0, canvas.width, canvas.height);
            for (i = 0; i < confettiPaperCount; i++) {
                confettiPapers[i].Update(duration);
                confettiPapers[i].Draw(context);
            }
            for (i = 0; i < confettiRibbonCount; i++) {
                confettiRibbons[i].Update(duration);
                confettiRibbons[i].Draw(context);
            }
            this.interval = rAF(function() {
                confetti.update();
            });
        }
    }
    var confetti = new confetti.Context('confetti');
    confetti.start();
    window.addEventListener('resize', function(event){
        confetti.resize();
    });
});
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSCET Quiz App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --color-primary: #F97316; /* Main orange color */
            --color-primary-hover: #EA580C;
            --color-secondary: #FF8C00; /* Darker orange shade */
            --color-accent: #FFA500; /* Lighter orange accent */
            --color-background: #1A120B; /* Darker background to complement orange */
            --color-surface: #2C1A0E; /* Surface color with orange undertone */
            --text: #F1F5F9;
            --text-secondary: #D4A373; /* Orange-tinted secondary text */
            --card-border: #4A2F1A;
            --shadow: rgba(0, 0, 0, 0.3);
            --gradient-primary: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
            --gradient-secondary: linear-gradient(135deg, #FF8C00 0%, #E67700 100%);
            --gradient-accent: linear-gradient(135deg, #FFA500 0%, #FF7F00 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--color-background);
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(249, 115, 22, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 140, 0, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 165, 0, 0.1) 0%, transparent 50%);
            color: var(--text);
            overflow-x: hidden;
            position: relative;
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
            background: var(--gradient-primary);
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; top: 20%; left: 80%; animation-delay: -5s; }
        .particle:nth-child(3) { width: 100px; height: 100px; top: 60%; left: 20%; animation-delay: -10s; }
        .particle:nth-child(4) { width: 40px; height: 40px; top: 80%; left: 90%; animation-delay: -15s; }
        .particle:nth-child(5) { width: 70px; height: 70px; top: 40%; left: 60%; animation-delay: -7s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(15px) rotate(240deg); }
        }

        /* Enhanced animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 40px rgba(249, 115, 22, 0.6), 0 0 60px rgba(249, 115, 22, 0.4); }
        }

        /* Component styles */
        .card {
            background: var(--color-surface);
            background-image: linear-gradient(135deg, var(--color-surface) 0%, rgba(249, 115, 22, 0.05) 100%);
            border: 1px solid var(--card-border);
            box-shadow: 
                0 10px 25px var(--shadow),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .welcome-card {
            animation: scaleIn 1s ease-out 0.3s both;
            margin-top: -300px;
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(249, 115, 22, 0.4);
            animation: glow 2s infinite;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .feature-card {
            animation: fadeInUp 1s ease-out both;
            position: relative;
            overflow: hidden;
        }

        .feature-card:nth-child(1) { animation-delay: 0.6s; }
        .feature-card:nth-child(2) { animation-delay: 0.8s; }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-accent);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: var(--gradient-secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 3s infinite;
        }

        .stats-container {
            animation: slideInLeft 1s ease-out 1s both;
        }

        .cta-section {
            animation: slideInRight 1s ease-out 1.2s both;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: floatShape 15s infinite linear;
        }

        .shape:nth-child(1) {
            width: 100px;
            height: 100px;
            background: var(--gradient-primary);
            border-radius: 30%;
            top: 10%;
            left: -10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 80px;
            height: 80px;
            background: var(--gradient-secondary);
            border-radius: 50%;
            top: 70%;
            left: -10%;
            animation-delay: -5s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            background: var(--gradient-accent);
            border-radius: 20%;
            top: 40%;
            left: -10%;
            animation-delay: -10s;
        }

        @keyframes floatShape {
            0% {
                transform: translateX(0) translateY(0) rotate(0deg);
            }
            100% {
                transform: translateX(100vw) translateY(-100px) rotate(360deg);
            }
        }

        .section-divider {
            height: 2px;
            background: var(--gradient-primary);
            margin: 3rem 0;
            animation: pulse 2s infinite;
        }

        .logo {
            position: absolute;
            top: 1rem;
            left: 1rem;
            width: 250px;
            height: auto;
            z-index: 20;
        }

        /* Lightness effect styles */
        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30vh;
            z-index: 100;
            background: linear-gradient(
                0deg,
                rgba(26, 18, 11, 1) 75%,
                rgba(26, 18, 11, 0.9) 80%,
                rgba(26, 18, 11, 0.25) 95%,
                rgba(26, 18, 11, 0) 100%
            );
        }

        .text {
            font-family: "Yanone Kaffeesatz", sans-serif;
            font-size: 100px;
            display: flex;
            position: relative;
            top: -10vh;
            left: 49%;
            transform: translateX(-50%);
            user-select: none;
            z-index: 10;
        }

        .wrapper {
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 20px;
        }

        .letter {
            transition: ease-out 1s;
            transform: translateY(40%);
            color: var(--color-primary);
        }

        .wrapper:hover .letter {
            transform: translateY(-200%);
        }

        /* Responsive enhancements */
        @media (max-width: 768px) {
            .text {
                font-size: 50px;
            }
            
            .card {
                margin: 1rem;
            }

            .logo {
                width: 120px;
            }
        }

        /* Loading animation overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--color-background);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeOut 1s ease-out 2s forwards;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 3px solid rgba(249, 115, 22, 0.3);
            border-top: 3px solid #F97316;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans relative">
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

    <header class="words w-full text-center py-16 relative z-10">
        <div class="overlay"></div>
        <div class="text">
            <div class="wrapper">
                <div class="letter">N</div>
            </div>
            <div class="wrapper">
                <div class="letter">S</div>
            </div>
            <div class="wrapper">
                <div class="letter">C</div>
            </div>
            <div class="wrapper">
                <div class="letter">E</div>
            </div>
            <div class="wrapper">
                <div class="letter">T</div>
            </div>
            <div class="wrapper">
                <div class="letter">&nbsp;</div>
            </div>
            <div class="wrapper">
                <div class="letter">Q</div>
            </div>
            <div class="wrapper">
                <div class="letter">U</div>
            </div>
            <div class="wrapper">
                <div class="letter">I</div>
            </div>
            <div class="wrapper">
                <div class="letter">Z</div>
            </div>
            <div class="wrapper">
                <div class="letter">&nbsp;</div>
            </div>
            <div class="wrapper">
                <div class="letter">A</div>
            </div>
            <div class="wrapper">
                <div class="letter">P</div>
            </div>
            <div class="wrapper">
                <div class="letter">P</div>
            </div>
        </div>
        <div class="section-divider max-w-md mx-auto"></div>
    </header>

    <main class="flex flex-col items-center justify-center flex-grow w-full max-w-6xl px-4 mx-auto relative z-10">
        <!-- Welcome Section -->
        <section class="welcome-card card rounded-xl p-10 text-center w-full mb-8">
            <h2 class="text-3xl font-bold mb-6 text-white">
                Welcome to the NSCET Quiz App
            </h2>
            <p class="text-xl mb-10 text-gray-300 leading-relaxed max-w-2xl mx-auto">
                Test your coding skills or manage quizzes with our interactive platform.
                Choose your role below to get started!
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href=".../../client/stu_index.php" class="btn-primary text-white font-semibold py-4 px-8 rounded-xl text-xl transform transition-all duration-300">
                    Student Portal
                </a>
                <a href=".../../client/sta_index.php" class="btn-primary text-white font-semibold py-4 px-8 rounded-xl text-xl transform transition-all duration-300">
                    Staff Portal
                </a>
            </div>
        </section>
    </main>

    <?php include('./footer.php') ?>

    <script>
        // Add smooth scrolling and additional interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add stagger animation to cards
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

            // Parallax effect for background elements
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const particles = document.querySelectorAll('.particle');
                particles.forEach((particle, index) => {
                    const speed = 0.5 + (index * 0.1);
                    particle.style.transform = translateY(${scrolled * speed}px);
                });
            });
        });
    </script>
</body>
</html>