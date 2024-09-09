<?php
// Include the database configuration file
include ('db_config.php');

// Check if tour_id is provided in the query string
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $tour_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Define the SQL query to fetch the tour details
    $sql_tour = "SELECT * FROM tours WHERE id = '$tour_id'";
    $result_tour = $conn->query($sql_tour);

    // Define the SQL query to fetch destinations related to the tour
    $sql_destinations = "SELECT d.name, d.description, d.photo_url FROM destinations d, tours_destinations t where d.id = t.destination_id AND t.tour_id = '$tour_id'";
    $result_destinations = $conn->query($sql_destinations);

    // Define the SQL query to fetch itinerary details
    $sql_itinerary = "SELECT * FROM tour_details WHERE tour_id = '$tour_id'";
    $result_itinerary = $conn->query($sql_itinerary);

    // Check if a tour with the given id exists
    if ($result_tour->num_rows > 0) {
        // Fetch tour details
        $row_tour = $result_tour->fetch_assoc();

        // Fetch destinations
        $destinations = [];
        while ($row_destination = $result_destinations->fetch_assoc()) {
            $destinations[] = [
                'name' => $row_destination['name'],
                'description' => $row_destination['description'],
                'photo_url' => $row_destination['photo_url']
            ];
        }

        // Fetch itinerary details
        $itinerary = [];
        while ($row_itinerary = $result_itinerary->fetch_assoc()) {
            $itinerary[] = $row_itinerary;
        }

        // Split highlights, inclusions, and exclusions into arrays
        $highlights = explode(',', $row_tour["highlights"]);
        $inclusions = explode(',', $row_tour["inclusions"]);
        $exclusions = explode(',', $row_tour["exclusions"]);

        // Assign other fetched data to variables
        $tour_name = $row_tour["name"];
        $description = $row_tour["description"];
        $price = $row_tour["price"];
        $special_price = $row_tour["special_price"];
        $start_date = $row_tour["start_date"];
        $end_date = $row_tour["end_date"];
        $additional_info = $row_tour["additional_info"];
        $spiritual_guide = $row_tour["spiritual_guide"];
        $season = $row_tour["season"];
        $photo_url = $row_tour["photo_url"];
        $brochure_url = $row_tour["brochure_url"];

        // Close database connection
        $conn->close();
    } else {
        echo "Tour not found.";
        exit; // Stop further execution if tour is not found
    }
} else {
    echo "Tour ID not specified.";
    exit; // Stop further execution if tour ID is not specified
}

// Function to calculate and format tour duration
function calculateTourDuration($start_date, $end_date)
{
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);

    // Format duration in days and nights (assuming nights are one less than days)
    $days = $interval->days;
    $nights = $days - 1;

    return "$days Hari, $nights Malam";
}

// Define function to determine icon class based on highlight
function getHighlightIcon($highlight)
{
    $lowercase_highlight = strtolower($highlight);
    if (stripos($lowercase_highlight, 'gereja') !== false) {
        return "fas fa-church fa-lg";
    } elseif (stripos($lowercase_highlight, 'beribadah') !== false || stripos($lowercase_highlight, 'berdoa') !== false) {
        return "fas fa-praying-hands fa-lg";
    } elseif (stripos($lowercase_highlight, 'sungai') !== false || stripos($lowercase_highlight, 'danau') !== false) {
        return "fas fa-water fa-lg"; // Icon for sea, ocean, or lake
    } elseif (stripos($lowercase_highlight, 'laut') !== false) {
        return "fas fa-umbrella-beach fa-lg";
    } else {
        return "fas fa-city fa-lg"; // Default icon
    }
}

// Define function to determine icon class based on inclucions
function getInclusionsIcon($inclusions)
{
    $lowercase_inclusions = strtolower($inclusions);
    if (stripos($lowercase_inclusions, 'pesawat') !== false || stripos($lowercase_inclusions, 'penerbangan') !== false) {
        return "fas fa-plane fa-lg";
    } elseif (stripos($lowercase_inclusions, 'makan') !== false) {
        return "fas fa-pizza-slice fa-lg";
    } elseif (stripos($lowercase_inclusions, 'asuransi') !== false) {
        return "fas fa-shield-alt fa-lg"; // Icon for sea, ocean, or lake
    } elseif (stripos($lowercase_inclusions, 'pengeluaran') !== false || stripos($lowercase_inclusions, 'belanja') !== false) {
        return "fas fa-shopping-bag fa-lg";
    } elseif (stripos($lowercase_inclusions, 'kendaraan') !== false) {
        return "fas fa-bus fa-lg";
    } elseif (stripos($lowercase_inclusions, 'tidur') !== false || stripos($lowercase_inclusions, 'hotel') !== false || stripos($lowercase_inclusions, 'akomodasi') !== false) {
        return "fas fa-hotel fa-lg";
    } else {
        return "fas fa-check fa-lg"; // Default icon
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
            <a href="tours.php" id="active" class="active">Tour</a>
            <a href="promo.php">Promo</a>
            <a href="destinations.php">Album Foto</a>
            <a href="testimonials.php">Testimoni</a>
            <a href="index.php#contact-us">Hubungi Kami</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1><?php echo $tour_name; ?></h1>
            <p><?php echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)); ?></p>
            <p><?php echo calculateTourDuration($start_date, $end_date); ?></p>

            <a href="#" class="hero-button btn btn-secondary active" onclick="showDetails()">Lihat Detail Tour</a>
            <a href="#" class="hero-button btn btn-primary" onclick="showTerms()">Syarat & Ketentuan</a>
        </div>
    </section>

    <main>
        <section class="tour-detail" id="tour-detail">
            <div class="tour-header">
                <h2><?php echo $tour_name; ?></h2>
                <h4><?php echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)); ?>
                </h4>
                <p><?php echo $season, ' Season'; ?></p>
                <div id="price-section">
                    <?php if ($special_price !== null && $special_price !== 0 && $special_price < $price): ?>
                        <p><span
                                style="font-size: 1.2em; color: #999; text-decoration: line-through; font-weight: normal;">$<?php echo number_format($price, 0); ?></span>
                        </p>
                        <p style="font-size: 2em; color: #EF9691; font-weight: bold;">
                            $<?php echo number_format($special_price, 0); ?></p>
                    <?php else: ?>
                        <p style="font-size: 2em; font-weight: bold;">
                            $<?php echo number_format($price, 0); ?></p>
                    <?php endif; ?>
                </div>
                <p id="destination">
                    <?php foreach ($destinations as $key => $destination): ?>
                        <?php echo $destination['name'];
                        if ($key < count($destinations) - 1) {
                            echo ' - ';
                        }
                        ?>
                    <?php endforeach; ?>
                </p>

                <p>Dengan <?php echo $spiritual_guide; ?></p>
                <div class="tour-brochure">
                    <button class="btn btn-primary" onclick="downloadBrochure()">
                        <i class="fas fa-file-download"></i> Unduh Brosur
                    </button>
                </div>
            </div>

            <div class="tour-detail-content">
                <div class="tour-description">
                    <h3>Deskripsi</h3>
                    <p><?php echo $description; ?></p>
                </div>

                <div class="tour-highlights">
                    <h3>Sorotan Tour</h3>
                    <ul>
                        <?php foreach ($highlights as $highlight): ?>
                            <li>
                                <i class="<?php echo getHighlightIcon($highlight); ?>"></i>
                                <span><?php echo htmlspecialchars($highlight); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Image gallery -->
                <div class="tour-images image-gallery">
                    <h3>Destinasi</h3>
                    <?php foreach ($destinations as $destination): ?>
                        <div class="image-container">
                            <img src="<?php echo $destination['photo_url']; ?>"
                                alt="<?php echo htmlspecialchars($destination['name']); ?>">
                            <div class="image-desc"><?php echo htmlspecialchars($destination['description']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Yang Termasuk Section -->
                <div class="tour-inclusions">
                    <h3>Yang Termasuk</h3>
                    <ul>
                        <?php foreach ($inclusions as $inclusion): ?>
                            <li>
                                <i class="<?php echo getInclusionsIcon($inclusion); ?>"></i>
                                <span><?php echo htmlspecialchars($inclusion); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Yang Tidak Termasuk Section -->
                <div class="tour-exclusions">
                    <h3>Yang Tidak Termasuk</h3>
                    <ul>
                        <?php foreach ($exclusions as $exclusion): ?>
                            <li>
                                <i class="fas fa-times"></i> <!-- Always use the cross icon -->
                                <span><?php echo htmlspecialchars($exclusion); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="tour-additional-info">
                    <h3>Informasi Tambahan</h3>
                    <p><?php echo $additional_info; ?></p>
                </div>

                <div class="tour-itinerary">
                    <h3>Itinerari</h3>
                    <table class="itinerary-table">
                        <thead>
                            <tr>
                                <th class="tanggal-column">Tanggal</th>
                                <th class="deskripsi-column">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $start_date = new DateTime($start_date); // Convert start_date to DateTime object
                            foreach ($itinerary as $day) {
                                $current_date = clone $start_date; // Clone start_date to current_date
                                $current_date->modify('+' . ($day['day_number'] - 1) . ' days'); // Modify current_date by adding day_number - 1 days
                                ?>
                                <tr>
                                    <td class="tanggal-column"><?php echo $current_date->format('d M Y'); ?></td>
                                    <!-- Display date -->
                                    <td class="deskripsi-column"><?php echo htmlspecialchars($day['description']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>

        <section class="terms-conditions" id="terms-conditions" style="display:none;">
            <div class="terms-header">
                <h2>Syarat dan Ketentuan</h2>
            </div>
            <div class="terms-content">
                <p>Syarat dan ketentuan paket tour ini adalah sebagai berikut:</p>
                <ul>
                    <li>Harga paket tour sudah termasuk tiket pesawat, akomodasi, dan makan selama perjalanan.</li>
                    <li>Pembayaran dilakukan dalam dua tahap: DP 50% dan pelunasan 50% sebelum keberangkatan.</li>
                    <li>Pembatalan tour setelah pelunasan dikenakan biaya pembatalan sebesar 50% dari total biaya.</li>
                    <li>Paket tour tidak termasuk pengeluaran pribadi dan asuransi perjalanan tambahan.</li>
                    <li>Peserta diwajibkan membawa dokumen perjalanan yang sah dan berlaku, serta mengikuti protokol
                        kesehatan yang berlaku.</li>
                </ul>
                <p>Untuk informasi lebih lanjut, hubungi layanan pelanggan kami.</p>
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

        function downloadBrochure() {
            // Check if $brochure_url is defined and not empty
            <?php if (!empty($brochure_url)): ?>
                var brochureUrl = "<?php echo $brochure_url; ?>";
                console.log("Brochure URL:", brochureUrl); // Log the URL to verify it's correct
                window.open(brochureUrl, "_blank");
            <?php else: ?>
                console.log("Brochure URL is empty."); // Log an error if URL is empty
                // Optionally, show an alert or handle this case as needed
            <?php endif; ?>
        }

        function showDetails() {
            document.getElementById('tour-detail').style.display = 'block';
            document.getElementById('terms-conditions').style.display = 'none';

            // Update button states
            document.querySelector('.hero-button').classList.add('active');
            document.querySelector('.btn-primary').classList.remove('active');
        }

        function showTerms() {
            document.getElementById('tour-detail').style.display = 'none';
            document.getElementById('terms-conditions').style.display = 'block';

            // Update button states
            document.querySelector('.hero-button').classList.remove('active');
            document.querySelector('.btn-primary').classList.add('active');
        }

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