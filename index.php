<?php
// Include the database configuration file
include ('db_config.php');

// Fetch tour data from the database
$sql = "SELECT id, name, photo_url, start_date, end_date, price FROM tours";
$result = $conn->query($sql);

$promoSql = "SELECT * FROM tours WHERE start_date = (SELECT MAX(start_date) FROM tours WHERE special_price IS NOT NULL and special_price != 0)";
$promoResult = $conn->query($promoSql);

// Fetch photos data from the database
$photoSql = "SELECT url FROM photos";
$photoResult = $conn->query($photoSql);

// Array to hold photo URLs
$photoUrls = [];

if ($photoResult->num_rows > 0) {
    while ($row = $photoResult->fetch_assoc()) {
        $photoUrls[] = $row['url'];
    }
}

// Fetch destinations data from the database
$destinationSql = "SELECT name, description, photo_url FROM destinations";
$destinationResult = $conn->query($destinationSql);

// Array to hold destinations data
$destinations = [];

if ($destinationResult->num_rows > 0) {
    while ($row = $destinationResult->fetch_assoc()) {
        $destinations[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rajawali Ministry</title>
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
        <a href="#" class="logo"><img src="images/logo rm-no bg.png" alt="Rajawali Ministry Logo"></a>
        <div class="menu-icon" id="menu-icon"> <!-- Corrected id="menu-icon" -->
            <i class="fas fa-bars"></i>
        </div>
        <div class="nav-links" id="nav-links">
            <a href=# class="active">Beranda</a>
            <a href=tours.php>Tour</a>
            <a href=promo.php>Promo</a>
            <a href=destinations.php>Album Foto</a>
            <a href=testimonials.php>Testimoni</a>
            <a href=#contact-us>Hubungi Kami</a>
        </div>
    </nav>

    <div class="hero-section">
        <div class="hero-content">
            <h1>Selamat Datang di Rajawali Ministry</h1>
            <p>Rasakan Tanah Suci seperti tidak pernah sebelumnya</p>
        </div>
        <form class="tour-search-form" action="tours.php" method="GET">
            <div class="form-container">
                <div class="form-group">
                    <label for="start-date">Tanggal Mulai</label>
                    <div class="date-picker">
                        <input type="date" id="start-date" name="start-date"
                            value="<?php echo isset($_GET['start-date']) ? $_GET['start-date'] : ''; ?>" required>
                    </div>
                    <label id="end-date" for="end-date">Tanggal Berakhir</label>
                    <div class="date-picker">
                        <input type="date" id="end-date" name="end-date"
                            value="<?php echo isset($_GET['end-date']) ? $_GET['end-date'] : ''; ?>" required>
                    </div>
                </div>

                <!-- Hidden fields for promo, season, and range harga -->
                <input type="hidden" name="promo" value="">
                <input type="hidden" name="season" value="">
                <input type="hidden" name="price" value="">

                <button type="submit" class="btn btn-primary">Cari Tour</button>
            </div>
        </form>

    </div>

    <!-- Tours Section -->
    <section id="tours" class="content-section">
        <h2>Tour Kami</h2>
        <p>Temukan tour menarik kami ke Tanah Suci, termasuk Yerusalem, Betlehem, dan lainnya.</p>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<a href="tour-detail.php?id=' . $row['id'] . '" class="swiper-slide">';
                        echo '<img src="' . $row['photo_url'] . '" alt="' . $row['name'] . '">';
                        echo '<div class="tour-details">';
                        echo '<h3>' . $row['name'] . '</h3>';
                        echo '<h5>' . date('d M Y', strtotime($row['start_date'])) . ' - ' . date('d M Y', strtotime($row['end_date'])) . '</h5>';
                        echo '<p>$' . number_format($row['price'], 0) . '</p>'; // Display price as integer
                        echo '</div></a>';
                    }
                } else {
                    // Debugging output
                    echo '<p>Debug: No tours found in the database.</p>';
                }
                ?>
            </div>
        </div>
        <a href="tours.php" class="btn btn-secondary">Lihat Semua Tour</a>
    </section>

    <!-- Promo Section -->
    <section id="promo" class="content-section promo-section israel-background section-transition">
        <div class="promo-content">
            <div class="coupon">
                <i class="fas fa-tags coupon-icon"></i>
                <div class="coupon-content">
                    <div class="coupon-header">
                        <?php
                        if ($promoResult->num_rows > 0) {
                            $promoRow = $promoResult->fetch_assoc();
                            echo '<h3 class="promo-name" style="color: rgb(255, 255, 255)">' . $promoRow['name'] . '</h3>';
                            echo '<h3 class="price" style="color: rgb(255, 255, 255)">$' . number_format($promoRow['price'], 0) . '</h3>'; // Display price as integer
                            echo '<div class="promo-disc-container">';
                            echo '<h1 class="promo-disc">$' . number_format($promoRow['special_price'], 0) . '</h1>'; // Display special price as integer
                            echo '<h1 class="promo-disc-bg">' . number_format($promoRow['special_price'], 0) . '</h1>'; // Display special price as integer
                            echo '</div>';

                            // Calculate countdown based on the start date
                            $startDateTimestamp = strtotime($promoRow['start_date']);
                            $nowTimestamp = time();
                            $diff = $startDateTimestamp - $nowTimestamp;
                            $days = floor($diff / (60 * 60 * 24));
                            $hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
                            $minutes = floor(($diff % (60 * 60)) / 60);
                            $seconds = $diff % 60;

                            echo '<div class="countdown">';
                            echo '<div class="countdown-item">';
                            echo '<div class="countdown-value" id="days">' . str_pad($days, 2, '0', STR_PAD_LEFT) . '</div>';
                            echo '<div class="countdown-label">Hari</div>';
                            echo '</div>';
                            echo '<div class="countdown-item">';
                            echo '<div class="countdown-value" id="hours">' . str_pad($hours, 2, '0', STR_PAD_LEFT) . '</div>';
                            echo '<div class="countdown-label">Jam</div>';
                            echo '</div>';
                            echo '<div class="countdown-item">';
                            echo '<div class="countdown-value" id="minutes">' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . '</div>';
                            echo '<div class="countdown-label">Menit</div>';
                            echo '</div>';
                            echo '<div class="countdown-item">';
                            echo '<div class="countdown-value" id="seconds">' . str_pad($seconds, 2, '0', STR_PAD_LEFT) . '</div>';
                            echo '<div class="countdown-label">Detik</div>';
                            echo '</div>';
                            echo '</div>';

                            // Create the "Pesan Sekarang" button link
                            $tourId = $promoRow['id'];
                            echo '<a href="tour-detail.php?id=' . $tourId . '" class="btn btn-primary">Pesan Sekarang</a>';
                        } else {
                            // If no promo found
                            echo '<h3 class="promo-name" style="color: rgb(255, 255, 255)">Tidak Ada Promo Saat Ini</h3>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- "Mengapa Harus Memilih Rajawali Ministry?" Section -->
    <section id="why-choose-us" class="content-section">
        <h2>Mengapa Harus Memilih Rajawali Ministry?</h2>
        <div class="reasons-content">
            <div class="reason">
                <i class="fas fa-globe-americas"></i>
                <h3>Pengalaman Tak Tertandingi</h3>
                <p>Rajawali Ministry telah menjalankan lebih dari 50 tour ke Israel, memberikan pengalaman dan
                    pengetahuan yang tak tertandingi untuk setiap perjalanan.</p>
            </div>
            <div class="reason">
                <i class="fas fa-headset"></i>
                <h3>Pelayanan Pelanggan Terbaik</h3>
                <p>Kami berkomitmen untuk memberikan pelayanan pelanggan yang luar biasa, memastikan setiap detail
                    perjalanan Anda diurus dengan baik.</p>
            </div>
            <div class="reason">
                <i class="fas fa-route"></i>
                <h3>Itinerary yang Berarti</h3>
                <p>Setiap tour dirancang dengan cermat untuk memberikan pengalaman spiritual yang mendalam dan
                    pengetahuan sejarah yang komprehensif.</p>
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section id="about-us" class="content-section about-us-section nazareth-background">
        <div class="about-us-content">
            <div class="about-us-text">
                <div class="about">
                    <h2>ABOUT US</h2>
                    <p>Rajawali Ministry is dedicated to facilitating meaningful pilgrimages to Israel, including
                        Jerusalem, Bethlehem, Nazareth, and other sacred sites. Lorem ipsum dolor sit amet, consectetour
                        adipiscing elit. Phasellus vitae tourpis quis nisi feugiat sagittis vel et dolor. Nulla
                        facilisi.
                        Integer auctor augue id erat rhoncus, in fringilla ipsum ultricies. Suspendisse potenti. Mauris
                        non mauris ac lectus fermentum congue sed eget ipsum. Donec et enim in odio tincidunt hendrerit.
                        Sed vitae ornare arcu. Aliquam erat volutpat.</p>
                </div>
                <div class="mission-vision-section">
                    <div class="mission">
                        <h2>OUR MISSION</h2>
                        <p>To provide transformative pilgrimage experiences to the Holy Land, enriching spiritual
                            journeys and deepening faith connections.</p>
                    </div>
                    <div class="vision">
                        <h2>OUR VISION</h2>
                        <p>To be a leading provider of spiritual pilgrimages, offering unparalleled expertise, service,
                            and insight into the Holy Land's history and significance.</p>
                    </div>
                </div>
            </div>
            <div class="gallery-museum">
                <?php
                // Loop through the photo URLs and create img tags dynamically
                foreach ($photoUrls as $url) {
                    echo '<img src="' . $url . '" alt="Gallery Image">';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Destinations Section -->
    <section id="destinations" class="content-section">
        <h2>Destinasi</h2>
        <div class="destinations-content">
            <?php
            // Loop through destinations data and create destination cards dynamically
            foreach ($destinations as $destination) {
                echo '<div class="destination">';
                echo '<img src="' . $destination['photo_url'] . '" alt="' . $destination['name'] . '">';
                echo '<h3>' . $destination['name'] . '</h3>';
                echo '<p>' . $destination['description'] . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="content-section israel-background">
        <h2 class="section-title">Perjalanan Kami</h2>
        <p class="section-subtitle">Nikmati pengalaman ziarah yang mengesankan dengan Rajawali Ministry. Dengan ratusan
            destinasi terbaik di seluruh dunia, kami menawarkan pengalaman yang tidak terlupakan.</p>
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
        <a href="testimonials.php" class="btn btn-secondary">Baca Testimoni</a>
    </section>

    <!-- Contact Us Section -->
    <section id="contact-us" class="content-section">
        <h2>Hubungi Kami</h2>
        <div class="contact-content">
            <form class="contact-form" action="process_contact.php" method="POST">
                <h3>Kirim Pesan ke Kami</h3>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">No. Telepon (WhatsApp)</label>
                    <input type="phone" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="message">Pesan</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
            <div class="contact-info">
                <h3>Informasi Kontak</h3>
                <p><i class="fas fa-map-marker-alt"></i> Alamat: Pakuwon Trade Center Lt LG (Sebelah Samsat), Surabaya,
                    Jawa Timur, Indonesia</p>
                <p><i class="fas fa-phone-alt"></i> No. WhatsApp: +62 813 8445 4455</p>
                <p><i class="fas fa-envelope"></i> Email: info@rajawalimistry.com</p>
                <div id="map" style="height: 200px; width: 100%;"></div>
            </div>
        </div>
    </section>


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
                navbar.classList.toggle("navbar-active"); // Toggle navbar-active class on navbar
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

        // Function to scroll to the top of the page
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }

        // Show or hide the back-to-top button based on scroll position
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

        // Initialize the map
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('map').setView([-7.2903, 112.6738], 13); // Surabaya coordinates

            // Set up the OpenStreetMap layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            // Add a marker for Surabaya
            var marker = L.marker([-7.2903, 112.6738]).addTo(map);
            marker.bindPopup("<b>Rajawali Ministry</b><br>Office").openPopup();
        });

        // Calculate remaining time until a certain date
        function countdownTimer(endTime) {
            const now = new Date().getTime();
            const difference = endTime - now;

            if (difference <= 0) {
                // If end time is reached, stop the timer
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                retourn;
            }

            // Calculate days, hours, minutes and seconds
            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            // Display the countdown values
            document.getElementById('days').textContent = days;
            document.getElementById('hours').textContent = formatTime(hours);
            document.getElementById('minutes').textContent = formatTime(minutes);
            document.getElementById('seconds').textContent = formatTime(seconds);

            // Update the countdown every 1 second
            setTimeout(() => countdownTimer(endTime), 1000);
        }

        // Format time values (add leading zeros if needed)
        function formatTime(time) {
            return time < 10 ? `0${time}` : time;
        }

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

    <!-- Leaflet Map CSS -->
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/6281384454455" target="_blank" class="whatsapp-button">
        <i class="fab fa-whatsapp"></i>
    </a>
    <!-- Back to Top Button -->
    <button onclick="scrollToTop()" id="back-to-top-btn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
</body>

</html>