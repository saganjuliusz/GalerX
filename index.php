<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GelerX - Profesjonalna Galeria</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #0f172a;
            --text-color: #f8fafc;
            --transition-duration: 0.6s;
        }

        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            color: var(--text-color);
        }

        .carousel-container {
            width: 2000px;
            height: 800px;
            position: relative;
            perspective: 1000px;
        }

        .carousel {
            width: 100%;
            height: 100%;
            position: absolute;
            transform-style: preserve-3d;
            transition: transform var(--transition-duration) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .carousel-item {
            position: absolute;
            width: 60%;
            height: 80%;
            left: 20%;
            top: 10%;
            border-radius: 15px;
            overflow: hidden;
            transition: all var(--transition-duration) cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            opacity: 0.6;
            filter: grayscale(30%);
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
            transition: transform var(--transition-duration);
        }

        .carousel-item.active {
            opacity: 1;
            z-index: 10;
            filter: grayscale(0%);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .carousel-item:hover img {
            transform: scale(1.05);
        }

        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(37, 99, 235, 0.2);
            color: var(--text-color);
            border: 2px solid rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            z-index: 20;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-button:hover {
            background: var(--primary-color);
            border-color: var(--text-color);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-button.prev {
            left: 30px;
        }

        .carousel-button.next {
            right: 30px;
        }

        .carousel-pagination {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 20;
        }

        .pagination-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination-dot.active {
            background: var(--primary-color);
            transform: scale(1.2);
        }

        .carousel-counter {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            backdrop-filter: blur(5px);
            z-index: 20;
        }

        @media (max-width: 2000px) {
            .carousel-container {
                width: 100vw;
                height: 40vw;
            }
        }

        @media (max-width: 768px) {
            .carousel-item {
                width: 80%;
                left: 10%;
            }
            
            .carousel-button {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="carousel-container">
        <div class="carousel">
            <div class="carousel-item"><img src="img1.jpg" alt="Slajd 1"></div>
            <div class="carousel-item"><img src="img2.jpg" alt="Slajd 2"></div>
            <div class="carousel-item"><img src="img3.jpg" alt="Slajd 3"></div>
            <div class="carousel-item"><img src="img4.jpg" alt="Slajd 4"></div>
            <div class="carousel-item"><img src="img5.jpg" alt="Slajd 5"></div>
            <div class="carousel-item"><img src="img6.jpg" alt="Slajd 6"></div>
            <div class="carousel-item"><img src="img7.jpg" alt="Slajd 7"></div>
            <div class="carousel-item"><img src="img8.jpg" alt="Slajd 8"></div>
        </div>
        <button class="carousel-button prev"><i class="fas fa-chevron-left"></i></button>
        <button class="carousel-button next"><i class="fas fa-chevron-right"></i></button>
        <div class="carousel-counter">1 / 8</div>
        <div class="carousel-pagination"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const carousel = $('.carousel');
            const items = $('.carousel-item');
            const itemCount = items.length;
            let currentIndex = Math.floor(itemCount / 2);
            let isAnimating = false;
            
            // Tworzenie kropek paginacji
            const paginationContainer = $('.carousel-pagination');
            for (let i = 0; i < itemCount; i++) {
                paginationContainer.append($('<div>').addClass('pagination-dot'));
            }
            const paginationDots = $('.pagination-dot');

            function updateCounter() {
                $('.carousel-counter').text(`${currentIndex + 1} / ${itemCount}`);
            }

            function updatePagination() {
                paginationDots.removeClass('active')
                    .eq(currentIndex).addClass('active');
            }

            function positionItems() {
                items.each(function(index) {
                    const offset = (index - currentIndex + itemCount) % itemCount - Math.floor(itemCount / 2);
                    const zIndex = 10 - Math.abs(offset);
                    const opacity = 1 - (Math.abs(offset) * 0.2);
                    const scale = 1 - (Math.abs(offset) * 0.1);
                    const translateX = offset * 60 + '%';
                    const rotateY = offset * 10 + 'deg';
                    const translateZ = -Math.abs(offset) * 150 + 'px';
                    
                    $(this).css({
                        'transform': `translateX(${translateX}) rotateY(${rotateY}) translateZ(${translateZ}) scale(${scale})`,
                        'z-index': zIndex,
                        'opacity': opacity
                    });
                    $(this).toggleClass('active', index === currentIndex);
                });
                
                updateCounter();
                updatePagination();
            }

            function rotateCarousel(direction) {
                if (isAnimating) return;
                isAnimating = true;
                currentIndex = (currentIndex - direction + itemCount) % itemCount;
                positionItems();
                setTimeout(() => { isAnimating = false; }, 600);
            }

            // Obsługa przycisków
            $('.carousel-button.prev').click(() => rotateCarousel(1));
            $('.carousel-button.next').click(() => rotateCarousel(-1));

            // Obsługa kropek paginacji
            paginationDots.click(function() {
                if (isAnimating) return;
                const newIndex = $(this).index();
                const direction = currentIndex > newIndex ? 1 : -1;
                while (currentIndex !== newIndex) {
                    rotateCarousel(direction);
                }
            });

            // Obsługa klawiszy
            $(document).keydown(function(e) {
                switch(e.which) {
                    case 37: // lewo
                        rotateCarousel(1);
                        break;
                    case 39: // prawo
                        rotateCarousel(-1);
                        break;
                    default: return;
                }
                e.preventDefault();
            });

            // Obsługa gestów dotykowych
            let touchStartX = 0;
            let touchEndX = 0;

            $('.carousel-container').on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });

            $('.carousel-container').on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                const difference = touchStartX - touchEndX;
                
                if (Math.abs(difference) > 50) {
                    if (difference > 0) {
                        rotateCarousel(-1);
                    } else {
                        rotateCarousel(1);
                    }
                }
            });

            // Automatyczne przewijanie
            let autoplayInterval;
            
            function startAutoplay() {
                autoplayInterval = setInterval(() => rotateCarousel(-1), 5000);
            }

            function stopAutoplay() {
                clearInterval(autoplayInterval);
            }

            $('.carousel-container').hover(stopAutoplay, startAutoplay);

            // Inicjalizacja
            positionItems();
            startAutoplay();
        });
    </script>
</body>
</html>