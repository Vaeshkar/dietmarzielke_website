<script>
  import { fade } from 'svelte/transition';

  // IntersectionObserver for scroll animations
  $effect(() => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.05,
      rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    return () => observer.disconnect();
  });

  let progressPercent = $state(0);
  let isTransitionActive = $state(false);

  // Periodic Random Grid Block Morph Swap Effect (25-second Countdown)
  $effect(() => {
    const hitArea = document.getElementById("about-image-scene");
    const canvas = document.getElementById("about-morph-canvas");
    const baseImg = document.getElementById("about-base-img");
    if (!hitArea || !canvas || !baseImg) return;

    let context = canvas.getContext("2d");
    if (!context) return;

    const sourceCanvas = document.createElement("canvas");
    const sourceContext = sourceCanvas.getContext("2d");
    if (!sourceContext) return;

    // We keep track of which image is current base
    let isPortrait2Base = false;

    // Load both image references
    const img1 = new Image();
    img1.src = "/dietmar_zielke_portrait.webp";
    const img2 = new Image();
    img2.src = "/dietmar_zielke_portrait2.webp";

    let rect = baseImg.getBoundingClientRect();
    let canvasWidth = 0;
    let canvasHeight = 0;
    let animationFrameId = null;
    let lastSwapTime = Date.now();

    const boxSize = 55;
    let boxes = [];

    // Transition state variables
    let transitionFrame = 0;

    const createBoxes = () => {
      boxes = [];
      for (let x = 0; x <= canvasWidth + boxSize; x += boxSize) {
        for (let y = 0; y <= canvasHeight + boxSize; y += boxSize) {
          boxes.push({
            x,
            y,
            centerX: x + boxSize / 2,
            centerY: y + boxSize / 2,
            scale: 0,
            delay: Math.random() * 45 // Random start delay up to 45 frames (0.75s)
          });
        }
      }
    };

    const resizeCanvas = () => {
      rect = baseImg.getBoundingClientRect();
      if (rect.width === 0 || rect.height === 0) return;

      const dpr = Math.min(1.5, window.devicePixelRatio || 1);
      canvasWidth = Math.round(rect.width * dpr);
      canvasHeight = Math.round(rect.height * dpr);

      canvas.style.width = `${rect.width}px`;
      canvas.style.height = `${rect.height}px`;
      canvas.width = canvasWidth;
      canvas.height = canvasHeight;
      sourceCanvas.width = canvasWidth;
      sourceCanvas.height = canvasHeight;

      createBoxes();
      updateSourceCanvas();
    };

    const updateSourceCanvas = () => {
      if (!sourceContext || canvasWidth === 0) return;
      sourceContext.clearRect(0, 0, canvasWidth, canvasHeight);
      
      // Draw the upcoming overlay image on the offscreen canvas
      const activeOverlay = isPortrait2Base ? img1 : img2;
      if (activeOverlay.complete) {
        drawCoverImage(sourceContext, activeOverlay);
      }
    };

    const drawCoverImage = (ctx, img) => {
      const imageRatio = img.width / img.height;
      const canvasRatio = canvasWidth / canvasHeight;
      let sourceWidth = img.width;
      let sourceHeight = img.height;
      let sourceX = 0;
      let sourceY = 0;

      if (imageRatio > canvasRatio) {
        sourceWidth = img.height * canvasRatio;
        sourceX = (img.width - sourceWidth) / 2;
      } else {
        sourceHeight = img.width / canvasRatio;
        sourceY = (img.height - sourceHeight) / 2;
      }

      ctx.drawImage(img, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, canvasWidth, canvasHeight);
    };

    const drawTile = (box) => {
      if (box.scale < 0.001) return;

      const sourceSize = boxSize;
      const sourceX = box.x;
      const sourceY = box.y;

      // Draw the block scaling up from the center of its grid cell
      context.drawImage(
        sourceCanvas,
        sourceX,
        sourceY,
        sourceSize,
        sourceSize,
        box.x + (boxSize - boxSize * box.scale) / 2,
        box.y + (boxSize - boxSize * box.scale) / 2,
        boxSize * box.scale,
        boxSize * box.scale
      );
    };

    const drawDot = (box) => {
      if (box.scale < 0.001) return;
      context.beginPath();
      context.arc(box.centerX, box.centerY, 3 * box.scale, 0, Math.PI * 2);
      context.fill();
    };

    const render = () => {
      if (canvasWidth === 0 || canvasHeight === 0) {
        animationFrameId = requestAnimationFrame(render);
        return;
      }

      const now = Date.now();
      const elapsed = now - lastSwapTime;

      // Update progress bar countdown (from 0 to 100 over 25 seconds)
      if (!isTransitionActive) {
        progressPercent = Math.min(100, (elapsed / 25000) * 100);
        if (elapsed >= 25000) {
          triggerTransition();
        }
      }

      context.clearRect(0, 0, canvasWidth, canvasHeight);

      if (isTransitionActive) {
        transitionFrame++;
        let allCompleted = true;

        boxes.forEach((box) => {
          if (transitionFrame > box.delay) {
            box.scale += 0.04; // Animation speed of tile scale-up
            if (box.scale > 1) box.scale = 1;
          }
          if (box.scale < 1) {
            allCompleted = false;
          }
        });

        // 1. Draw tiles
        boxes.forEach(drawTile);

        // 2. Draw subtle technical dots
        context.fillStyle = "rgba(255, 255, 255, 0.45)";
        boxes.forEach(drawDot);

        // Swap images behind the canvas when all blocks are fully covering the canvas (100% opaque)
        if (allCompleted) {
          isPortrait2Base = !isPortrait2Base;
          
          // Swap base image source (no flicker because canvas is fully covering it with identical layout)
          baseImg.src = isPortrait2Base ? "/dietmar_zielke_portrait2.webp" : "/dietmar_zielke_portrait.webp";
          
          // Reset transition states
          isTransitionActive = false;
          lastSwapTime = Date.now();
          progressPercent = 0;
          
          // Reset box scales to transparent for the next cycle
          boxes.forEach(box => {
            box.scale = 0;
            box.delay = Math.random() * 45;
          });
          
          // Load the new target overlay
          updateSourceCanvas();
          context.clearRect(0, 0, canvasWidth, canvasHeight);
        }
      }

      animationFrameId = requestAnimationFrame(render);
    };

    const triggerTransition = () => {
      if (isTransitionActive) return;
      isTransitionActive = true;
      transitionFrame = 0;
      progressPercent = 100;
      updateSourceCanvas();
    };

    // Initial setup listeners
    if (baseImg.complete) {
      resizeCanvas();
    } else {
      baseImg.onload = resizeCanvas;
    }
    img1.onload = updateSourceCanvas;
    img2.onload = updateSourceCanvas;
    window.addEventListener("resize", resizeCanvas);

    animationFrameId = requestAnimationFrame(render);

    // Self-healing check in case layout height is computed delayed
    const layoutChecker = setInterval(() => {
      if (canvasWidth === 0) resizeCanvas();
    }, 500);

    return () => {
      clearInterval(layoutChecker);
      if (animationFrameId) cancelAnimationFrame(animationFrameId);
      window.removeEventListener("resize", resizeCanvas);
    };
  });

  let activeSection = $state('start');

  // Scroll Spy for Navigation Active State
  $effect(() => {
    const sections = ['start', 'leistungen', 'fuer-wen', 'ueber-mich', 'so-arbeite-ich', 'faq'];
    const observerOptions = {
      root: null,
      rootMargin: '-30% 0px -60% 0px',
      threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          activeSection = entry.target.id;
        }
      });
    }, observerOptions);

    sections.forEach(id => {
      const el = document.getElementById(id);
      if (el) observer.observe(el);
    });

    return () => observer.disconnect();
  });

  function directionAwareHover(node) {
    const handleMouseEnter = (e) => {
      const rect = node.getBoundingClientRect();
      const x = e.clientX - rect.left;
      node.style.setProperty('--origin', x < rect.width / 2 ? 'left' : 'right');
    };

    const handleMouseLeave = (e) => {
      const rect = node.getBoundingClientRect();
      const x = e.clientX - rect.left;
      node.style.setProperty('--origin', x < rect.width / 2 ? 'left' : 'right');
    };

    node.addEventListener('mouseenter', handleMouseEnter);
    node.addEventListener('mouseleave', handleMouseLeave);

    return {
      destroy() {
        node.removeEventListener('mouseenter', handleMouseEnter);
        node.removeEventListener('mouseleave', handleMouseLeave);
      }
    };
  }

  let activeFaq = $state(null);
  let isMobileMenuOpen = $state(false);
  let openModal = $state(null); // 'impressum' | 'datenschutz' | null

  // Form Fields
  let name = $state('');
  let email = $state('');
  let message = $state('');
  let consent = $state(false);
  let status = $state(null); // 'success' | 'error' | 'sending'
  let feedbackMessage = $state('');

  function toggleFaq(index) {
    if (activeFaq === index) {
      activeFaq = null;
    } else {
      activeFaq = index;
    }
  }

  function toggleMobileMenu() {
    isMobileMenuOpen = !isMobileMenuOpen;
  }

  function handleNavClick(sectionId) {
    isMobileMenuOpen = false;
    const el = document.getElementById(sectionId);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' });
    }
  }

  async function handleSubmit(event) {
    event.preventDefault();
    if (!consent) {
      status = 'error';
      feedbackMessage = 'Bitte akzeptieren Sie die Datenschutzerklärung.';
      return;
    }

    status = 'sending';
    feedbackMessage = 'Nachricht wird gesendet...';

    try {
      const response = await fetch('send_mail.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          name: name,
          email: email,
          message: message,
          consent: consent ? 'yes' : 'no'
        })
      });

      const text = await response.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error("Invalid JSON response:", text);
        data = { success: false, message: 'Serverfehler bei der E-Mail-Übertragung.' };
      }

      if (data.success) {
        status = 'success';
        feedbackMessage = data.message;
        name = '';
        email = '';
        message = '';
        consent = false;
      } else {
        status = 'error';
        feedbackMessage = data.message || 'Fehler beim Senden der Nachricht.';
      }
    } catch (e) {
      status = 'error';
      feedbackMessage = 'Netzwerkfehler. Bitte versuchen Sie es später noch einmal.';
    }
  }
</script>

<!-- Header & Nav -->
<header class="header">
  <div class="container header-container">
    <a href="#start" class="logo" onclick={(e) => { e.preventDefault(); handleNavClick('start'); }}>
      <img src="/dz_logo_d.svg" alt="Dietmar Zielke Rechtliche Betreuungen" class="logo-img" />
    </a>

    <!-- Hamburger menu toggle -->
    <button 
      class="menu-toggle" 
      aria-label="Menü öffnen" 
      aria-expanded={isMobileMenuOpen}
      onclick={toggleMobileMenu}
    >
      <span style={isMobileMenuOpen ? 'transform: rotate(45deg) translate(6px, 6px);' : ''}></span>
      <span style={isMobileMenuOpen ? 'opacity: 0;' : ''}></span>
      <span style={isMobileMenuOpen ? 'transform: rotate(-45deg) translate(6px, -6px);' : ''}></span>
    </button>

    <nav class="nav {isMobileMenuOpen ? 'open' : ''}">
      <ul class="nav-list">
        <li><a href="#start" use:directionAwareHover class="nav-link" class:active={activeSection === 'start'} onclick={(e) => { e.preventDefault(); handleNavClick('start'); }}>Start</a></li>
        <li><a href="#leistungen" use:directionAwareHover class="nav-link" class:active={activeSection === 'leistungen'} onclick={(e) => { e.preventDefault(); handleNavClick('leistungen'); }}>Leistungen</a></li>
        <li><a href="#fuer-wen" use:directionAwareHover class="nav-link" class:active={activeSection === 'fuer-wen'} onclick={(e) => { e.preventDefault(); handleNavClick('fuer-wen'); }}>Für wen?</a></li>
        <li><a href="#ueber-mich" use:directionAwareHover class="nav-link" class:active={activeSection === 'ueber-mich'} onclick={(e) => { e.preventDefault(); handleNavClick('ueber-mich'); }}>Über mich</a></li>
        <li><a href="#so-arbeite-ich" use:directionAwareHover class="nav-link" class:active={activeSection === 'so-arbeite-ich'} onclick={(e) => { e.preventDefault(); handleNavClick('so-arbeite-ich'); }}>So arbeite ich</a></li>
        <li><a href="#faq" use:directionAwareHover class="nav-link" class:active={activeSection === 'faq'} onclick={(e) => { e.preventDefault(); handleNavClick('faq'); }}>Über rechtliche Betreuung</a></li>
      </ul>
    </nav>
  </div>
</header>

<main>
  <!-- Hero Section -->
  <section id="start" class="hero-section section-spacing">
    <div class="container hero-grid">
      <div class="hero-content reveal">
        <h1>Rechtliche Betreuung mit Klarheit, Ruhe und Verlässlichkeit.</h1>
        <p>Ich unterstütze und vertrete Menschen in rechtlichen und organisatorischen Angelegenheiten – persönlich, sorgfältig und auf Augenhöhe.</p>
        <div class="hero-actions">
          <a href="#kontakt" class="btn btn-primary" onclick={(e) => { e.preventDefault(); handleNavClick('kontakt'); }}>
            Kontakt aufnehmen 
            <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>
          <a href="#leistungen" class="btn btn-outline" onclick={(e) => { e.preventDefault(); handleNavClick('leistungen'); }}>
            Leistungen
            <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>
        </div>
      </div>
      
      <!-- Premium dynamic hero visual matching design concept -->
      <div class="hero-illustration reveal reveal-delay-1">
        <svg viewBox="0 0 520 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="hero-svg-illustration">
          <!-- Definitions for patterns -->
          <defs>
            <pattern id="dot-pattern" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
              <circle cx="2" cy="2" r="1.5" fill="#cbd5e1" />
            </pattern>
          </defs>

          <!-- Top Right Dot Grid -->
          <rect x="360" y="50" width="80" height="80" fill="url(#dot-pattern)" class="hero-dots-top" />
          
          <!-- Bottom Left Dot Grid -->
          <rect x="60" y="270" width="80" height="80" fill="url(#dot-pattern)" class="hero-dots-bottom" />

          <!-- Background Gray Chat Bubble shape group -->
          <g class="hero-gray-bubble">
            <path d="M 80,140 C 80,100 120,80 180,80 C 240,80 280,100 280,140 C 280,180 250,220 190,220 H 130 L 90,250 V 220 C 80,210 80,180 80,140 Z" fill="#f1f5f9" />
            <!-- Small Orange Dot inside Gray Bubble -->
            <circle cx="150" cy="140" r="5" fill="#ff4e18" />
          </g>

          <!-- Balloon / Speech Bubble Outline in Orange -->
          <path d="M 230,120 A 30,40 0 1 1 290,120 C 290,145 280,165 270,175 L 265,185 L 260,175 C 240,165 230,145 230,120 Z" stroke="#ff4e18" stroke-width="2" fill="none" class="hero-outline-bubble" />

          <!-- Organic Pebbles Shape Group -->
          <g class="hero-organic-group">
            <path d="M 260,250 C 260,180 320,150 390,150 C 460,150 480,200 480,260 C 480,320 440,350 360,350 C 280,350 260,320 260,250 Z" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" class="hero-organic-shape" />
            <!-- Rounded Arch Outline inside Organic Shape -->
            <path d="M 360,290 V 220 A 25,25 0 0 1 410,220 V 290" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" fill="none" />
          </g>

          <!-- Solid Orange Circle (Focal Point) -->
          <circle cx="310" cy="230" r="42" fill="#ff4e18" class="hero-orange-circle" />
        </svg>
      </div>
    </div>
  </section>

  <!-- Leistungen Section -->
  <section id="leistungen" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title">Leistungen</h2>
      </div>

      <div class="leistungen-grid">
        <!-- Card 1 -->
        <div class="service-card reveal">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3>Gesundheit & Behörden</h3>
          <p>Unterstützung bei medizinischen Entscheidungen und im Umgang mit Ämtern und Krankenkassen.</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>

        <!-- Card 2 -->
        <div class="service-card reveal reveal-delay-1">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
          </div>
          <h3>Vermögenssorge</h3>
          <p>Verantwortungsvoller Umgang mit Einkommen, Vermögen und Zahlungsverkehr.</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>

        <!-- Card 3 -->
        <div class="service-card reveal reveal-delay-2">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <h3>Wohn- und Alltagsangelegenheiten</h3>
          <p>Organisation des Wohnumfelds und Unterstützung im täglichen Leben.</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>

        <!-- Card 4 -->
        <div class="service-card reveal reveal-delay-3">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
          <h3>Vertretung & Kommunikation</h3>
          <p>Vertretung gegenüber Behörden, Institutionen und anderen Beteiligten.</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>
      </div>
    </div>
  </section>

  <!-- Spacer Section 1 (Abstract Artwork: Listening & Processing) -->
  <section class="image-spacer-section">
    <div class="container-wide">
      <svg viewBox="0 0 1440 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="spacer-svg">
        <!-- Definitions for patterns -->
        <defs>
          <pattern id="dot-pattern-s1" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="1.5" fill="#cbd5e1" />
          </pattern>
        </defs>

        <!-- Top Right Dot Grid -->
        <rect x="1080" y="80" width="80" height="80" fill="url(#dot-pattern-s1)" class="hero-dots-top" />
        
        <!-- Bottom Left Dot Grid -->
        <rect x="240" y="220" width="80" height="80" fill="url(#dot-pattern-s1)" class="hero-dots-bottom" />

        <!-- Winding horizontal lines spreading out across the wide viewport -->
        <!-- Left winding line heading to sound waves -->
        <path d="M 150,200 H 320 C 350,200 370,150 400,150" stroke="#ff4e18" stroke-width="1.5" stroke-linecap="round" fill="none" class="spacer-winding-line" />
        <circle cx="150" cy="200" r="4" fill="#ff4e18" />

        <!-- Right winding line continuing from orange center -->
        <path d="M 752,200 H 830 C 860,200 870,250 900,250 H 1050 C 1100,250 1120,200 1150,200 H 1290" stroke="#ff4e18" stroke-width="1.5" stroke-linecap="round" fill="none" class="spacer-winding-line" />
        <circle cx="1290" cy="200" r="4" fill="#ff4e18" />

        <!-- Concentric Sound Waves (Listening motif) -->
        <g class="hero-outline-bubble">
          <path d="M 400,120 A 80,80 0 0 1 400,280" stroke="#ff4e18" stroke-width="2" stroke-linecap="round" fill="none" />
          <path d="M 370,90 A 110,110 0 0 1 370,310" stroke="#ff4e18" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.6" />
          <path d="M 340,60 A 140,140 0 0 1 340,340" stroke="#ff4e18" stroke-width="1" stroke-linecap="round" fill="none" opacity="0.3" />
        </g>

        <!-- Receiver Shell Shape (Abstract Ear/Pebble) -->
        <g class="hero-organic-group">
          <path d="M 520,100 C 580,100 620,140 620,200 C 620,260 580,300 520,300 C 470,300 460,250 460,200 C 460,150 470,100 520,100 Z" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" />
        </g>

        <!-- Background Gray Processing block shape -->
        <g class="hero-gray-bubble">
          <path d="M 850,120 C 850,80 890,60 950,60 C 1010,60 1050,80 1050,120 V 240 C 1050,280 1010,300 950,300 C 890,300 850,280 850,240 Z" fill="#f1f5f9" />
          <!-- Accent dot inside processing block -->
          <circle cx="950" cy="180" r="5" fill="#ff4e18" />
        </g>

        <!-- Solid Orange Circle (Listening Focal Point) -->
        <circle cx="720" cy="200" r="32" fill="#ff4e18" class="hero-orange-circle" />
      </svg>
    </div>
  </section>

  <!-- Für Wen Section -->
  <section id="fuer-wen" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title">Für wen?</h2>
        <p class="section-subtitle">Meine Betreuung orientiert sich an den Bedürfnissen aller Beteiligten.</p>
      </div>

      <div class="for-whom-grid">
        <!-- Card 1 -->
        <div class="whom-card reveal">
          <div class="whom-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="whom-info">
            <h3>Betroffene</h3>
            <p>Ich begleite Sie verlässlich und respektvoll in allen rechtlichen und organisatorischen Fragen.</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="whom-card reveal reveal-delay-1">
          <div class="whom-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div class="whom-info">
            <h3>Angehörige</h3>
            <p>Ich entlaste, informiere und arbeite transparent mit Ihnen zusammen.</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="whom-card reveal reveal-delay-2">
          <div class="whom-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div class="whom-info">
            <h3>Gerichte / Institutionen</h3>
            <p>Ich übernehme Betreuungen mit Sorgfalt, Verlässlichkeit und klarer Kommunikation.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Über mich Section -->
  <section id="ueber-mich" class="section-spacing">
    <div class="container about-grid">
      <div class="about-image-wrapper reveal" id="about-image-scene">
        <img id="about-base-img" src="/dietmar_zielke_portrait.webp" alt="Dietmar Zielke Portrait" class="about-img" />
        <canvas class="about-canvas" id="about-morph-canvas" aria-hidden="true"></canvas>
        <div class="about-progress-container" class:transitioning={isTransitionActive}>
          <div class="about-progress-bar" style="width: {progressPercent}%"></div>
        </div>
      </div>
      <div class="about-content reveal reveal-delay-1">
        <h2 class="section-title">Über mich</h2>
        <h3 class="about-subtitle">Dietmar Zielke</h3>
        <p class="about-tagline">Rechtlicher Betreuer in Hamburg & der Metropolregion</p>
        
        <p>Als staatlich anerkannter rechtlicher Betreuer unterstütze ich Menschen dabei, ihr selbstbestimmtes Leben bestmöglich fortzuführen, wenn organisatorische oder rechtliche Hürden zu groß werden. Mit Empathie, Klarheit und Fachkompetenz stehe ich Ihnen und Ihren Angehörigen zur Seite.</p>
        
        <p>Mein Ansatz beruht auf enger, transparenter Kommunikation und absoluter Zuverlässigkeit. Ich vertrete Ihre Interessen gegenüber Behörden, koordiniere gesundheitliche Belange und verwalte Vermögenswerte mit größter Sorgfalt – stets auf Augenhöhe und mit Respekt vor Ihrer Lebensleistung.</p>
        
        <ul class="about-bullets">
          <li>
            <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            Persönliche & verlässliche Begleitung im Alltag
          </li>
          <li>
            <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            Sorgfältige Vertretung in allen gerichtlichen Aufgabenbereichen
          </li>
          <li>
            <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            Enge & vertrauensvolle Abstimmung mit Ärzten, Pflegediensten & Behörden
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- So arbeite ich Section -->
  <section id="so-arbeite-ich" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title">So arbeite ich</h2>
      </div>

      <div class="timeline-row">
        <!-- Step 1 -->
        <div class="timeline-step reveal">
          <div class="timeline-step-header">
            <span class="timeline-number">01</span>
            <h3>Kennenlernen</h3>
          </div>
          <p>Wir besprechen die Situation und klären alle wichtigen Fragen. Vertraulich und auf Augenhöhe.</p>
        </div>

        <!-- Separator Chevron -->
        <svg class="timeline-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>

        <!-- Step 2 -->
        <div class="timeline-step reveal reveal-delay-1">
          <div class="timeline-step-header">
            <span class="timeline-number">02</span>
            <h3>Planen & Handeln</h3>
          </div>
          <p>Ich übernehme die Aufgaben, koordiniere Notwendiges und halte Sie regelmäßig informiert.</p>
        </div>

        <!-- Separator Chevron -->
        <svg class="timeline-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>

        <!-- Step 3 -->
        <div class="timeline-step reveal reveal-delay-2">
          <div class="timeline-step-header">
            <span class="timeline-number">03</span>
            <h3>Begleiten & Entlasten</h3>
          </div>
          <p>Ich bleibe ansprechbar und sorge für Kontinuität, Ordnung und Verlässlichkeit.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Spacer Section 2 (Abstract Artwork: Clarity & Trust) -->
  <section class="image-spacer-section">
    <div class="container-wide">
      <svg viewBox="0 0 1440 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="spacer-svg">
        <!-- Definitions for patterns -->
        <defs>
          <pattern id="dot-pattern-s2" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse">
            <circle cx="2" cy="2" r="1.5" fill="#cbd5e1" />
          </pattern>
        </defs>

        <!-- Top Left Dot Grid -->
        <rect x="240" y="60" width="60" height="80" fill="url(#dot-pattern-s2)" class="hero-dots-top" />

        <!-- Bottom Right Dot Grid -->
        <rect x="980" y="180" width="60" height="80" fill="url(#dot-pattern-s2)" class="hero-dots-bottom" />

        <!-- Background Archway Shadow on Left -->
        <path d="M 320,320 V 140 A 50,50 0 0 1 420,140 V 320 Z" fill="#f1f5f9" class="spacer-archway" />

        <!-- Small Orange Outline Squares (Floating/Rotating) -->
        <rect x="350" y="100" width="24" height="24" rx="6" stroke="#ff4e18" stroke-width="2" fill="none" class="spacer-square" />
        <rect x="680" y="90" width="28" height="28" rx="6" stroke="#ff4e18" stroke-width="2" fill="none" class="spacer-square" />
        <rect x="520" y="260" width="24" height="24" rx="6" stroke="#ff4e18" stroke-width="2" fill="none" class="spacer-square" />

        <!-- Winding line connecting left circle to eye badge -->
        <path d="M 150,200 H 280 C 320,200 320,280 370,280 C 420,280 420,180 470,180 H 560 C 610,180 615,240 665,240 H 780" stroke="#ff4e18" stroke-width="2" stroke-linecap="round" fill="none" class="spacer-winding-line" />
        <circle cx="150" cy="200" r="4" fill="#ff4e18" />

        <!-- Solid Orange Circle sitting on the winding line -->
        <circle cx="500" cy="230" r="18" fill="#ff4e18" class="spacer-orange-circle-large" />

        <!-- Eye Badge in a Clean White Circle with grey outline -->
        <g class="spacer-eye-badge">
          <circle cx="850" cy="200" r="45" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" />
          <!-- Eye Graphic inside -->
          <path d="M 820,200 C 830,180 870,180 880,200 C 870,220 830,220 820,200 Z" stroke="#ff4e18" stroke-width="2" fill="none" />
          <circle cx="850" cy="200" r="12" stroke="#ff4e18" stroke-width="1.5" fill="none" />
          <circle cx="850" cy="200" r="5" fill="#ff4e18" />
        </g>

        <!-- Winding line continuing from eye badge to the right -->
        <path d="M 920,220 C 950,250 990,250 1020,290 H 1290" stroke="#ff4e18" stroke-width="2" stroke-linecap="round" fill="none" class="spacer-winding-line" />
        <circle cx="1290" cy="290" r="4" fill="#ff4e18" />
      </svg>
    </div>
  </section>

  <!-- FAQ Section (Was ist rechtliche Betreuung?) -->
  <section id="faq" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title">Was ist rechtliche Betreuung?</h2>
      </div>

      <div class="faq-grid">
        <div class="faq-explanation reveal">
          <p>Die rechtliche Betreuung ist eine gesetzliche Unterstützung für Menschen, die ihre Angelegenheiten ganz oder teilweise nicht mehr selbst regeln können. Als rechtlicher Betreuer vertrete ich die Interessen der betreuten Person und entscheide nur in den Bereichen, für die ich bestellt wurde. Ziel ist es, Selbstbestimmung zu erhalten und Überforderung zu vermeiden.</p>
        </div>

        <div class="faq-list reveal reveal-delay-1">
          <!-- FAQ 1 -->
          <div class="faq-item {activeFaq === 0 ? 'active' : ''}">
            <button class="faq-trigger" onclick={() => toggleFaq(0)} aria-expanded={activeFaq === 0}>
              Wer bestellt einen rechtlichen Betreuer?
              <svg class="faq-trigger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div class="faq-content" style={activeFaq === 0 ? 'max-height: 200px;' : 'max-height: 0;'}>
              <p>Ein rechtlicher Betreuer wird vom zuständigen Betreuungsgericht bestellt. Dies geschieht entweder auf eigenen Antrag der betroffenen Person oder von Amts wegen (z. B. auf Anregung von Angehörigen, Krankenhäusern oder Behörden), wenn jemand aufgrund einer psychischen Krankheit oder einer körperlichen, geistigen oder seelischen Behinderung seine Angelegenheiten ganz oder teilweise nicht regeln kann.</p>
            </div>
          </div>

          <!-- FAQ 2 -->
          <div class="faq-item {activeFaq === 1 ? 'active' : ''}">
            <button class="faq-trigger" onclick={() => toggleFaq(1)} aria-expanded={activeFaq === 1}>
              Welche Aufgaben hat ein rechtlicher Betreuer?
              <svg class="faq-trigger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div class="faq-content" style={activeFaq === 1 ? 'max-height: 200px;' : 'max-height: 0;'}>
              <p>Die Aufgabenbereiche werden vom Gericht individuell festgelegt (z.B. Gesundheitssorge, Vermögenssorge, Vertretung gegenüber Behörden, Wohnungsangelegenheiten). Der Betreuer unterstützt die betreute Person dabei, ihr Leben nach den eigenen Wünschen zu gestalten, und vertritt sie im erforderlichen Umfang rechtlich.</p>
            </div>
          </div>

          <!-- FAQ 3 -->
          <div class="faq-item {activeFaq === 2 ? 'active' : ''}">
            <button class="faq-trigger" onclick={() => toggleFaq(2)} aria-expanded={activeFaq === 2}>
              Was kostet eine rechtliche Betreuung?
              <svg class="faq-trigger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div class="faq-content" style={activeFaq === 2 ? 'max-height: 200px;' : 'max-height: 0;'}>
              <p>Die Vergütung und Auslagen des Betreuers werden gesetzlich geregelt. Wenn die betreute Person über ausreichendes Vermögen verfügt (oberhalb des Schonvermögens von derzeit 10.000 €), zahlt sie die Kosten selbst. Andernfalls übernimmt die Staatskasse die Vergütung.</p>
            </div>
          </div>

          <!-- FAQ 4 -->
          <div class="faq-item {activeFaq === 3 ? 'active' : ''}">
            <button class="faq-trigger" onclick={() => toggleFaq(3)} aria-expanded={activeFaq === 3}>
              Kann ich Wünsche oder Vollmachten festlegen?
              <svg class="faq-trigger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div class="faq-content" style={activeFaq === 3 ? 'max-height: 200px;' : 'max-height: 0;'}>
              <p>Ja. Mit einer Vorsorgevollmacht oder einer Betreuungsverfügung können Sie im Vorfeld selbst bestimmen, wer im Ernstfall Ihre Interessen vertreten soll und welche Wünsche bei der Betreuung berücksichtigt werden müssen. Dies stärkt Ihre Selbstbestimmung maßgeblich.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Kontakt Section -->
  <section id="kontakt" class="section-spacing">
    <div class="container kontakt-grid">
      <!-- Column 1 -->
      <div class="contact-column-left reveal">
        <h2 class="section-title">Kontakt</h2>
        <div class="contact-detail-list">
          <!-- Phone -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <div class="contact-detail-text">
              <p><a href="tel:015252585620" class="hover:underline">0152 525 85 620</a></p>
            </div>
          </div>

          <!-- Email -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div class="contact-detail-text">
              <p><a href="mailto:zielke@betreuungen-zielke.de" class="hover:underline">zielke@betreuungen-zielke.de</a></p>
            </div>
          </div>

          <!-- Web -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
            </svg>
            <div class="contact-detail-text">
              <p><a href="https://www.betreuungen-zielke.de" target="_blank" rel="noopener noreferrer" class="hover:underline">www.betreuungen-zielke.de</a></p>
            </div>
          </div>

          <!-- Postbox -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <div class="contact-detail-text">
              <p>Postfach in Hamburg</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Column 2 -->
      <div class="contact-column-middle reveal reveal-delay-1">
        <div class="contact-column-content">
          <h3 class="contact-slogan-title">Ich bin gerne für Sie da.</h3>
          <p class="contact-slogan-desc">Schreiben Sie mir oder rufen Sie an. Ich melde mich zeitnah bei Ihnen.</p>
          <div class="contact-business-hours">
            <h4>Bürozeiten:</h4>
            <p>Mo – Fr &nbsp;&nbsp;9:00 – 17:00 Uhr</p>
          </div>
        </div>
      </div>

      <!-- Column 3 -->
      <div class="contact-column-right reveal reveal-delay-2">
        <div class="contact-column-content">
          <!-- Contact Form Component -->
          <form class="contact-form" onsubmit={handleSubmit}>
            {#if status}
              <div class="form-feedback {status === 'success' ? 'form-feedback-success' : 'form-feedback-error'}" role="alert">
                {feedbackMessage}
              </div>
            {/if}

            <div class="form-group-row">
              <div class="form-group">
                <label for="form-name" class="sr-only">Ihr Name *</label>
                <input 
                  id="form-name"
                  type="text" 
                  class="form-input" 
                  required 
                  placeholder="Ihr Name" 
                  bind:value={name}
                  disabled={status === 'sending'}
                />
              </div>
              <div class="form-group">
                <label for="form-email" class="sr-only">Ihre E-Mail *</label>
                <input 
                  id="form-email"
                  type="email" 
                  class="form-input" 
                  required 
                  placeholder="Ihre E-Mail" 
                  bind:value={email}
                  disabled={status === 'sending'}
                />
              </div>
            </div>

            <div class="form-group">
              <label for="form-message" class="sr-only">Nachricht *</label>
              <textarea 
                id="form-message"
                rows="5" 
                class="form-input form-textarea" 
                required 
                placeholder="Nachricht" 
                bind:value={message}
                disabled={status === 'sending'}
              ></textarea>
            </div>

            <div class="form-footer">
              <div class="form-checkbox-group">
                <input 
                  id="form-dsgvo"
                  type="checkbox" 
                  class="form-checkbox" 
                  required 
                  bind:checked={consent}
                  disabled={status === 'sending'}
                />
                <label for="form-dsgvo" class="form-checkbox-label">
                  Ich habe die <a href="#datenschutz" onclick={(e) => { e.preventDefault(); openModal = 'datenschutz'; }}>Datenschutzerklärung</a> gelesen und akzeptiere sie. *
                </label>
              </div>

              <button type="submit" class="btn btn-primary form-submit-btn" disabled={status === 'sending'}>
                {status === 'sending' ? 'Wird gesendet...' : 'Nachricht senden'}
                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Footer -->
<footer class="footer">
  <div class="container footer-container">
    <div class="logo">
      <img src="/dz_logo_d.svg" alt="Dietmar Zielke Rechtliche Betreuungen" class="logo-img" />
    </div>
    
    <p class="footer-tagline">Verlässlich. Sorgfältig. Menschlich.</p>
    
    <ul class="footer-links">
      <li class="footer-link-item">
        <a href="#impressum" onclick={(e) => { e.preventDefault(); openModal = 'impressum'; }}>Impressum</a>
      </li>
      <li class="footer-link-item">
        <a href="#datenschutz" onclick={(e) => { e.preventDefault(); openModal = 'datenschutz'; }}>Datenschutz</a>
      </li>
    </ul>
  </div>
</footer>

<!-- Modals for Legal Information (GDPR/DSGVO & Impressum) -->
{#if openModal === 'impressum'}
  <div class="modal-overlay" onclick={() => openModal = null} onkeydown={(e) => { if (e.key === 'Escape' || e.key === 'Enter') openModal = null; }} role="button" tabindex="-1" aria-label="Schließen" transition:fade={{ duration: 150 }}>
    <div class="modal-container" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.stopPropagation()} role="dialog" aria-modal="true" tabindex="-1">
      <div class="modal-header">
        <h2>Impressum</h2>
        <button class="modal-close-btn" onclick={() => openModal = null} aria-label="Schließen">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <h3>Angaben gemäß § 5 TMG</h3>
        <p>
          Dietmar Zielke<br>
          Rechtliche Betreuungen<br>
          Postfach 10 06 06<br>
          20004 Hamburg
        </p>

        <h3>Kontakt</h3>
        <p>
          Telefon: 0152 525 85 620<br>
          Fax: 040-35 671 480<br>
          E-Mail: zielke@betreuungen-zielke.de
        </p>

        <h3>Berufsbezeichnung und Aufsichtsbehörde</h3>
        <p>
          Berufsbezeichnung: Rechtlicher Betreuer (Berufsbetreuer)<br>
          Zuständige Aufsichtsbehörde (Betreuungsbehörde): Hamburg / zuständiges Amtsgericht Hamburg.
        </p>

        <h3>Steuerliche Angaben</h3>
        <p>
          Steuernummer: 46/277/04580<br>
          Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz: 84 531 067 933
        </p>

        <h3>Bankverbindung</h3>
        <p>
          IBAN: DE21 1101 0101 5806 8968 70<br>
          BIC: SOBKDEB2XXX
        </p>

        <h3>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h3>
        <p>
          Dietmar Zielke<br>
          Postfach 10 06 06<br>
          20004 Hamburg
        </p>

        <h3>Streitschlichtung</h3>
        <p>
          Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit: <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer" style="color: var(--primary);">https://ec.europa.eu/consumers/odr/</a>.<br>
          Unsere E-Mail-Adresse finden Sie oben im Impressum.
        </p>
        <p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>
      </div>
    </div>
  </div>
{/if}

{#if openModal === 'datenschutz'}
  <div class="modal-overlay" onclick={() => openModal = null} onkeydown={(e) => { if (e.key === 'Escape' || e.key === 'Enter') openModal = null; }} role="button" tabindex="-1" aria-label="Schließen" transition:fade={{ duration: 150 }}>
    <div class="modal-container" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.stopPropagation()} role="dialog" aria-modal="true" tabindex="-1">
      <div class="modal-header">
        <h2>Datenschutzerklärung</h2>
        <button class="modal-close-btn" onclick={() => openModal = null} aria-label="Schließen">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="modal-body">
        <h3>1. Datenschutz auf einen Blick</h3>
        <p><strong>Allgemeine Hinweise</strong></p>
        <p>Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie persönlich identifiziert werden können. Ausführliche Informationen zum Thema Datenschutz entnehmen Sie unserer unter diesem Text aufgeführten Datenschutzerklärung.</p>

        <p><strong>Datenerfassung auf dieser Website</strong></p>
        <p><em>Wer ist verantwortlich für die Datenerfassung auf dieser Website?</em></p>
        <p>Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten können Sie dem Impressum dieser Website entnehmen.</p>
        
        <p><em>Wie erfassen wir Ihre Daten?</em></p>
        <p>Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese mitteilen. Hierbei kann es sich z. B. um Daten handeln, die Sie in ein Kontaktformular eingeben. Andere Daten werden automatisch oder nach Ihrer Einwilligung beim Besuch der Website durch unsere IT-Systeme erfasst. Das sind vor allem technische Daten (z. B. Internetbrowser, Betriebssystem oder Uhrzeit des Seitenaufrufs).</p>

        <p><em>Wofür nutzen wir Ihre Daten?</em></p>
        <p>Ein Teil der Daten wird erhoben, um eine fehlerfreie Bereitstellung der Website zu gewährleisten. Andere Daten können zur Analyse Ihres Nutzerverhaltens verwendet werden.</p>

        <p><em>Welche Rechte haben Sie bezüglich Ihrer Daten?</em></p>
        <p>Sie haben jederzeit das Recht, unentgeltlich Auskunft über Herkunft, Empfänger und Zweck Ihrer gespeicherten personenbezogenen Daten zu erhalten. Sie haben außerdem ein Recht, die Berichtigung oder Löschung dieser Daten zu verlangen. Wenn Sie eine Einwilligung zur Datenverarbeitung erteilt haben, können Sie diese Einwilligung jederzeit für die Zukunft widerrufen. Außerdem haben Sie das Recht, unter bestimmten Umständen die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen.</p>

        <h3>2. Allgemeine Hinweise und Pflichtinformationen</h3>
        <p><strong>Datenschutz</strong></p>
        <p>Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend den gesetzlichen Datenschutzvorschriften sowie dieser Datenschutzerklärung.</p>
        <p>Wenn Sie diese Website benutzen, werden verschiedene personenbezogene Daten erhoben. Diese Datenschutzerklärung erläutert, welche Daten wir erheben und wofür wir sie nutzen. Sie erklärt auch, wie und zu welchem Zweck das geschieht.</p>
        <p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z. B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.</p>

        <p><strong>Hinweis zur verantwortlichen Stelle</strong></p>
        <p>Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:</p>
        <p>
          Dietmar Zielke<br>
          Rechtliche Betreuungen<br>
          Postfach 10 06 06<br>
          20004 Hamburg<br>
          Telefon: 0152 525 85 620<br>
          E-Mail: zielke@betreuungen-zielke.de
        </p>
        <p>Verantwortliche Stelle ist die natürliche oder juristische Person, die allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z. B. Namen, E-Mail-Adressen o. Ä.) entscheidet.</p>

        <p><strong>Widerruf Ihrer Einwilligung zur Datenverarbeitung</strong></p>
        <p>Viele Datenverarbeitungsvorgänge sind nur mit Ihrer ausdrücklichen Einwilligung möglich. Sie können eine bereits erteilte Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.</p>

        <p><strong>Beschwerderecht bei der zuständigen Aufsichtsbehörde</strong></p>
        <p>Im Falle von Verstößen gegen die DSGVO steht den Betroffenen ein Beschwerderecht bei einer Aufsichtsbehörde, insbesondere in dem Mitgliedstaat ihres üblichen Aufenthalts, ihres Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes zu. Das Beschwerderecht besteht unbeschadet anderweitiger verwaltungsrechtlicher oder gerichtlicher Rechtsbehelfe.</p>

        <h3>3. Datenerfassung auf dieser Website</h3>
        <p><strong>Kontaktformular</strong></p>
        <p>Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall von Anschlussfragen bei uns gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.</p>
        <p>Die Verarbeitung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. b DSGVO, sofern Ihre Anfrage mit der Erfüllung eines Vertrags zusammenhängt oder zur Durchführung vorvertraglicher Maßnahmen erforderlich ist. In allen übrigen Fällen beruht die Verarbeitung auf unserem berechtigten Interesse an der effektiven Bearbeitung der an uns gerichteten Anfragen (Art. 6 Abs. 1 lit. f DSGVO) oder auf Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO) falls diese abgefragt wurde.</p>
        <p>Die von Ihnen im Kontaktformular eingegebenen Daten verbleiben bei uns, bis Sie uns zur Löschung auffordern, Ihre Einwilligung zur Speicherung widerrufen oder der Zweck für die Datenspeicherung entfällt (z. B. nach abgeschlossener Bearbeitung Ihrer Anfrage). Zwingende gesetzliche Bestimmungen – insbesondere Aufbewahrungsfristen – bleiben unberührt.</p>
      </div>
    </div>
  </div>
{/if}
