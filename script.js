// Updated script.js — marquee/round-logo animation controller
// - Creates a smooth, continuous right-to-left scroll using requestAnimationFrame
// - Duplicates the .marquee-set if needed for a seamless loop
// - Pauses on hover, touch, and keyboard focus
// - Respects prefers-reduced-motion
// - Injects a subtle rotation animation for each logo image (can be removed easily)
// - Recomputes widths on resize for correctness

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const marquee = document.querySelector('.logo-marquee');
    if (!marquee) return;

    const track = marquee.querySelector('.marquee-track');
    if (!track) return;

    // Ensure there are two sets for seamless looping; clone if only one exists
    let sets = Array.from(track.querySelectorAll('.marquee-set'));
    if (sets.length === 0) {
      console.warn('No .marquee-set elements found inside .marquee-track');
      return;
    }
    if (sets.length === 1) {
      const clone = sets[0].cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);
      sets = Array.from(track.querySelectorAll('.marquee-set'));
    }

    // Add a class to each <img> so we can animate them via injected CSS
    const allImgs = track.querySelectorAll('.marquee-item img');
    allImgs.forEach(img => img.classList.add('logo-img'));

    // Inject minimal CSS for subtle rotation (respects reduced-motion)
    const injectedCss = `
      @keyframes mqLogoSpin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      .logo-img {
        display: inline-block;
        transition: transform 200ms ease;
        will-change: transform;
        /* smaller rotation so it's subtle */
        animation: mqLogoSpin 14s linear infinite;
        transform-origin: 50% 50%;
      }
      @media (prefers-reduced-motion: reduce) {
        .logo-img { animation: none !important; }
      }
    `;
    const styleEl = document.createElement('style');
    styleEl.setAttribute('data-generated-by', 'metaqubitx-marquee-js');
    styleEl.appendChild(document.createTextNode(injectedCss));
    document.head.appendChild(styleEl);

    // Animation state
    let setWidth = sets[0].offsetWidth;
    let pos = 0; // pixels moved from 0 .. setWidth
    let lastTime = null;
    const baseSpeed = 60; // pixels per second (adjust to taste)
    let speed = baseSpeed;
    let running = true;

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reducedMotion) {
      running = false;
      // make sure the track is aligned at start (no transform)
      track.style.transform = 'translateX(0)';
    }

    // Use requestAnimationFrame for smooth movement
    function step(ts) {
      if (!lastTime) lastTime = ts;
      const deltaSec = (ts - lastTime) / 1000;
      lastTime = ts;

      if (running) {
        pos += speed * deltaSec;
        // wrap around to create seamless loop
        if (pos >= setWidth) {
          // keep remainder to avoid jumps when speed causes >width increment
          pos = pos - setWidth;
        }
        track.style.transform = `translateX(${-pos}px)`;
      }

      requestAnimationFrame(step);
    }

    // Start the loop
    requestAnimationFrame(step);

    // Pause / resume helpers
    function pauseMarquee() {
      running = false;
    }
    function resumeMarquee() {
      if (!reducedMotion) running = true;
    }

    // Pause on hover/touch/focus for usability
    marquee.addEventListener('mouseenter', pauseMarquee);
    marquee.addEventListener('mouseleave', resumeMarquee);
    marquee.addEventListener('touchstart', pauseMarquee, { passive: true });
    marquee.addEventListener('touchend', resumeMarquee, { passive: true });
    marquee.addEventListener('focusin', pauseMarquee);
    marquee.addEventListener('focusout', resumeMarquee);

    // Recompute dimensions on resize (debounced)
    let resizeTimer = null;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        // recalc the width of a single set (first set)
        setWidth = sets[0].offsetWidth || setWidth;
        // keep pos bounded
        pos = pos % setWidth;
      }, 120);
    });

    // Optional: expose a small API via the element for runtime tuning
    marquee.marqueeController = {
      pause: pauseMarquee,
      resume: resumeMarquee,
      setSpeed: function (pxPerSec) {
        speed = Number(pxPerSec) || baseSpeed;
      },
      getSpeed: function () { return speed; },
      isRunning: function () { return running; }
    };


    
    // Accessibility: ensure track has transform set so screen readers won't get weird offsets
    track.style.willChange = 'transform';

  }); // DOMContentLoaded


  // FAQ accordion behavior - append to script.js
document.addEventListener('DOMContentLoaded', function () {
  const faqButtons = document.querySelectorAll('.faq-question');
  faqButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      // close all
      faqButtons.forEach(b => {
        b.setAttribute('aria-expanded', 'false');
        const panel = document.getElementById(b.getAttribute('aria-controls'));
        if (panel) panel.hidden = true;
        const che = b.querySelector('.faq-chevron');
        if (che) che.textContent = '▼';
      });
      // toggle clicked
      if (!expanded) {
        btn.setAttribute('aria-expanded', 'true');
        const panel = document.getElementById(btn.getAttribute('aria-controls'));
        if (panel) panel.hidden = false;
        const che = btn.querySelector('.faq-chevron');
        if (che) che.textContent = '▲';
      }
    });
  });
});

  
})();