import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let lenis = null;
let lenisTickerRef = null;
let heroBound = false;

function ensureScrollDriver() {
  if (prefersReducedMotion || lenis) return;

  lenis = new Lenis({
    duration: 1.15,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smoothWheel: true,
    touchMultiplier: 1.4,
  });

  lenis.on('scroll', ScrollTrigger.update);

  lenisTickerRef = (time) => {
    lenis.raf(time * 1000);
  };
  gsap.ticker.add(lenisTickerRef);
  gsap.ticker.lagSmoothing(0);
}

export function destroyHomeMotion() {
  ScrollTrigger.getAll().forEach((trigger) => trigger.kill());
  gsap.killTweensOf('[data-reveal], [data-hero-item], [data-parallax-speed], [data-count-up]');

  if (lenis) {
    if (lenisTickerRef) {
      gsap.ticker.remove(lenisTickerRef);
      lenisTickerRef = null;
    }
    lenis.destroy();
    lenis = null;
  }
  heroBound = false;
}

export function initHomeMotion(root = document) {
  if (prefersReducedMotion) return;

  const scope = root.nodeType === 1 ? root : document;
  ensureScrollDriver();

  const hero = scope.querySelector('#hero');
  if (hero && !heroBound) {
    heroBound = true;

    gsap.from(hero.querySelectorAll('[data-hero-item]'), {
      y: 28,
      opacity: 0,
      duration: 0.9,
      ease: 'power3.out',
      stagger: 0.12,
      delay: 0.15,
    });

    const img = hero.querySelector('[data-parallax-speed]');
    if (img) {
      const speed = parseFloat(img.getAttribute('data-parallax-speed')) || 0.22;
      gsap.fromTo(
        img,
        { yPercent: -speed * 10 },
        {
          yPercent: speed * 10,
          ease: 'none',
          scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: 'bottom top',
            scrub: true,
          },
        }
      );
    }
  }

  scope.querySelectorAll('[data-reveal]').forEach((el) => {
    if (el.dataset.revealDone) return;
    el.dataset.revealDone = '1';

    gsap.fromTo(
      el,
      { opacity: 0, y: 26 },
      {
        opacity: 1,
        y: 0,
        duration: 0.85,
        ease: 'power2.out',
        delay: parseFloat(el.dataset.revealDelay || 0),
        scrollTrigger: {
          trigger: el,
          start: 'top 88%',
          once: true,
        },
      }
    );
  });

  scope.querySelectorAll('[data-count-up]').forEach((el) => {
    if (el.dataset.counted) return;
    el.dataset.counted = '1';

    const target = parseInt(el.getAttribute('data-count-up'), 10) || 0;
    const obj = { val: 0 };

    gsap.to(obj, {
      val: target,
      duration: 1.6,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: el,
        start: 'top 90%',
        once: true,
      },
      onUpdate: () => {
        el.textContent = Math.round(obj.val).toLocaleString('en-US');
      },
    });
  });

  ScrollTrigger.refresh();
}

export function refreshHomeMotion() {
  if (!prefersReducedMotion) {
    ScrollTrigger.refresh();
  }
}