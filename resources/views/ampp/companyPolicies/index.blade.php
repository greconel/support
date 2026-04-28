<x-layouts.ampp :title="__('BMK Afspraken')">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #fafbfc;
        }
        .hero-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #e2e8f0 100%);
            color: #0369a1;
            padding: 5rem 0;
            margin: -2.5rem -2.5rem -2.5rem -2.5rem;
            position: relative;
            border-bottom: 4px solid transparent;
            border-image: linear-gradient(90deg, #0369a1, #059669, #7c3aed, #dc2626) 1;
        }
        .banner-img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            margin-right: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(3, 105, 161, 0.2);
            transition: transform 0.3s ease;
        }
        .banner-img:hover {
            transform: scale(1.05);
        }
        .hero-content {
            position: relative;
        }
        .hero-text {
            flex: 1;
        }
        
        .category-button {
            border: 2px solid #e9ecef;
            background: white;
            color: #495057;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 0.25rem;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .category-button:hover {
            border-color: #0369a1;
            color: #0369a1;
            background: #f0f9ff;
        }
        
        .category-button.active {
            background: linear-gradient(135deg, #0369a1, #0284c7);
            color: white;
            border-color: #0369a1;
            transform: translateY(-5px) scale(1.04);
            box-shadow: 0 18px 40px rgba(3, 105, 161, 0.5);
        }
        .category-title {
            font-weight: bold;
            color: #212529;
            transition: color 0.3s;
        }
        .category-button.active .category-title {
            color: #fff;
        }
        
        /* Verbeterde styling voor categorie content */
        .category-content {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .category-content .detail-header {
            font-size: 1.4rem;
            font-weight: 600;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 3px solid #0369a1;
        }
        
        .category-content .detail-content {
            padding: 2rem;
        }
        
        .category-content h5 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
        }
        
        .category-content h6 {
            font-size: 1.15rem;
            margin-bottom: 1rem;
        }
        
        .category-content .list-unstyled li,
        .category-content .info-list li {
            font-size: 1.05rem;
            margin-bottom: 0.8rem;
            line-height: 1.5;
        }
        
        .category-content .bg-light {
            padding: 1.5rem;
            border-left: 4px solid #0369a1;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .category-content .list-group-item {
            padding: 1rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .category-content .list-group-item:last-child {
            border-bottom: none;
        }
        
        .category-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #495057;
            transition: all 0.3s ease;
        }
        .category-button:hover .category-icon {
            background: linear-gradient(135deg, #0369a1, #0284c7);
            color: white;
            transform: scale(1.1);
        }
        .category-button.active .category-icon {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: scale(1.1);
        }
        
        .detail-section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0, 0.06);
            margin-bottom: 3rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .detail-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0,0,0, 0.1);
        }
        .mission-values-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
            margin: 0 -2.5rem 4rem -2.5rem;
            position: relative;
        }
        .mission-values-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0369a1, #0284c7, #38bdf8, #0ea5e9, #0369a1);
        }
        .mission-card, .values-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(3, 105, 161, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            height: 100%;
            overflow: hidden;
        }
        .mission-card:hover, .values-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(3, 105, 161, 0.15);
            border-color: #e2e8f0;
        }
        .mission-content, .values-content {
            display: flex;
            align-items: flex-start;
            padding: 2.5rem;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .mission-text {
            font-size: 1.2rem;
            font-weight: 500;
            line-height: 1.6;
            color: #475569;
            word-break: break-word;
            overflow-wrap: anywhere;
        }
        .content-text {
            min-width: 0;
            flex: 1 1 0;
        }
        .mission-img, .values-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 15px;
            flex-shrink: 0;
            box-shadow: 0 5px 15px rgba(3, 105, 161, 0.2);
        }
        .content-text {
            flex: 1;
        }
        .waarden-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .waarden-badge {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            background: #f1f5f9;
            color: #0369a1;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .waarden-badge:hover {
            background: #0369a1;
            color: white;
            transform: scale(1.02);
            background: #0369a1;
            color: white;
            box-shadow: 0 5px 15px rgba(3, 105, 161, 0.3);
        }
        .category-section {
            background: white;
            border-radius: 30px;
            padding: 4rem;
            margin-bottom: 4rem;
            box-shadow: 0 15px 50px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }
        .category-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0369a1, #0284c7, #38bdf8, #0ea5e9, #0369a1);
        }
        .category-button {
            border: 2px solid #e9ecef;
            background: white;
            color: #495057;
            transition: all 0.4s ease;
            cursor: pointer;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .category-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        .category-button:hover::before {
            left: 100%;
        }
        .category-button:hover {
            border-color: #0369a1;
            color: #0369a1;
            background: #f0f9ff;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(3, 105, 161, 0.2);
        }
        .category-button.active {
            background: linear-gradient(135deg, #0369a1, #0284c7);
            color: white;
            border-color: #0369a1;
            transform: translateY(-5px) scale(1.04);
            box-shadow: 0 18px 40px rgba(3, 105, 161, 0.5);
        }
        
        .detail-header {
            background: #f8f9fa;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #DADDE0;
            font-weight: 600;
            color: #212529;
        }
        
        .detail-content {
            padding: 1.25rem;
        }
        
        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .info-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            align-items: flex-start;
        }
        
        .info-list li:last-child {
            border-bottom: none;
        }
        
        .info-icon {
            color: #0369a1;
            margin-right: 0.75rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }
        
        .highlight-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 0.25rem;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .contact-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(3, 105, 161, 0.1);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(3, 105, 161, 0.15);
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .contact-info {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 25px;
            padding: 2rem 1rem;
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }
        .contact-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0369a1, #0284c7, #38bdf8, #0ea5e9, #0369a1);
        }
        .algemeen-section .detail-header {
            font-size: 1.5rem;
            padding: 1.5rem 2rem;
        }
        .algemeen-section .detail-content {
            padding: 2rem;
        }
        .algemeen-section h5 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        .algemeen-section ul {
            padding-left: 1.5rem;
        }
        .algemeen-section ul li {
            margin-bottom: 0.5rem;
        }
    </style>

    <div class="container-fluid">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container hero-content">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-11">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/bmk/welkom.jpg') }}" alt="Welkom bij BMK" class="banner-img">
                            <div class="hero-text">
                                <h1 class="display-3 fw-bold mb-4 text-primary">Welkom bij BMK Solutions</h1>
                                <p class="fs-3 text-muted mb-0">Op deze pagina vind je alle praktische afspraken over de werkvloer van BMK.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission & Values -->
        <div class="mission-values-section">
            <div class="container">
                <div class="row g-5">
                    <div class="col-md-7">
                        <div class="mission-card">
                            <div class="mission-content">
                                <img src="{{ asset('images/bmk/missie.jpg') }}" alt="Missie BMK" class="mission-img">
                                <div class="content-text">
                                    <p class="mb-0 mission-text" >
                                        <span style="font-size:2rem; font-weight:700; color:#0369a1; display:block;">
                                            Wij zijn ADMINISTRATIEVERSNELLERS!
                                        </span>
                                        <br>
                                        <span style="font-size:1.5rem;">
                                            We creëren productievere, efficiëntere en minder stressvolle werkomgevingen door het inzetten van slimme technologie na grondige analyse.
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="values-card">
                            <div class="values-content">
                                <img src="{{ asset('images/bmk/waarden.jpg') }}" alt="Waarden BMK" class="values-img">
                                <div class="content-text">
                                    <div class="waarden-row">
                                        <span class="waarden-badge">VERTROUWEN & RESPECT</span>
                                        <span class="waarden-badge">KLANTGERICHTHEID</span>
                                        <span class="waarden-badge">SAMENWERKING</span>
                                        <span class="waarden-badge">LANGDURIGE RELATIES</span>
                                        <span class="waarden-badge">KWALITEIT</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Navigation -->
        <div class="container">
            <div class="category-section">
                <div class="text-center mb-5">
                    <h2 class="fs-3 fw-bold text-primary mb-2">Interne afspraken</h2>
                    <p class="text-muted fs-4">Selecteer een categorie voor meer informatie</p>
                </div>
                
                <div class="row g-4">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="category-button" data-target="algemeen">
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h5 class="mb-1 category-title">Algemeen</h5>
                            <small>Praktische afspraken</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="category-button" data-target="communicatie">
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5 class="mb-1 category-title">Samenwerking</h5>
                            <small>Communicatie & planning</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="category-button" data-target="kantoor">
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-building"></i>
                            </div>
                            <h5 class="mb-1 category-title">Kantoor</h5>
                            <small>Werkplek & faciliteiten</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="category-button" data-target="tools">
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <h5 class="mb-1 category-title">Tools</h5>
                            <small>Software & systemen</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="category-button" data-target="klantcontact">
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="mb-1 category-title">Klantcontact</h5>
                            <small>Contact & service</small>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="category-button" data-target="privacy">
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5 class="mb-1 category-title">Privacy</h5>
                            <small>Privacy & social media</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Sections -->
        <div class="container">
            <div id="detail-sections">

            <!-- Algemeen Section -->
            <div id="algemeen-details" class="detail-section category-content" style="display: none;">
                <div class="detail-header">
                    <i class="fas fa-info-circle me-2"></i>Algemene informatie & praktische afspraken
                </div>
                <div class="detail-content">
                    <div class="col-12">
                   
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-calendar-day me-2"></i>Werktijden & flexibiliteit
                                    </h6>
                                    <p class="mb-3">Onze <strong>standaardwerkdag</strong> loopt van <strong>8u30 tot 17u00</strong>, van maandag tot en met vrijdag. Omdat niet iedereen hetzelfde ritme heeft, werken we met <strong>glijdende werkuren</strong>: je kan starten tussen <strong>8u00 en 9u00</strong>. Tussen <strong>9u00 en 17u00</strong> rekenen we op je <strong>beschikbaarheid</strong>, omdat in die periode meetings kunnen ingepland worden en we zeker willen zijn dat klanten en collega's je kunnen bereiken.</p>
                                    
                                    <ul class="mb-3">
                                        <li>Kom je uitzonderlijk later of geraak je vast in verkeer? Breng je team even op de hoogte via <strong>Microsoft Teams</strong> of <strong>telefonisch</strong>.</li>
                                        <li>Zo vermijden we misverstanden en weten we meteen dat je veilig onderweg bent.</li>
                                    </ul>
                                    
                                    <p class="mb-3">We <strong>lunchen samen</strong> van <strong>12u30 tot 13u00</strong>. Dat moment gebruiken we niet alleen om te eten, maar ook om bij te praten en even afstand te nemen van onze projecten.</p>
                                    <p class="mb-0">Afwijken van het standaardrooster kan uiteraard, maar enkel <strong>in overleg</strong> met je leidinggevende. We geloven sterk in <strong>flexibiliteit</strong>, zolang het evenwicht tussen <strong>klantentevredenheid</strong> en <strong>teamafspraken</strong> behouden blijft.</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-user-md me-2"></i>Verlof & afwezigheden
                                    </h6>
                                    <p class="mb-3"><strong>Verlof aanvragen</strong> doe je via mail naar <a href="mailto:kevin@bmksolutions.be">kevin@bmksolutions.be</a>. Stuur je aanvraag <strong>minstens twee weken vooraf</strong> door, zodat we projecten tijdig kunnen afstemmen. Voor het jaarlijkse groot verlof verwachten we tegen <strong>eind februari</strong> een voorstel, zodat we onderling rekening kunnen houden met vakantieperiodes.</p>
                                    
                                    <div class="alert alert-info border-start border-4 border-info" style="background-color: #d1ecf1; border-color: #0dcaf0 !important;">
                                        <h6 class="fw-bold text-dark mb-2">
                                            <i class="fas fa-calendar-times me-2 text-info"></i>Collectief verlof 2025
                                        </h6>
                                        <ul class="mb-0 list-unstyled">
                                            <li><i class="fas fa-circle me-2 text-info" style="font-size: 0.5rem;"></i><strong>Maandag 10 november</strong></li>
                                            <li><i class="fas fa-circle me-2 text-info" style="font-size: 0.5rem;"></i><strong>Maandag 22 december 2025 - vrijdag 2 januari 2026</strong></li>
                                        </ul>
                                        <h6 class="fw-bold text-dark mb-2">
                                            <i class="fas fa-calendar-times me-2 text-info"></i>Collectief verlof 2026
                                        </h6>
                                        <ul class="mb-0 list-unstyled">
                                            <li><i class="fas fa-circle me-2 text-info" style="font-size: 0.5rem;"></i><strong>Maandag 22 december 2025 - vrijdag 2 januari 2026</strong></li>
                                            <li><i class="fas fa-circle me-2 text-info" style="font-size: 0.5rem;"></i><strong>Vrijdag 15 mei</strong></li>
                                            <li><i class="fas fa-circle me-2 text-info" style="font-size: 0.5rem;"></i><strong>Donderdag 24 december - donderdag 31 december</strong></li>
                                        </ul>
                                    </div>
                                    
                                    <p class="mb-0">Word je <strong>ziek</strong> of moet je onverwacht thuisblijven door <strong>familiale omstandigheden</strong>, dan laat je dit <strong>telefonisch</strong> weten aan je leidinggevende. Een <strong>doktersattest</strong> mail je zo snel mogelijk door. Had je die dag een <strong>klantafspraak</strong> of een <strong>belangrijke meeting</strong>, verwittig dan ook de betrokken collega's en denk samen na hoe dit kan opgevangen worden.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Communicatie Section -->
            <div id="communicatie-details" class="detail-section category-content" style="display: none;">
                <div class="detail-header">
                    <i class="fas fa-comments me-2"></i>Communicatie & planning
                </div>
                <div class="detail-content">
                    <div class="col-12">
                   
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-broadcast-tower me-2"></i>Communicatiekanalen
                                    </h6>
                                    <p class="mb-3">We gebruiken verschillende kanalen, elk met een duidelijke rol:</p>
                                    <ul class="mb-3">
                                        <li><strong>E-mail</strong> voor formele zaken zoals klantcommunicatie, verlofaanvragen en afwezigheden.</li>
                                        <li><strong>Microsoft Teams</strong> voor snelle vragen, korte meldingen of online vergaderingen. Hier vind je ook onze projectkanalen, waarin we informatie en bestanden delen.</li>
                                           </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-calendar-check me-2"></i>Meetings & overleg
                                    </h6>
                                    <p class="mb-3">We geloven in korte, efficiënte overlegmomenten.</p>
                                    <ul class="mb-3">
                                        <li><strong>Dagelijkse stand-up:</strong> Elke werkdag starten we om 10u00 met een korte stand-up: een moment waarop iedereen deelt waar hij of zij aan werkt en welke obstakels er zijn.</li>
                                        <li><strong>Planningsmeeting:</strong> Op maandag om 10u00 houden we een planningsmeeting, waarin we de prioriteiten van de week afstemmen.</li>
                                        <li><strong>Lessons Learnt:</strong> Elk kwartaal organiseren we een Lessons Learnt-sessie: we kijken terug naar voorbije projecten en bespreken wat goed liep en wat beter kan.</li>
                                        <li><strong>Functioneringsgesprekken:</strong> Jaarlijks voorzien we een functioneringsgesprek, aangevuld met evaluatiemomenten in de eerste maanden na je start.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-tasks me-2"></i>Planning & deadlines
                                    </h6>
                                    <p class="mb-3">Alle projecten en taken beheren we via <strong>Motion</strong>. Hierin vind je je eigen <strong>to-do's</strong> en kan je de <strong>voortgang van projecten</strong> opvolgen. De <strong>projectmanager</strong> is je eerste aanspreekpunt bij <strong>vragen</strong> of <strong>obstakels</strong> en heeft ook de <strong>verantwoordelijkheid richting de klant</strong>.</p>
                                    <p class="mb-3">Voor elk <strong>project</strong> wordt vooraf een <strong>tijdsinschatting</strong> gemaakt. Het is belangrijk dat je <strong>taken</strong> binnen dit kader afrondt. Merk je dat dit niet haalbaar is, <strong>meld dit meteen</strong>. Soms ligt de oorzaak bij <strong>extra vragen van de klant</strong>, soms bij <strong>interne knelpunten</strong> – in beide gevallen zoeken we samen naar een <strong>oplossing</strong>.</p>
                                    <p class="mb-3"><strong>Deadlines</strong> zijn voor ons meer dan een datum: ze zijn een <strong>belofte aan de klant</strong>. We rekenen erop dat je <strong>problemen tijdig signaleert</strong>, zodat we deze belofte kunnen waarmaken.</p>
                                    <p class="mb-0"><strong>Bij afwezigheid van de projectmanager:</strong> Stuur aan het eind van de dag een korte <strong>status update via Teams</strong> voordat je naar huis vertrekt. Zo blijft iedereen op de hoogte van de voortgang.</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Klantcontact Section -->
            <div id="klantcontact-details" class="detail-section category-content" style="display: none;">
                <div class="detail-header">
                    <i class="fas fa-users me-2"></i>Contact & service
                </div>
                <div class="detail-content">
                    <div class="col-12">
                 
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-star me-2"></i>Professionele uitstraling
                                    </h6>
                                    <ul class="mb-3">
                                        <li><strong>Verzorgde uitstraling:</strong> Zorg voor een professionele en verzorgde uitstraling. Dit straalt vertrouwen uit.</li>
                                        <li><strong>Stiptheid:</strong> Stiptheid wordt gewaardeerd: kom op tijd bij afspraken.</li>
                                        <li><strong>Open communicatie:</strong> Communiceer steeds open en helder. Problemen mag je nooit laten aanslepen: bespreek ze meteen met je projectmanager.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-handshake me-2"></i>Opvolging & communicatie
                                    </h6>
                                    <ul class="mb-3">
                                        <li><strong>Feedback geven:</strong> Na een klantbezoek of call koppel je altijd terug naar de projectmanager of Kevin, zodat er een volledig beeld is van de situatie.</li>
                                        <li><strong>Afspraken naleven:</strong> Klanten verwachten dat we hen correct en volledig opvolgen. Dit betekent ook dat we intern afspraken goed naleven.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-ticket-alt me-2"></i>Service & ticketafhandeling
                                    </h6>
                                    <p class="mb-3">Een efficiënte ticketafhandeling is essentieel voor klanttevredenheid. We hanteren een duidelijke procedure die zorgt voor transparantie en snelle opvolging.</p>
                                    <ul class="mb-3">
                                        <li><strong>Ticket indienen:</strong> Klanten dienen servicevragen in via <a href="mailto:support@bmksolutions.be">support@bmksolutions.be</a>. Dit zorgt voor een gestructureerde opvolging.</li>
                                        <li><strong>Automatische registratie:</strong> Alle tickets worden automatisch aangemaakt in Odoo en toegewezen volgens prioriteit en expertise.</li>
                                        <li><strong>Dagelijkse opvolging:</strong> Tickets worden besproken in de dagelijkse stand-up meeting om voortgang te bewaken en knelpunten aan te pakken.</li>
                                        <li><strong>Snelle reactie:</strong> Reageer binnen 24 uur op klantvragen, ook als je nog geen definitief antwoord hebt. Bevestig ontvangst en geef een verwachte oplossingstijd.</li>
                                        <li><strong>Transparante communicatie:</strong> Ken je het antwoord niet? Wees eerlijk en zeg dat je dit intern gaat navragen. Zorg dat je dit ook daadwerkelijk doet en kom tijdig terug naar de klan.t</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Privacy Section -->
            <div id="privacy-details" class="detail-section category-content" style="display: none;">
                <div class="detail-header">
                    <i class="fas fa-shield-alt me-2"></i>Privacy & sociale media
                </div>
                <div class="detail-content">
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-lock me-2"></i>Privacy en veiligheid
                                    </h6>
                                    <ul class="mb-3">
                                        <li><strong>Sterke wachtwoorden:</strong> Gebruik altijd sterke wachtwoorden en bewaar deze in 1Password, onze centrale tool voor veilig wachtwoordbeheer.</li>
                                        <li><strong>Laptop beveiligen:</strong> Laat nooit gevoelige klantendata onbeheerd openstaan. Vergrendel je laptop als je even weggaat.</li>
                                        <li><strong>Data eigendom:</strong> Bestanden en data blijven altijd eigendom van BMK Solutions en worden niet gedeeld met derden zonder toestemming.</li>
                                        <li><strong>Inbreuken:</strong> Inbreuken op deze regels worden strikt opgevolgd.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-share-alt me-2"></i>Sociale media
                                    </h6>
                                    <p class="mb-3">BMK Solutions wil zichtbaar zijn op sociale media en moedigt medewerkers aan om ons bereik te versterken – maar wel op een professionele en afgestemde manier.</p>
                                    <p class="mb-3"><strong>We zijn actief op:</strong></p>
                                    <div class="d-flex gap-3 mb-3">
                                        <a href="https://www.linkedin.com/company/bmk-solutions-be" target="_blank" class="btn btn-outline-primary rounded-pill">
                                            <i class="fab fa-linkedin me-2"></i>LinkedIn
                                        </a>
                                        <a href="https://www.facebook.com/bmksolutions" target="_blank" class="btn btn-outline-primary rounded-pill">
                                            <i class="fab fa-facebook me-2"></i>Facebook
                                        </a>
                                        <a href="https://www.instagram.com/bmksolutions/" target="_blank" class="btn btn-outline-primary rounded-pill">
                                            <i class="fab fa-instagram me-2"></i>Instagram
                                        </a>
                                    </div>
                                    <ul class="mb-3">
                                        <li><strong>Officiële posts:</strong> Het delen van officiële BMK-posts wordt aangemoedigd.</li>
                                        <li><strong>Eigen posts:</strong> Eigen posts over klanten of projecten mag je enkel delen na overleg en toestemming van de klant.</li>
                                        <li><strong>Foto's:</strong> Foto's van kantoor of teamevents zijn welkom! Bezorg ze aan Kevin zodat we ze, mits akkoord, via onze kanalen kunnen delen.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tools Section -->
            <div id="tools-details" class="detail-section category-content" style="display: none;">
                <div class="detail-header">
                    <i class="fas fa-laptop-code me-2"></i>Software & systemen
                </div>
                <div class="detail-content">
                    <div class="col-12">
                   
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-envelope me-2"></i>Hoofdtools
                                    </h6>
                                    <ul class="mb-3">
                                        <li><strong>Microsoft Office 365 & Teams:</strong> voor communicatie, agenda en samenwerking.</li>
                                        <li><strong>Motion:</strong> het centrale systeem voor project- en taakplanning.</li>
                                        <li><strong>Odoo:</strong> voor opvolging van service tickets en klantenservice.</li>
                                        <li><strong>Git:</strong> versiebeheer en samenwerking bij programmeren. Zorg steeds voor duidelijke omschrijvingen van je updates.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-clock me-2"></i>Tijdsregistratie
                                    </h6>
                                    <p class="mb-0"><strong>Verplicht dagelijks bij te houden in AMPP.</strong> Hierin noteer je je gewerkte uren per project en een korte beschrijving van je activiteiten. Dit is essentieel voor facturatie, rapportering én interne opvolging.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kantoor Section -->
            <div id="kantoor-details" class="detail-section category-content" style="display: none;">
                <div class="detail-header">
                    <i class="fas fa-building me-2"></i>Kantoor & faciliteiten
                </div>
                <div class="detail-content">
                    <div class="col-12">
                      
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-key me-2"></i>Algemene kantoorregels
                                    </h6>
                                    <ul class="mb-3">
                                        <li><strong>Sleutelkastje:</strong> je hebt steeds toegang tot het kantoor via de sleutel in het sleutelkastje met je persoonlijke code. DEEL DEZE CODE NIET MET ANDEREN. Zorg dat de sleutel steeds terug in het kastje zit bij het verlaten van het kantoor.</li>
                                        <li><strong>Afsluiten:</strong> wie als laatste het kantoor verlaat, sluit ramen en deuren en dooft de lichten.</li>
                                        <li><strong>Clean desk policy:</strong> laat je werkplek steeds opgeruimd achter. Geen koffietassen, papiertjes of persoonlijke spullen. Zo kan iedereen flexibel gebruikmaken van de bureaus.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bg-light rounded p-4">
                                    <h6 class="fw-bold text-secondary mb-3">
                                        <i class="fas fa-coffee me-2"></i>Keuken & lunchruimte
                                    </h6>
                                    <p class="mb-3">Op kantoor lunchen we doorgaans samen. Je brengt je eigen lunch mee, maar je kan gebruik maken van de koelkast of de microgolfoven. Na gebruik laat je alles netjes achter.</p>
                                    <ul class="mb-3">
                                        <li><strong>Na de lunch</strong> ruim je je bord en tas meteen op.</li>
                                        <li><strong>De vaat</strong> gaat in de afwasmachine; bij een volle machine zet je deze aan.</li>
                                        <li><strong>Het leegmaken</strong> doen we samen, zodat het nooit bij één persoon blijft hangen.</li>
                                        <li><strong>Afval sorteren:</strong> we respecteren de voorziene afvalcontainers.</li>
                                        <li><strong>Sanitair:</strong> laat de wc's proper achter voor de volgende gebruiker.</li>
                                        <li><strong>Dranken:</strong> koffie, thee en water zijn altijd gratis beschikbaar. Signaleer tijdig als de voorraad bijna leeg is.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>

        <!-- Contact Section -->
        <div class="container">
            <div class="contact-info">
                <div class="mb-4 text-center">
                    <i class="fas fa-question-circle" style="font-size: 2.2rem; color: #0369a1; opacity: 0.8;"></i>
                    <h2 class="fw-bold text-primary mb-2 fs-4">Nog vragen?</h2>
                    <p class="mb-2 text-muted fs-6">
                        Aarzel niet om contact op te nemen. We helpen je graag verder.<br>
                    </p>
                </div>
                <div class="row justify-content-center g-3">
                    <div class="col-md-6 col-lg-5">
                        <div class="contact-card h-100 d-flex align-items-center p-3">
                            <img src="{{ asset('images/bmk/Kevin.jpg') }}" alt="Kevin Moons" class="profile-img me-4">
                            <div class="flex-grow-1 text-start">
                                <h4 class="fw-bold text-primary mb-1">Kevin Moons</h4>
                                <p class="text-muted mb-2 fw-medium">Zaakvoerder & Tech Lead</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="mailto:kevin@bmksolutions.be" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="fas fa-envelope me-2"></i>kevin@bmksolutions.be
                                    </a>
                                    <a href="tel:+32471234567" class="btn btn-outline-success btn-sm rounded-pill">
                                        <i class="fas fa-phone me-2"></i>+32 479 31 43 74
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="contact-card h-100 d-flex align-items-center p-3">
                            <img src="{{ asset('images/bmk/Celine.jpg') }}" alt="Céline Cuypers" class="profile-img me-4">
                            <div class="flex-grow-1 text-start">
                                <h4 class="fw-bold text-success mb-1">Céline Cuypers</h4>
                                <p class="text-muted mb-2 fw-medium">Organisatiecoach</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="mailto:celine@bmksolutions.be" class="btn btn-outline-success btn-sm rounded-pill">
                                        <i class="fas fa-envelope me-2"></i>celine@fit-design.be
                                    </a>
                                    <a href="tel:+32478987654" class="btn btn-outline-primary btn-sm rounded-pill">
                                        <i class="fas fa-phone me-2"></i>+32 477 96 60 79
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryButtons = document.querySelectorAll('.category-button');
        const detailSections = document.querySelectorAll('.detail-section');
        
        // Show first category by default (Algemeen)
        if (categoryButtons.length > 0) {
            categoryButtons[0].classList.add('active');
            const firstTarget = categoryButtons[0].getAttribute('data-target');
            document.getElementById(firstTarget + '-details').style.display = 'block';
        }
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                
                // Hide all detail sections
                detailSections.forEach(section => section.style.display = 'none');
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding detail section
                const targetSection = document.getElementById(target + '-details');
                if (targetSection) {
                    targetSection.style.display = 'block';
                    // targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' }); // Scrollen uitgeschakeld
                }
            });
        });
    });
    </script>
</x-layouts.ampp>
