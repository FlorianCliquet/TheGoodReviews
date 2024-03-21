<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheGoodReviews</title>
    <link rel="stylesheet" href="style_public.css">
</head>
<body>
    <div class="container">
      <div class="pre-loader">
        <div class="loader"></div>
        <div class="loader-bg"></div>
      </div>
      <div class="loader-content">
        <div class="count"><p>0</p></div>
        <div class="copy"><p class="ml16">TheGoodReviews</p></div>   
      </div>
      <div class="loader-2"></div>
    </div>
    <nav class="glassmorphism-nav">
        <ul>
            <li><a href="../../index.php">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="../login-register/login.php">Login</a></li>
        </ul>
    </nav>
    <div class="site-content">
      <div class="header">
        <h1>T</h1>
        <h1>h</h1>
        <h1>e</h1>
        <h1>G</h1>
        <h1>o</h1>
        <h1>o</h1>
        <h1>d</h1>
        <h1>R</h1>
        <h1>e</h1>
        <h1>v</h1>
        <h1>i</h1>
        <h1>e</h1>
        <h1>w</h1>
        <h1>s</h1>
      </div>
      <footer>
        <div class="footer-copy">
          <p>
            TheGoodReviews is a website that gather gaming enthusiasts to
            discuss around games. You'll find news, reviews of the latest games,
            and be able to share your opinion.
          </p>
        </div>
        <div class="footer-nav">
          <div class="img"></div>
          <div class="img"></div>
        </div>
      </footer>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        function startLoader() {
            let counterElement = document.querySelector(".count p");
            let currentValue = 0;
            
            function updateCounter() {
                if(currentValue < 100){
                    let increment = Math.floor(Math.random() * 10) + 1;
                    currentValue = Math.min(currentValue + increment, 100);
                    counterElement.textContent = currentValue;

                    let delay = Math.floor(Math.random() * 200) + 25;
                    setTimeout(updateCounter, delay);
                }
            }

            updateCounter();
        }
        startLoader();
        gsap.to(".count", { opacity: 0, delay: 3.5, duration: 0.5});

        let textWrapper = document.querySelector(".ml16");
        textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

        anime.timeline({ loop: false})
            .add({
                targets: '.ml16 .letter',
                translateY:  [-100, 0],
                easing: "easeOutExpo",
                duration: 1500,
                delay: (el, i) => 30*i
            })
            .add({
                targets: '.ml16 .letter',
                translateY:  [0, 100],
                easing: "easeOutExpo",
                duration: 3000,
                delay: (el, i) => 2000 + 30*i
            });

            gsap.to(".pre-loader", {
                scale: 0.5,
                ease: "power4.inOut",
                duration: 2,
                delay: 3,
                onComplete: function() {
                  document.querySelector('.glassmorphism-nav').style.opacity = '0';
                  document.querySelector('.glassmorphism-nav').style.display = 'flex';

                
                gsap.to(".glassmorphism-nav", {
                    opacity: 1,
                    duration: 1,
                    ease: "power1.inOut"
                }); 
                }
            })

            gsap.to(".loader", {
                height: "0",
                ease: "power4.inOut",
                duration: 1.5,
                delay: 3.75
            })

            gsap.to(".loader-bg", {
                height: "0",
                ease: "power4.inOut",
                duration: 1.5,
                delay: 4
            })

            gsap.to(".loader-2", {
                clipPath: "polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)",
                ease: "power4.inOut",
                duration: 1.5,
                delay: 3.5
            })

            gsap.from(".header h1", {
                y: 200,
                ease: "power4.inOut",
                duration: 1,
                delay: 3.75,
                stagger: 0.05
            })

            gsap.to(".img", {
                clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
                ease: "power4.inOut",
                duration: 1.5,
                delay: 4.5,
                stagger: 0.25
            })

    </script>
</body>
</html>
