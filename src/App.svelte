<script>
  import { fade } from 'svelte/transition';
  import { onMount } from 'svelte';

  let content = $state({
    hero: {
      title: "Rechtliche Betreuung in Hamburg",
      description: "Ich unterstütze Menschen in den Aufgabenbereichen, die das Betreuungsgericht festlegt. Dabei geht es um klare Absprachen, geordnete Unterlagen und Entscheidungen, die sich am Willen der betreuten Person orientieren."
    },
    leistungen: {
      title: "Leistungen",
      cards: [
        {
          title: "Gesundheit & Behörden",
          desc: "Unterstützung bei Schriftverkehr mit Krankenkassen, Ärzten, Behörden und weiteren Stellen."
        },
        {
          title: "Vermögenssorge",
          desc: "Klärung von Zahlungen, Anträgen, Bescheiden und laufenden finanziellen Angelegenheiten."
        },
        {
          title: "Wohn- und Alltagsangelegenheiten",
          desc: "Rechtliche Klärung von Mietfragen, Wohnsituation und notwendiger Unterstützung."
        },
        {
          title: "Kommunikation & Abstimmung",
          desc: "Klärung mit Behörden, Institutionen und anderen Beteiligten."
        }
      ]
    },
    fuerWen: {
      title: "Für wen?",
      subtitle: "Rechtliche Betreuung betrifft immer die konkrete Situation der betreuten Person.",
      cards: [
        {
          title: "Betroffene",
          desc: "Unterstützung bei Angelegenheiten, die Sie nicht oder nur teilweise selbst regeln können."
        },
        {
          title: "Angehörige",
          desc: "Ansprechpartner für Angehörige, soweit Austausch möglich und gewünscht ist."
        },
        {
          title: "Gerichte / Einrichtungen",
          desc: "Verlässliche Bearbeitung von Berichten, Rückfragen und Abstimmungen."
        }
      ]
    },
    ueberMich: {
      title: "Über mich",
      subtitle: "Dietmar Zielke",
      tagline: "Rechtlicher Betreuer in Hamburg & der Metropolregion",
      p1: "Ich arbeite als rechtlicher Betreuer in Hamburg und der Metropolregion. Meine Aufgabe ist es, Menschen in den Bereichen zu unterstützen, die das Betreuungsgericht festlegt.",
      p2: "Wichtig sind mir geordnete Unterlagen, erreichbare Kommunikation und Entscheidungen, die nachvollziehbar bleiben. Dabei berücksichtige ich den Willen und die Wünsche der betreuten Person, soweit dies rechtlich möglich ist.",
      bullets: [
        "Unterstützung im gerichtlichen Aufgabenbereich",
        "Klare Abstimmung mit Behörden, Gerichten und Einrichtungen",
        "Nachvollziehbare Bearbeitung von Unterlagen, Fristen und Anträgen"
      ]
    },
    soArbeiteIch: {
      title: "So arbeite ich",
      steps: [
        {
          number: "01",
          title: "Kennenlernen",
          desc: "Zuerst kläre ich die Situation, den gerichtlichen Aufgabenbereich und die vorhandenen Unterlagen."
        },
        {
          number: "02",
          title: "Klären & Abstimmen",
          desc: "Ich nehme Kontakt zu den beteiligten Stellen auf, prüfe Fristen und bereite die nächsten Schritte vor."
        },
        {
          number: "03",
          title: "Handeln & Berichten",
          desc: "Ich handele im festgelegten Aufgabenbereich und informiere das Gericht, wenn Berichte erforderlich sind."
        }
      ]
    },
    faq: {
      title: "Was ist rechtliche Betreuung?",
      explanation: "Eine rechtliche Betreuung kann eingerichtet werden, wenn ein volljähriger Mensch seine Angelegenheiten wegen Krankheit oder Behinderung ganz oder teilweise rechtlich nicht selbst besorgen kann. Das Betreuungsgericht legt fest, für welche Bereiche ein Betreuer bestellt wird.",
      items: [
        {
          category: "general",
          question: "Was ist eine rechtliche Betreuung?",
          answer: "Eine rechtliche Betreuung ist eine vom Gericht angeordnete Unterstützung. Sie betrifft nur die Aufgabenbereiche, in denen die betroffene Person rechtliche Hilfe braucht."
        },
        {
          category: "general",
          question: "Wer bestellt einen rechtlichen Betreuer?",
          answer: "Zuständig ist das Betreuungsgericht. Es prüft, ob eine Betreuung notwendig ist und welche Aufgabenbereiche betroffen sind. Eine Betreuung kann beantragt oder angeregt werden."
        },
        {
          category: "general",
          question: "Bedeutet rechtliche Betreuung \"Entmündigung\"?",
          answer: "Nein. Eine Entmündigung gibt es im deutschen Betreuungsrecht nicht. Die betreute Person behält ihre Rechte. Der Betreuer darf nur in den Bereichen handeln, die das Gericht festgelegt hat."
        },
        {
          category: "rights",
          question: "Wie läuft das Betreuungsverfahren ab?",
          answer: "Das Betreuungsgericht prüft den Bedarf. Dazu können ein ärztliches Gutachten, eine Stellungnahme der Betreuungsbehörde und eine persönliche Anhörung gehören. Danach entscheidet das Gericht, ob eine Betreuung eingerichtet wird."
        },
        {
          category: "rights",
          question: "Welche Aufgaben hat ein rechtlicher Betreuer?",
          answer: "Die Aufgaben richten sich nach dem gerichtlichen Beschluss. Häufig geht es um Behördenangelegenheiten, Gesundheitssorge, Vermögenssorge oder Wohnungsangelegenheiten. Der Betreuer unterstützt, klärt und handelt rechtlich in diesen Bereichen."
        },
        {
          category: "rights",
          question: "Kann ich den Betreuer wechseln oder die Betreuung beenden?",
          answer: "Die betreute Person kann sich an das Betreuungsgericht wenden, wenn sie einen Wechsel oder eine Beendigung prüfen lassen möchte. Das Gericht prüft auch, ob die Betreuung noch erforderlich ist."
        },
        {
          category: "costs",
          question: "Was kostet eine rechtliche Betreuung?",
          answer: "Die Vergütung beruflicher Betreuer ist gesetzlich geregelt. Ob die betreute Person Kosten selbst tragen muss oder die Staatskasse eintritt, hängt von den finanziellen Verhältnissen ab."
        },
        {
          category: "costs",
          question: "Kann ich Wünsche oder Vollmachten festlegen?",
          answer: "Ja. Mit einer Vorsorgevollmacht oder Betreuungsverfügung können Sie festhalten, wer für Sie handeln soll und welche Wünsche wichtig sind. Ob und wie dies wirkt, hängt vom Einzelfall und der rechtlichen Situation ab."
        }
      ]
    },
    kontakt: {
      title: "Kontakt",
      phone: "0152 525 85 620",
      phoneRaw: "015252585620",
      email: "zielke@betreuungen-zielke.de",
      web: "www.betreuungen-zielke.de",
      postbox: "Postfach in Hamburg",
      fax: "040-35 671 480",
      sloganTitle: "Ich bin gerne für Sie da.",
      sloganDesc: "Sie können mich telefonisch oder per E-Mail erreichen. Ich melde mich zurück, sobald es möglich ist.",
      hoursLabel: "Bürozeiten:",
      hoursVal: "Mo – Fr   9:00 – 17:00 Uhr"
    },
    legal: {
      impressum: {
        address: "Dietmar Zielke\nRechtliche Betreuungen\nPostfach 10 06 06\n20004 Hamburg",
        fax: "040-35 671 480",
        taxNumber: "46/277/04580",
        ustId: "84 531 067 933",
        iban: "DE21 1101 0101 5806 8968 70",
        bic: "SOBKDEB2XXX"
      }
    }
  });

  // Helper to set nested object properties from flat paths (e.g., "leistungen.cards[0].title")
  function setNestedValue(obj, path, value) {
    const parts = path.split('.');
    let current = obj;
    for (let i = 0; i < parts.length; i++) {
      const part = parts[i];
      const arrayMatch = part.match(/^(\w+)\[(\d+)\]$/);
      if (arrayMatch) {
        const arrayName = arrayMatch[1];
        const arrayIndex = parseInt(arrayMatch[2], 10);
        if (!current[arrayName]) {
          current[arrayName] = [];
        }
        if (i === parts.length - 1) {
          current[arrayName][arrayIndex] = value;
        } else {
          if (!current[arrayName][arrayIndex]) {
            current[arrayName][arrayIndex] = {};
          }
          current = current[arrayName][arrayIndex];
        }
      } else {
        if (i === parts.length - 1) {
          current[part] = value;
        } else {
          if (!current[part]) {
            current[part] = {};
          }
          current = current[part];
        }
      }
    }
  }

  // Parses the context.txt key-value properties format
  function parseTextConfig(rawText) {
    const lines = rawText.split('\n');
    for (const line of lines) {
      const trimmed = line.trim();
      if (!trimmed || trimmed.startsWith('#')) continue;
      const separatorIndex = trimmed.indexOf('=');
      if (separatorIndex !== -1) {
        const key = trimmed.slice(0, separatorIndex).trim();
        const val = trimmed.slice(separatorIndex + 1).trim().replace(/\\n/g, '\n');
        setNestedValue(content, key, val);
      }
    }
  }

  // Fetch updated content from context.txt on mount
  onMount(async () => {
    try {
      const response = await fetch('/de/context.txt');
      if (response.ok) {
        const rawText = await response.text();
        parseTextConfig(rawText);
      }
    } catch (e) {
      console.warn("Failed to load context.txt, using fallback content", e);
    }
  });

  // Svelte action to create a 3D tilt and glass shine effect on mouseover
  function tilt(node, maxAngle = 10) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return { destroy() {} };
    }
    const shine = document.createElement('div');
    shine.className = 'glass-shine';
    node.appendChild(shine);

    node.style.position = 'relative';
    node.style.transition = 'transform 0.15s ease-out, box-shadow 0.15s ease-out';
    node.style.transformStyle = 'preserve-3d';

    const onMouseMove = (e) => {
      const rect = node.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const w = rect.width;
      const h = rect.height;

      const rotateX = ((y / h) - 0.5) * -maxAngle;
      const rotateY = ((x / w) - 0.5) * maxAngle;

      node.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;

      const percentX = (x / w) * 100;
      const percentY = (y / h) * 100;
      shine.style.background = `radial-gradient(circle at ${percentX}% ${percentY}%, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 65%)`;
      shine.style.opacity = '1';
    };

    const onMouseEnter = () => {
      node.style.transition = 'none';
      shine.style.transition = 'opacity 0.15s ease-out';
    };

    const onMouseLeave = () => {
      node.style.transition = 'transform 0.5s ease-out, box-shadow 0.5s ease-out';
      node.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
      shine.style.transition = 'opacity 0.5s ease-out';
      shine.style.opacity = '0';
    };

    node.addEventListener('mousemove', onMouseMove);
    node.addEventListener('mouseenter', onMouseEnter);
    node.addEventListener('mouseleave', onMouseLeave);

    return {
      destroy() {
        node.removeEventListener('mousemove', onMouseMove);
        node.removeEventListener('mouseenter', onMouseEnter);
        node.removeEventListener('mouseleave', onMouseLeave);
        shine.remove();
      }
    };
  }

  // Svelte action to manage and trap keyboard focus inside dialogs/drawers
  function focusTrap(node) {
    const previousActiveElement = document.activeElement;
    const focusableSelector = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex]:not([tabindex="-1"]), *[contenteditable]';

    function getFocusableElements() {
      return Array.from(node.querySelectorAll(focusableSelector))
        .filter(el => {
          return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
        });
    }

    // Move focus inside the dialog after it mounts/transitions in
    setTimeout(() => {
      const focusable = getFocusableElements();
      if (focusable.length > 0) {
        // Try to focus close button first, else first interactive element
        const closeBtn = focusable.find(el => el.classList.contains('modal-close-btn') || el.classList.contains('drawer-close-btn'));
        if (closeBtn) {
          closeBtn.focus();
        } else {
          focusable[0].focus();
        }
      }
    }, 50);

    function handleKeydown(event) {
      if (event.key !== 'Tab') return;

      const focusable = getFocusableElements();
      if (focusable.length === 0) {
        event.preventDefault();
        return;
      }

      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      const active = document.activeElement;

      if (event.shiftKey) {
        if (active === first) {
          last.focus();
          event.preventDefault();
        }
      } else {
        if (active === last) {
          first.focus();
          event.preventDefault();
        }
      }
    }

    node.addEventListener('keydown', handleKeydown);

    return {
      destroy() {
        node.removeEventListener('keydown', handleKeydown);
        if (previousActiveElement && typeof previousActiveElement.focus === 'function') {
          previousActiveElement.focus();
        }
      }
    };
  }

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

  let activeSection = $state('start');

  // Scroll Spy for Navigation Active State
  $effect(() => {
    const sections = ['faq', 'leistungen', 'fuer-wen', 'ueber-mich', 'so-arbeite-ich'];
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
  let selectedServiceIndex = $state(null);
  let selectedAudienceIndex = $state(null);
  let activeFaqTab = $state('general');

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
      if (sectionId === 'kontakt') {
        setTimeout(() => {
          const firstInput = el.querySelector('input:not([type="hidden"]), textarea, select');
          if (firstInput) {
            firstInput.focus();
          } else {
            el.focus();
          }
        }, 200); // 200ms ensures drawer unmount and focusTrap destruction have finished
      }
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
      <img src="/media/logo.svg" alt="Dietmar Zielke Rechtliche Betreuungen" class="logo-img" />
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

    <!-- Desktop Nav (Hidden on Mobile) -->
    <nav class="nav desktop-nav">
      <ul class="nav-list">
        <li><a href="#faq" use:directionAwareHover class="nav-link" class:active={activeSection === 'faq'} onclick={(e) => { e.preventDefault(); handleNavClick('faq'); }}>Über rechtliche Betreuung</a></li>
        <li><a href="#leistungen" use:directionAwareHover class="nav-link" class:active={activeSection === 'leistungen'} onclick={(e) => { e.preventDefault(); handleNavClick('leistungen'); }}>Leistungen</a></li>
        <li><a href="#fuer-wen" use:directionAwareHover class="nav-link" class:active={activeSection === 'fuer-wen'} onclick={(e) => { e.preventDefault(); handleNavClick('fuer-wen'); }}>Für wen?</a></li>
        <li><a href="#ueber-mich" use:directionAwareHover class="nav-link" class:active={activeSection === 'ueber-mich'} onclick={(e) => { e.preventDefault(); handleNavClick('ueber-mich'); }}>Über mich</a></li>
        <li><a href="#so-arbeite-ich" use:directionAwareHover class="nav-link" class:active={activeSection === 'so-arbeite-ich'} onclick={(e) => { e.preventDefault(); handleNavClick('so-arbeite-ich'); }}>So arbeite ich</a></li>
      </ul>
    </nav>
  </div>
</header>

<!-- Mobile Nav (Outside Header to avoid backdrop-filter containing block bug) -->
<nav class="nav mobile-nav {isMobileMenuOpen ? 'open' : ''}">
  <ul class="nav-list">
    <li><a href="#faq" use:directionAwareHover class="nav-link" class:active={activeSection === 'faq'} onclick={(e) => { e.preventDefault(); handleNavClick('faq'); }}>Über rechtliche Betreuung</a></li>
    <li><a href="#leistungen" use:directionAwareHover class="nav-link" class:active={activeSection === 'leistungen'} onclick={(e) => { e.preventDefault(); handleNavClick('leistungen'); }}>Leistungen</a></li>
    <li><a href="#fuer-wen" use:directionAwareHover class="nav-link" class:active={activeSection === 'fuer-wen'} onclick={(e) => { e.preventDefault(); handleNavClick('fuer-wen'); }}>Für wen?</a></li>
    <li><a href="#ueber-mich" use:directionAwareHover class="nav-link" class:active={activeSection === 'ueber-mich'} onclick={(e) => { e.preventDefault(); handleNavClick('ueber-mich'); }}>Über mich</a></li>
    <li><a href="#so-arbeite-ich" use:directionAwareHover class="nav-link" class:active={activeSection === 'so-arbeite-ich'} onclick={(e) => { e.preventDefault(); handleNavClick('so-arbeite-ich'); }}>So arbeite ich</a></li>
  </ul>
</nav>

<main>
  <!-- Hero Section -->
  <section id="start" class="hero-section section-spacing">
    <div class="container hero-grid">
      <div class="hero-content reveal">
        <h1 >{content.hero.title}</h1>
        <p >{content.hero.description}</p>
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
    </div>
  </section>

  <!-- FAQ Section (Über rechtliche Betreuung) -->
  <section id="faq" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title" >{content.faq.title}</h2>
      </div>

      <div class="faq-tabs reveal">
        <button class="faq-tab-btn" class:active={activeFaqTab === 'general'} onclick={() => { activeFaqTab = 'general'; activeFaq = null; }}>Allgemeines</button>
        <button class="faq-tab-btn" class:active={activeFaqTab === 'rights'} onclick={() => { activeFaqTab = 'rights'; activeFaq = null; }}>Rechte & Ablauf</button>
        <button class="faq-tab-btn" class:active={activeFaqTab === 'costs'} onclick={() => { activeFaqTab = 'costs'; activeFaq = null; }}>Kosten & Vorsorge</button>
      </div>

      <div class="faq-grid">
        <div class="faq-explanation reveal">
          <p >{content.faq.explanation}</p>
        </div>

        <div class="faq-list reveal reveal-delay-1">
          {#each content.faq.items as item, index}
            {#if item.category === activeFaqTab}
              <div class="faq-item {activeFaq === index ? 'active' : ''}">
                <button class="faq-trigger" onclick={() => toggleFaq(index)} aria-expanded={activeFaq === index}>
                  <span >{item.question}</span>
                  <svg class="faq-trigger-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div class="faq-content" style={activeFaq === index ? 'max-height: 200px;' : 'max-height: 0;'}>
                  <p >{item.answer}</p>
                </div>
              </div>
            {/if}
          {/each}
        </div>
      </div>
    </div>
  </section>

  <!-- Leistungen Section -->
  <section id="leistungen" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title" >{content.leistungen.title}</h2>
      </div>

      <div class="leistungen-grid">
        <!-- Card 1 -->
        <div class="service-card reveal" use:tilt onclick={() => selectedServiceIndex = 0} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedServiceIndex = 0; } }} role="button" tabindex="0" aria-label="Leistungsdetails für {content.leistungen.cards[0].title} anzeigen">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <h3 >{content.leistungen.cards[0].title}</h3>
          <p >{content.leistungen.cards[0].desc}</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>

        <!-- Card 2 -->
        <div class="service-card reveal reveal-delay-1" use:tilt onclick={() => selectedServiceIndex = 1} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedServiceIndex = 1; } }} role="button" tabindex="0" aria-label="Leistungsdetails für {content.leistungen.cards[1].title} anzeigen">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
          </div>
          <h3 >{content.leistungen.cards[1].title}</h3>
          <p >{content.leistungen.cards[1].desc}</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>

        <!-- Card 3 -->
        <div class="service-card reveal reveal-delay-2" use:tilt onclick={() => selectedServiceIndex = 2} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedServiceIndex = 2; } }} role="button" tabindex="0" aria-label="Leistungsdetails für {content.leistungen.cards[2].title} anzeigen">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <h3 >{content.leistungen.cards[2].title}</h3>
          <p >{content.leistungen.cards[2].desc}</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>

        <!-- Card 4 -->
        <div class="service-card reveal reveal-delay-3" use:tilt onclick={() => selectedServiceIndex = 3} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedServiceIndex = 3; } }} role="button" tabindex="0" aria-label="Leistungsdetails für {content.leistungen.cards[3].title} anzeigen">
          <div class="service-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
          <h3 >{content.leistungen.cards[3].title}</h3>
          <p >{content.leistungen.cards[3].desc}</p>
          <svg class="service-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </div>
      </div>
    </div>
  </section>

  <!-- Für Wen Section -->
  <section id="fuer-wen" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title" >{content.fuerWen.title}</h2>
        <p class="section-subtitle" >{content.fuerWen.subtitle}</p>
      </div>

      <div class="for-whom-grid">
        <!-- Card 1 -->
        <div class="whom-card reveal" use:tilt onclick={() => selectedAudienceIndex = 0} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedAudienceIndex = 0; } }} role="button" tabindex="0" aria-label="Details für {content.fuerWen.cards[0].title} anzeigen">
          <div class="whom-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="whom-info">
            <h3 >{content.fuerWen.cards[0].title}</h3>
            <p >{content.fuerWen.cards[0].desc}</p>
            <svg class="whom-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="whom-card reveal reveal-delay-1" use:tilt onclick={() => selectedAudienceIndex = 1} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedAudienceIndex = 1; } }} role="button" tabindex="0" aria-label="Details für {content.fuerWen.cards[1].title} anzeigen">
          <div class="whom-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div class="whom-info">
            <h3 >{content.fuerWen.cards[1].title}</h3>
            <p >{content.fuerWen.cards[1].desc}</p>
            <svg class="whom-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="whom-card reveal reveal-delay-2" use:tilt onclick={() => selectedAudienceIndex = 2} onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); selectedAudienceIndex = 2; } }} role="button" tabindex="0" aria-label="Details für {content.fuerWen.cards[2].title} anzeigen">
          <div class="whom-icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <div class="whom-info">
            <h3 >{content.fuerWen.cards[2].title}</h3>
            <p >{content.fuerWen.cards[2].desc}</p>
            <svg class="whom-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Über mich Section -->
  <section id="ueber-mich" class="section-spacing">
    <div class="container about-grid">
      <div class="about-content reveal">
        <h2 class="section-title" >{content.ueberMich.title}</h2>
        <h3 class="about-subtitle" >{content.ueberMich.subtitle}</h3>
        <p class="about-tagline" >{content.ueberMich.tagline}</p>
        
        <p >{content.ueberMich.p1}</p>
        <p >{content.ueberMich.p2}</p>
        
        <ul class="about-bullets">
          <li>
            <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span >{content.ueberMich.bullets[0]}</span>
          </li>
          <li>
            <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span >{content.ueberMich.bullets[1]}</span>
          </li>
          <li>
            <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span >{content.ueberMich.bullets[2]}</span>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <!-- So arbeite ich Section -->
  <section id="so-arbeite-ich" class="section-spacing">
    <div class="container">
      <div class="section-header reveal">
        <h2 class="section-title" >{content.soArbeiteIch.title}</h2>
      </div>

      <div class="timeline-row">
        <!-- Step 1 -->
        <div class="timeline-step reveal">
          <div class="timeline-step-header">
            <span class="timeline-number">01</span>
            <h3 >{content.soArbeiteIch.steps[0].title}</h3>
          </div>
          <p >{content.soArbeiteIch.steps[0].desc}</p>
        </div>

        <!-- Separator Chevron -->
        <svg class="timeline-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>

        <!-- Step 2 -->
        <div class="timeline-step reveal reveal-delay-1">
          <div class="timeline-step-header">
            <span class="timeline-number">02</span>
            <h3 >{content.soArbeiteIch.steps[1].title}</h3>
          </div>
          <p >{content.soArbeiteIch.steps[1].desc}</p>
        </div>

        <!-- Separator Chevron -->
        <svg class="timeline-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>

        <!-- Step 3 -->
        <div class="timeline-step reveal reveal-delay-2">
          <div class="timeline-step-header">
            <span class="timeline-number">03</span>
            <h3 >{content.soArbeiteIch.steps[2].title}</h3>
          </div>
          <p >{content.soArbeiteIch.steps[2].desc}</p>
        </div>
      </div>
    </div>
  </section>


  <!-- Kontakt Section -->
  <section id="kontakt" class="section-spacing">
    <div class="container kontakt-grid">
      <!-- Column 1 -->
      <div class="contact-column-left reveal">
        <h2 class="section-title" >{content.kontakt.title}</h2>
        <div class="contact-detail-list">
          <!-- Phone -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <div class="contact-detail-text">
              <p><a href="tel:{content.kontakt.phoneRaw}" class="hover:underline" >{content.kontakt.phone}</a></p>
            </div>
          </div>

          <!-- Email -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <div class="contact-detail-text">
              <p><a href="mailto:{content.kontakt.email}" class="hover:underline" >{content.kontakt.email}</a></p>
            </div>
          </div>

          <!-- Web -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
            </svg>
            <div class="contact-detail-text">
              <p><a href="https://{content.kontakt.web}" target="_blank" rel="noopener noreferrer" class="hover:underline" >{content.kontakt.web}</a></p>
            </div>
          </div>

          <!-- Fax -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <div class="contact-detail-text">
              <p>Fax: <span >{content.kontakt.fax}</span></p>
            </div>
          </div>

          <!-- Postbox -->
          <div class="contact-detail-item">
            <svg class="contact-detail-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <div class="contact-detail-text">
              <p >{content.kontakt.postbox}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Column 2 -->
      <div class="contact-column-middle reveal reveal-delay-1">
        <div class="contact-column-content">
          <h3 class="contact-slogan-title" >{content.kontakt.sloganTitle}</h3>
          <p class="contact-slogan-desc" >{content.kontakt.sloganDesc}</p>
          <div class="contact-business-hours">
            <h4 >{content.kontakt.hoursLabel}</h4>
            <p >{content.kontakt.hoursVal}</p>
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
      <img src="/media/logo.svg" alt="Dietmar Zielke Rechtliche Betreuungen" class="logo-img" />
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
  <div class="drawer-overlay" onclick={() => openModal = null} onkeydown={(e) => { if (e.key === 'Escape' || e.key === 'Enter') openModal = null; }} role="button" tabindex="-1" aria-label="Schließen" transition:fade={{ duration: 150 }}>
    <div class="drawer-container" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.stopPropagation()} role="dialog" aria-modal="true" tabindex="-1" use:focusTrap aria-labelledby="impressum-title">
      <button class="drawer-close-btn" onclick={() => openModal = null} aria-label="Schließen">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="drawer-content">
        <span class="drawer-badge">Rechtliches</span>
        <h2 id="impressum-title">Impressum</h2>
        <div class="drawer-separator"></div>
        
        <div class="drawer-body-text">
          <h3>Angaben gemäß § 5 TMG</h3>
          <p style="white-space: pre-line;" >{content.legal.impressum.address}</p>

          <h3>Kontakt</h3>
          <p>
            Telefon: <span >{content.kontakt.phone}</span><br>
            Fax: <span >{content.legal.impressum.fax}</span><br>
            E-Mail: <span >{content.kontakt.email}</span>
          </p>

          <h3>Berufsbezeichnung und Aufsichtsbehörde</h3>
          <p>
            Berufsbezeichnung: Rechtlicher Betreuer (Berufsbetreuer)<br>
            Zuständige Aufsichtsbehörde (Betreuungsbehörde): Hamburg / zuständiges Amtsgericht Hamburg.
          </p>

          <h3>Steuerliche Angaben</h3>
          <p>
            Steuernummer: <span >{content.legal.impressum.taxNumber}</span><br>
            Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz: <span >{content.legal.impressum.ustId}</span>
          </p>

          <h3>Bankverbindung</h3>
          <p>
            IBAN: <span >{content.legal.impressum.iban}</span><br>
            BIC: <span >{content.legal.impressum.bic}</span>
          </p>

          <h3>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h3>
          <p style="white-space: pre-line;" >{content.legal.impressum.address}</p>

          <h3>Streitschlichtung</h3>
          <p>
            Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit: <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer" style="color: var(--primary);">https://ec.europa.eu/consumers/odr/</a>.<br>
            Unsere E-Mail-Adresse finden Sie oben im Impressum.
          </p>
          <p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>
        </div>
      </div>
    </div>
  </div>
{/if}

{#if openModal === 'datenschutz'}
  <div class="modal-overlay" onclick={() => openModal = null} onkeydown={(e) => { if (e.key === 'Escape' || e.key === 'Enter') openModal = null; }} role="button" tabindex="-1" aria-label="Schließen" transition:fade={{ duration: 150 }}>
    <div class="modal-container" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.stopPropagation()} role="dialog" aria-modal="true" tabindex="-1" use:focusTrap aria-labelledby="datenschutz-title">
      <div class="modal-header">
        <h2 id="datenschutz-title">Datenschutzerklärung</h2>
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
        <p>Technische Daten werden verarbeitet, damit diese Website angezeigt werden kann. Angaben aus dem Kontaktformular werden genutzt, um Ihre Anfrage zu bearbeiten.</p>

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
          <span style="white-space: pre-line;">{content.legal.impressum.address}</span><br>
          Telefon: {content.kontakt.phone}<br>
          E-Mail: {content.kontakt.email}
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

{#if selectedServiceIndex !== null}
  {@const service = content.leistungen.cards[selectedServiceIndex]}
  <div class="drawer-overlay" onclick={() => selectedServiceIndex = null} onkeydown={(e) => { if (e.key === 'Escape' || e.key === 'Enter') selectedServiceIndex = null; }} role="button" tabindex="-1" aria-label="Schließen" transition:fade={{ duration: 150 }}>
    <div class="drawer-container" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.stopPropagation()} role="dialog" aria-modal="true" tabindex="-1" use:focusTrap aria-labelledby="service-title">
      <button class="drawer-close-btn" onclick={() => selectedServiceIndex = null} aria-label="Schließen">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="drawer-content">
        <span class="drawer-badge">Leistung</span>
        <h2 id="service-title">{service.detailTitle || service.title}</h2>
        {#if service.detailSubtitle}
          <p class="drawer-subtitle">{service.detailSubtitle}</p>
        {/if}
        <div class="drawer-separator"></div>
        
        {#if service.detailDesc}
          <p class="drawer-desc">{service.detailDesc}</p>
        {:else}
          <p class="drawer-desc">{service.desc}</p>
        {/if}
        
        <ul class="drawer-bullets">
          {#if service.detailBullet1}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{service.detailBullet1}</span>
            </li>
          {/if}
          {#if service.detailBullet2}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{service.detailBullet2}</span>
            </li>
          {/if}
          {#if service.detailBullet3}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{service.detailBullet3}</span>
            </li>
          {/if}
          {#if service.detailBullet4}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{service.detailBullet4}</span>
            </li>
          {/if}
        </ul>
        
        {#if service.detailNote}
          <div class="drawer-note-box">
            <svg class="drawer-note-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="drawer-note">{service.detailNote}</p>
          </div>
        {/if}
        
        <div class="drawer-actions">
          <button class="btn btn-primary" onclick={() => { selectedServiceIndex = null; handleNavClick('kontakt'); }}>
            Kontakt aufnehmen
            <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
{/if}

{#if selectedAudienceIndex !== null}
  {@const audience = content.fuerWen.cards[selectedAudienceIndex]}
  <div class="drawer-overlay" onclick={() => selectedAudienceIndex = null} onkeydown={(e) => { if (e.key === 'Escape' || e.key === 'Enter') selectedAudienceIndex = null; }} role="button" tabindex="-1" aria-label="Schließen" transition:fade={{ duration: 150 }}>
    <div class="drawer-container" onclick={(e) => e.stopPropagation()} onkeydown={(e) => e.stopPropagation()} role="dialog" aria-modal="true" tabindex="-1" use:focusTrap aria-labelledby="audience-title">
      <button class="drawer-close-btn" onclick={() => selectedAudienceIndex = null} aria-label="Schließen">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      
      <div class="drawer-content">
        <span class="drawer-badge">Zielgruppe</span>
        <h2 id="audience-title">{audience.detailTitle || audience.title}</h2>
        {#if audience.detailSubtitle}
          <p class="drawer-subtitle">{audience.detailSubtitle}</p>
        {/if}
        <div class="drawer-separator"></div>
        
        {#if audience.detailDesc}
          <p class="drawer-desc">{audience.detailDesc}</p>
        {:else}
          <p class="drawer-desc">{audience.desc}</p>
        {/if}
        
        <ul class="drawer-bullets">
          {#if audience.detailBullet1}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{audience.detailBullet1}</span>
            </li>
          {/if}
          {#if audience.detailBullet2}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{audience.detailBullet2}</span>
            </li>
          {/if}
          {#if audience.detailBullet3}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{audience.detailBullet3}</span>
            </li>
          {/if}
          {#if audience.detailBullet4}
            <li>
              <svg class="bullet-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span>{audience.detailBullet4}</span>
            </li>
          {/if}
        </ul>
        
        {#if audience.detailNote}
          <div class="drawer-note-box">
            <svg class="drawer-note-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="drawer-note">{audience.detailNote}</p>
          </div>
        {/if}
        
        <div class="drawer-actions">
          <button class="btn btn-primary" onclick={() => { selectedAudienceIndex = null; handleNavClick('kontakt'); }}>
            Kontakt aufnehmen
            <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
{/if}
