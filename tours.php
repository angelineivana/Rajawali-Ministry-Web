<?php
// Include the database configuration file
include ('db_config.php');

// Check if the form is submitted
$isFormSubmitted = isset($_GET['start-date']) && isset($_GET['end-date']);

// Define the initial SQL query to fetch all tours
$sql = "SELECT * FROM tours";

// If the form is submitted, modify the SQL query to filter the results
if ($isFormSubmitted) {
    $startDate = $_GET['start-date'];
    $endDate = $_GET['end-date'];
    $promoFilter = $_GET['promo'];
    $seasonFilter = $_GET['season'];
    $priceFilter = $_GET['price'];

    $conditions = [];
    if (!empty($startDate)) {
        $conditions[] = "start_date >= '$startDate'";
    }
    if (!empty($endDate)) {
        $conditions[] = "end_date <= '$endDate'";
    }
    if ($promoFilter !== '') {
        if ($promoFilter == 'true') {
            $conditions[] = "special_price IS NOT NULL";
        } else {
            $conditions[] = "special_price IS NULL";
        }
    }
    if ($seasonFilter !== '') {
        $conditions[] = "season = '$seasonFilter'";
    }
    if ($priceFilter !== '') {
        list($minPrice, $maxPrice) = explode('-', $priceFilter);
        $conditions[] = "price BETWEEN $minPrice AND $maxPrice";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }
}

$result = $conn->query($sql);

// Fetch special price tours if the form is not submitted
$specialResult = null;
if (!$isFormSubmitted) {
    $specialSql = "SELECT id, name, photo_url, start_date, end_date, price, special_price FROM tours WHERE special_price IS NOT NULL";
    $specialResult = $conn->query($specialSql);
}
?>

<!DOCTYPE html>
<html lang="id">

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
        <a href="#" class="logo"><img src="images/logo rm-no bg.png" alt="Logo Rajawali Ministry"></a>
        <div class="menu-icon" id="menu-icon">
            <i class="fas fa-bars"></i>
        </div>
        <div class="nav-links" id="nav-links">
            <a href=index.php>Beranda</a>
            <a href="#" id="active" class="active">Tour</a>
            <a href=promo.php>Promo</a>
            <a href=destinations.php>Album Foto</a>
            <a href=testimonials.php>Testimoni</a>
            <a href=index.php#contact-us id="active">Hubungi Kami</a>
        </div>
    </nav>

    <main class="tours-page">
        <h2>Cari Tour Sesuai Keinginan Anda</h2>

        <form class="tour-search-form" action="tours.php" method="GET">
            <div class="form-container">
                <div class="form-date">
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
                </div>
                <div class="form-group filter-group">
                    <div class="filter-options">
                        <label for="promo-filter">Promo</label>
                        <select id="promo-filter" name="promo">
                            <option value="" <?php echo (!isset($_GET['promo']) || $_GET['promo'] === '') ? 'selected' : ''; ?>>Semua</option>
                            <option value="true" <?php echo (isset($_GET['promo']) && $_GET['promo'] === 'true') ? 'selected' : ''; ?>>Harga Promo</option>
                            <option value="false" <?php echo (isset($_GET['promo']) && $_GET['promo'] === 'false') ? 'selected' : ''; ?>>Harga Normal</option>
                        </select>

                        <label for="season-filter">Season</label>
                        <select id="season-filter" name="season">
                            <option value="" <?php echo (!isset($_GET['season']) || $_GET['season'] === '') ? 'selected' : ''; ?>>Semua</option>
                            <option value="low" <?php echo (isset($_GET['season']) && $_GET['season'] === 'low') ? 'selected' : ''; ?>>Low Season</option>
                            <option value="high" <?php echo (isset($_GET['season']) && $_GET['season'] === 'high') ? 'selected' : ''; ?>>High Season</option>
                        </select>

                        <label for="price-filter">Range Harga</label>
                        <select id="price-filter" name="price">
                            <option value="" <?php echo (!isset($_GET['price']) || $_GET['price'] === '') ? 'selected' : ''; ?>>Semua</option>
                            <option value="0-500" <?php echo (isset($_GET['price']) && $_GET['price'] === '0-500') ? 'selected' : ''; ?>>$0 - $500</option>
                            <option value="501-1000" <?php echo (isset($_GET['price']) && $_GET['price'] === '501-1000') ? 'selected' : ''; ?>>$501 - $1000</option>
                            <option value="1001-2000" <?php echo (isset($_GET['price']) && $_GET['price'] === '1001-2000') ? 'selected' : ''; ?>>$1001 - $2000</option>
                            <option value="2001-5000" <?php echo (isset($_GET['price']) && $_GET['price'] === '2001-5000') ? 'selected' : ''; ?>>$2001 - $5000</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Cari Tour</button>
            </div>
        </form>

        <section class="tour-section">
            <?php if (!$isFormSubmitted): ?>
                <h2>Harga Spesial</h2>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        if ($specialResult->num_rows > 0) {
                            while ($row = $specialResult->fetch_assoc()) {
                                echo '<a href="tour-detail.php?id=' . $row['id'] . '" class="swiper-slide">';
                                echo '<img src="' . $row['photo_url'] . '" alt="' . $row['name'] . '">';
                                echo '<div class="tour-details">';
                                echo '<h3>' . $row['name'] . '</h3>';
                                echo '<h5>' . date('d M Y', strtotime($row['start_date'])) . ' - ' . date('d M Y', strtotime($row['end_date'])) . '</h5>';
                                echo '<p>';
                                if ($row['special_price'] != null && $row['special_price'] != 0.00) {
                                    echo '<span style="text-decoration: line-through;">$' . number_format($row['price'], 0) . '</span> ';
                                    echo '<span style="color: #EF9691;">$' . number_format($row['special_price'], 0) . '</span>';
                                } else {
                                    echo '$' . number_format($row['price'], 0);
                                }
                                echo '</p>';
                                echo '</div></a>';
                            }
                        } else {
                            echo '<p>No special price tours found.</p>';
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>


            <div class="tour-result">
                <h3>Hasil Pencarian</h3>
                <?php if ($result->num_rows > 0): ?>
                    <p><?php echo $result->num_rows; ?> Paket Tour Tersedia</p>
                    <div class="tour-list">
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="tour-card">';
                            echo '<img src="' . $row['photo_url'] . '" alt="Gambar Tour">';
                            echo '<div class="tour-info">';
                            echo '<h3>' . $row['name'] . '</h3>';
                            echo '<h4>' . date('d M Y', strtotime($row['start_date'])) . ' - ' . date('d M Y', strtotime($row['end_date'])) . '</h4>';
                            echo '<p id="destination">' . $row['description'] . '</p>';
                            echo '<p><strong>Highlight:</strong> ' . $row['highlights'] . '</p>';
                            echo '<p><strong>Termasuk:</strong> ' . $row['inclusions'] . '</p>';
                            echo '</div>';
                            echo '<div class="season">';
                            echo '<p>' . $row['season'] . ' season</p>';
                            echo '</div>';
                            echo '<div class="right-side">';

                            // Display prices based on special_price comparison
                            if ($row['special_price'] !== null && $row['special_price'] < $row['price']) {
                                echo '<p><span style="font-size: 0.8em; color: #999; text-decoration: line-through; font-weight: normal;">$' . number_format($row['price'], 0) . '</span></p>';
                                echo '<p style="font-size: 1.6em; color: #EF9691; font-weight: bold;">$' . number_format($row['special_price'], 0) . '</p>';
                            } else {
                                echo '<p>$' . number_format($row['price'], 0) . '</p>';
                            }

                            echo '<a href="tour-detail.php?id=' . $row['id'] . '" class="btn btn-primary">Detail</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <p>No tours found for your criteria.</p>
                <?php endif; ?>
            </div>

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
    <!-- WhatsApp Button -->
    <a href="https://wa.me/6281384454455" target="_blank" class="whatsapp-button">
        <i class="fab fa-whatsapp"></i>
    </a>
    <!-- Back to Top Button -->
    <button onclick="scrollToTop()" id="back-to-top-btn" title="Kembali ke atas"><i
            class="fas fa-arrow-up"></i></button>
</body>

</html>