<?php
// Include the database configuration file
include ('db_config.php');

// Fetch FAQ data from the database
$faqSql = "SELECT id, question, answer FROM faq";
$faqResult = $conn->query($faqSql);

// Array to hold FAQ data
$faqs = [];

if ($faqResult->num_rows > 0) {
    while ($row = $faqResult->fetch_assoc()) {
        $faqs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Rajawali Ministry</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Add Font Awesome for social media icons -->
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
            <a href="testimonials.php">Testimoni</a>
            <a href="index.php#contact-us">Hubungi Kami</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Syarat dan Ketentuan</h1>
            <p>Untuk syarat dan ketentuan lebih detail, dapat dilihat di halaman setiap paket tour.</p>
        </div>
    </section>

    <main>
        <section class="terms-conditions" id="terms-conditions">
            <p>Syarat dan ketentuan dalam perjalanan bersama tour kami secara umum adalah sebagai berikut:</p>
            <ul>
                <li>Harga paket tour sudah termasuk tiket pesawat, akomodasi, dan makan selama perjalanan.</li>
                <li>Pembayaran dilakukan dalam dua tahap: DP 50% dan pelunasan 50% sebelum keberangkatan.</li>
                <li>Pembatalan tour setelah pelunasan dikenakan biaya pembatalan sebesar 50% dari total biaya.</li>
                <li>Paket tour tidak termasuk pengeluaran pribadi dan asuransi perjalanan tambahan.</li>
                <li>Peserta diwajibkan membawa dokumen perjalanan yang sah dan berlaku, serta mengikuti protokol
                    kesehatan yang berlaku.</li>
            </ul>
            <p>Untuk informasi lebih lanjut, hubungi layanan pelanggan kami.</p>

        </section>
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

            // Example data for demonstration
            const specialPrice = null;  // Change this to null or undefined if there's no special price

            if (specialPrice) {
                document.getElementById('special-price').style.display = 'block';
                document.getElementById('special-price').innerText = `$${specialPrice}`;
                document.getElementById('normal-price').style.textDecoration = 'line-through';
            }
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

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 40, // Adjust as needed
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript to change the hero background images
        const heroImages = ["images/bethlehem.jpg", "images/nazareth.jpg", "images/jerusalem.jpg"];
        let currentImageIndex = 0;

        function changeHeroImage() {
            const heroSection = document.querySelector('.hero');
            heroSection.style.backgroundImage = `url(${heroImages[currentImageIndex]})`;
            currentImageIndex = (currentImageIndex + 1) % heroImages.length;
        }

        setInterval(changeHeroImage, 5000); // Change image every 5 seconds

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