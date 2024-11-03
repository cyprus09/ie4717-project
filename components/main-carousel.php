<div id="main-carousel" class="main-carousel">
    <!-- carousel wrapper -->
    <div class="carousel-inner">
        <div class="carousel-item slider-bg-01 active">
            <div class="ads-1"></div>
        </div>
        <div class="carousel-item slider-bg-01">
            <div class="ads-2"></div>        
        </div>
        <div class="carousel-item slider-bg-01">
            <div class="ads-3"></div>        
        </div>
        <div class="carousel-item slider-bg-01">
            <div class="ads-4"></div>        
        </div>
    </div>
    <!-- control button -->
    <div class="carousel-controls ">
        <button class="prev" onclick="prevSlide()"><</button>
        <button class="next" onclick="nextSlide()">></button>
    </div>
    <!-- indicator bar -->
    <div class="carousel-indicators d-none d-lg-block">
        <button class="indicator active" onclick="goToSlide(0)"></button>
        <button class="indicator" onclick="goToSlide(1)"></button>
        <button class="indicator" onclick="goToSlide(2)"></button>
        <button class="indicator" onclick="goToSlide(3)"></button>
    </div>
    <!-- JS Scripts -->
    <script src="../scripts/components/main-carousel.js"></script>
</div>