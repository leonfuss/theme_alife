<?php
defined('MOODLE_INTERNAL') || die();

$extraclasses = [];
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes
];

echo $OUTPUT->doctype();
?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Inline critical CSS for immediate effect */
        .alife-custom-header {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .alife-title {
            font-size: 3rem;
            font-weight: 700;
            color: #6b8e23;
            text-align: center;
            margin: 2rem 0;
        }
        .alife-subtitle {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #9b7cb8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .alife-course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        .alife-course-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .alife-course-card:hover {
            transform: translateY(-5px);
        }
        .alife-course-number {
            background: #6b8e23;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }
    </style>
</head>

<body <?php echo $bodyattributes; ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page" class="container-fluid">
    <!-- Custom ALIFE Header -->
    <div class="alife-custom-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 style="color: #6b8e23; margin: 0;">🌱 Startseite</h2>
                </div>
                <div class="col-md-6 text-end">
                    <?php if (isloggedin() && !isguestuser()): ?>
                        <a href="<?php echo new moodle_url('/my/'); ?>" class="btn btn-outline-success me-2">Meine Kurse</a>
                        <a href="<?php echo new moodle_url('/login/logout.php'); ?>" class="btn btn-outline-danger">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo new moodle_url('/login/index.php'); ?>" class="btn btn-success">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="container">
        <h1 class="alife-title">
            Willkommen bei AL<span class="alife-subtitle">IFE</span>
        </h1>
        
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div style="background: #f5f2f8; padding: 2rem; border-radius: 15px; text-align: center;">
                    <h3 style="color: #9b7cb8; margin-bottom: 1rem;">ALIFE – Das steht für:</h3>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-2">
                            <div style="color: #9b7cb8;">⚙️ Adaptiv</div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div style="color: #9b7cb8;">📊 Evidenzbasiert</div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div style="color: #9b7cb8;">✓ Wirksam</div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div style="color: #9b7cb8;">🔄 Aktuell</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Section -->
        <h2 style="color: #6b8e23; font-size: 2rem; margin-bottom: 2rem;">Verfügbare Kurse</h2>
        
        <div class="alife-course-grid">
            <!-- Course 1: Digital Media -->
            <div class="alife-course-card">
                <div class="alife-course-number">01</div>
                <h3 style="color: #6b8e23; margin-bottom: 1rem;">Digitale Medien</h3>
                <p>Lernen Sie den effektiven Einsatz digitaler Medien im Unterricht kennen.</p>
                <a href="<?php echo new moodle_url('/course/index.php'); ?>" class="btn btn-success">Zum Kurs</a>
            </div>

            <!-- Course 2: Digital Literacy -->
            <div class="alife-course-card">
                <div class="alife-course-number">02</div>
                <h3 style="color: #6b8e23; margin-bottom: 1rem;">Digital Literacy</h3>
                <p>Entwickeln Sie digitale Kompetenzen für das 21. Jahrhundert.</p>
                <a href="<?php echo new moodle_url('/course/index.php'); ?>" class="btn btn-success">Zum Kurs</a>
            </div>

            <!-- Course 3: Hochbegabung -->
            <div class="alife-course-card">
                <div class="alife-course-number">03</div>
                <h3 style="color: #6b8e23; margin-bottom: 1rem;">Hochbegabung</h3>
                <p>Erkennung und Förderung hochbegabter Schülerinnen und Schüler.</p>
                <a href="<?php echo new moodle_url('/course/index.php'); ?>" class="btn btn-success">Zum Kurs</a>
            </div>
        </div>
    </div>

    <!-- Required Moodle main content (hidden on frontpage) -->
    <div id="page-content" style="display: none;">
        <?php echo $OUTPUT->main_content(); ?>
    </div>

    <!-- Footer -->
    <footer style="background: #9b7cb8; color: white; padding: 2rem 0; margin-top: 4rem;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <a href="#" style="color: white; text-decoration: none; margin-right: 2rem;">Impressum</a>
                    <a href="#" style="color: white; text-decoration: none; margin-right: 2rem;">Kontakt</a>
                    <a href="/datenschutz" style="color: white; text-decoration: none;">Datenschutzerklärung</a>
                </div>
                <div class="col-md-6 text-end">
                    <h3 style="margin: 0;">ALIFE</h3>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
