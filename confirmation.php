<?php
// Start the session to use session variables if needed
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ready to Vote! | CCS Voting Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #43a047, #66bb6a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
            50% { transform: translateY(-100px) rotate(180deg); opacity: 1; }
        }

        .confirmation-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            position: relative;
            z-index: 1;
            animation: slideIn 0.8s ease-out;
            margin: 0 auto;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .success-icon i {
            font-size: 50px;
            color: white;
            animation: checkmark 0.8s ease-in-out;
        }

        @keyframes checkmark {
            0% { transform: scale(0) rotate(45deg); }
            50% { transform: scale(1.2) rotate(-10deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-message {
            font-size: 1.3em;
            color: #34495e;
            margin: 30px 0;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.5s both;
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

        .highlight {
            color: #e74c3c;
            font-weight: bold;
            position: relative;
        }

        .highlight::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #e74c3c, #f39c12);
            animation: underline 2s ease-in-out infinite;
        }

        @keyframes underline {
            0%, 100% { width: 0; }
            50% { width: 100%; }
        }

        .vote-button {
            display: inline-block;
            padding: 18px 40px;
            font-size: 1.2em;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 0px;
            cursor: pointer;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: bounceIn 1s ease-out 1s both;
            width: 100%;
            max-width: 300px;
        }

        @keyframes bounceIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .vote-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .vote-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .vote-button:hover::before {
            left: 100%;
        }

        .vote-countdown {
            margin: 30px 0;
            font-size: 1.1em;
            color: #7f8c8d;
        }

        .countdown-number {
            font-size: 1.5em;
            font-weight: bold;
            color: #e74c3c;
            animation: countdown 1s ease-in-out;
        }

        @keyframes countdown {
            0% { transform: scale(1.5); color: #e74c3c; }
            100% { transform: scale(1); color: #2c3e50; }
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .floating-element {
            position: absolute;
            font-size: 20px;
            opacity: 0.1;
            animation: floatAround 10s linear infinite;
        }

        @keyframes floatAround {
            0% { transform: translateX(-100px) translateY(0px) rotate(0deg); }
            100% { transform: translateX(calc(100vw + 100px)) translateY(-100px) rotate(360deg); }
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #e74c3c;
            animation: confettiFall 3s ease-in-out infinite;
        }

        @keyframes confettiFall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: #ecf0f1;
            border-radius: 0px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            width: 0%;
            animation: progressFill 2s ease-out 1.5s forwards;
        }

        @keyframes progressFill {
            to { width: 100%; }
        }

        .emoji {
            font-size: 2em;
            margin: 0 5px;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <!-- Animated background -->
    <div class="particles" id="particles"></div>
    
   

    <div class="confirmation-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
       
        
        <div class="welcome-message">
            Your Student ID has been <span class="highlight">verified</span>!
            <br>
            You're now ready to cast your vote and shape the future of CCS.
            
        </div>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>

        <div class="vote-countdown">
            <span id="countdown">3</span> seconds until voting begins...
        </div>

        <a href="vote_tally.php" class="vote-button">
            <i class="fas fa-vote-yea"></i>
            Enter Voting Booth
        </a>
    </div>

    <script>
        // Create particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Countdown animation
        function startCountdown() {
            const countdownEl = document.getElementById('countdown');
            let count = 3;
            
            const interval = setInterval(() => {
                count--;
                if (count > 0) {
                    countdownEl.textContent = count;
                    countdownEl.classList.add('countdown-number');
                    setTimeout(() => countdownEl.classList.remove('countdown-number'), 1000);
                } else {
                    clearInterval(interval);
                    countdownEl.parentElement.innerHTML = '<span style="color: #27ae60; font-weight: bold;">Ready to vote! </span>';
                }
            }, 1000);
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            startCountdown();
            createConfetti();
            
            // Add hover effects to button
            const voteButton = document.querySelector('.vote-button');
            voteButton.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.05)';
            });
            
            voteButton.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add click sound effect (visual feedback)
        document.querySelector('.vote-button').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create ripple effect
            const button = this;
            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255,255,255,0.3);
                border-radius: 0px;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
                window.location.href = button.href;
            }, 600);
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
