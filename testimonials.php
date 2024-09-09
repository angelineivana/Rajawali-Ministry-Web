<?php
// Include the database configuration file
include ('db_config.php');

// Fetch the latest testimonial from the database
$sql = "SELECT * FROM testimonials WHERE video_url IS NOT NULL ORDER BY created_at DESC LIMIT 1";
$latestResult = $conn->query($sql);

$sql = "SELECT * FROM testimonials ORDER BY created_at DESC";
$result = $conn->query($sql);
$testimonials = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tour - Rajawali Ministry</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Add Font Awesome for star icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>
    <nav id="navbar">
        <a href="#" class="logo"><img src="images/logo rm-no bg.png" alt="Logo Rajawali Ministry"></a>
        <div class="menu-icon" id="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
        <div class="nav-links" id="nav-links">
            <a href="index.php">Beranda</a>
            <a href="tours.php">Tour</a>
            <a href="promo.php">Promo</a>
            <a href="destinations.php">Album Foto</a>
            <a href="#" id="active" class="active">Testimoni</a>
            <a href="index.php#contact-us">Hubungi Kami</a>
        </div>
    </nav>

    <!-- Testimonials Section -->
    <main class="testimonial-page">
        <div class="container">
            <div class="testimonial-video">
                <?php if ($latestResult->num_rows > 0): ?>
                <?php $testimonial = $latestResult->fetch_assoc(); ?>
                <div class="testimonial-text">
                    <p>"
                        <?php echo $testimonial['review']; ?>"
                    </p>
                    <div class="testimonial-info">
                        <span class="author">-
                            <?php echo $testimonial['customer_name']; ?>
                        </span>
                        <div class="departure-date">Tanggal keberangkatan:
                            <?php echo date('d F Y', strtotime($testimonial['date'])); ?>
                        </div>
                        <div class="rating">
                            <div class="stars" data-rating="<?php echo $testimonial['rating']; ?>"></div>
                        </div>
                    </div>
                </div>

                <div class="video-wrapper">
                    <?php if (!empty($testimonial['video_url'])): ?>
                    <iframe width="420" height="315" src="<?php echo $testimonial['video_url']; ?>">
                    </iframe>
                    <?php else: ?>
                    <p>No video available.</p>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <p>No testimonials found.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="stats-section">
            <h2 class="section-title">Perjalanan Kami</h2>
            <p class="section-subtitle">Nikmati pengalaman ziarah yang mengesankan dengan Rajawali Ministry. Dengan
                ratusan destinasi terbaik di seluruh dunia, kami menawarkan pengalaman yang tidak terlupakan.</p>
            <!-- Icons for tour statistics -->
            <div class="tour-stats">
                <div>
                    <i class="fas fa-suitcase"></i>
                    <span>1000+</span>
                    <p>Paket Tour</p>
                </div>
                <div>
                    <i class="fas fa-users"></i>
                    <span>5000+</span>
                    <p>Total Peserta</p>
                </div>
                <div>
                    <i class="fas fa-calendar-alt"></i>
                    <span>20+</span>
                    <p>Tahun Berjalan</p>
                </div>
            </div>
        </div>

        <div class="other-testimonials">
            <h2>Check Other Testimonials</h2>
            <div class="testimonial-navigation">
                <button id="prevTestimonial" class="arrow-button"><i class="fas fa-arrow-left"></i></button>
                <div id="testimonial-display" class="testimonial-display">
                    <!-- Testimonial content will be inserted by JavaScript -->
                </div>
                <button id="nextTestimonial" class="arrow-button"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-columns">
            <div class="footer-column">
                <h3>Contact</h3>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Alamat: Pakuwon Trade Center Lt LG (Sebelah Samsat),
                        Surabaya, Jawa Timur, Indonesia</li>
                    <li><i class="fas fa-phone-alt"></i> No. WhatsApp: +62 813 8445 4455</li>
                    <li><i class="fas fa-envelope"></i> Email: info@rajawalimistry.com</li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>YouTube Rajawali</h3>
                <p><a href="https://www.youtube.com/@rajawaliministry_" target="_blank">Kunjungi Channel YouTube
                        kami</a></p>
            </div>
            <div class="footer-column">
                <h3>Informasi Lain</h3>
                <ul>
                    <li><a href="syarat-ketentuan.php">Syarat & Ketentuan</a></li>
                    <li><a href="faq.php">FAQ (Frequently Asked Questions)</a></li>
                </ul>
            </div>
        </div>
        <div class="social-media">
            <a href="https://www.instagram.com/rajawaliministry_pilgrimage" target="_blank"
                class="social-icon fab fa-instagram"></a>
            <a href="https://www.facebook.com/p/PT-Rajawali-Ministry-100063903619628/" target="_blank"
                class="social-icon fab fa-facebook"></a>
            <a href="https://wa.me/6281384454455" target="_blank" class="social-icon fab fa-whatsapp"></a>
        </div>
        <p class="copyright">©2024 Rajawali Ministry. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript to handle navbar transparency on scroll -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuIcon = document.getElementById("menu-icon");
            const navLinks = document.getElementById("nav-links");

            menuIcon.addEventListener("click", function () {
                navLinks.classList.toggle("active");
                navbar.classList.toggle("navbar-active");
            });
        });

        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 0) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        window.addEventListener('scroll', function () {
            var backToTopBtn = document.getElementById('back-to-top-btn');

            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'block';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        // Function to create star ratings dynamically
        function createStarRating(starsContainer, rating) {
            const starsTotal = 5; // Total number of stars

            starsContainer.innerHTML = ""; // Clear existing content

            // Fill stars dynamically
            for (let i = 0; i < starsTotal; i++) {
                const star = document.createElement("i");
                star.classList.add("fas", "fa-star");
                if (i < rating) {
                    star.classList.add("filled");
                }
                starsContainer.appendChild(star);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.stars').forEach(starsContainer => {
                const rating = parseInt(starsContainer.getAttribute('data-rating'));
                createStarRating(starsContainer, rating);
            });
        });

        // JavaScript for testimonials navigation
        const testimonials = <?php echo json_encode($testimonials); ?>;
        let currentIndex = 0;

        function displayTestimonial(index) {
            const testimonial = testimonials[index];
            const testimonialDisplay = document.getElementById('testimonial-display');
            const testimonialContent = 
            `
                    <div class="testimonial-text">
                        <p>"${testimonial.review}"</p>
                        <div class="testimonial-info">
                            <span class="author">- ${testimonial.customer_name}</span>
                            <div class="departure-date">Tanggal keberangkatan: ${new Date(testimonial.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</div>
                            <div class="rating">
                                <div class="stars" data-rating="${testimonial.rating}"></div>
                            </div>
                        </div>
                    </div>
                    <div class="video-wrapper">
                        ${testimonial.video_url ? `<iframe width="420" height="315" src="${testimonial.video_url}"></iframe>` : ``}
                    </div>
                `;
            testimonialDisplay.innerHTML = testimonialContent;
            document.querySelectorAll('.stars').forEach(starsContainer => {
                const rating = parseInt(starsContainer.getAttribute('data-rating'));
                createStarRating(starsContainer, rating);
            });
        }

        document.getElementById('prevTestimonial').addEventListener('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = testimonials.length - 1;
            }
            displayTestimonial(currentIndex);
        });

        document.getElementById('nextTestimonial').addEventListener('click', function () {
            if (currentIndex < testimonials.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            displayTestimonial(currentIndex);
        });

        // Initial display
        displayTestimonial(currentIndex);
    </script>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/6281384454455" target="_blank" class="whatsapp-button">
        <i class="fab fa-whatsapp"></i>
    </a>
    <!-- Back to Top Button -->
    <button onclick="scrollToTop()" id="back-to-top-btn" title="Kembali ke atas"><i
            class="fas fa-arrow-up"></i></button>
</body>

</html>