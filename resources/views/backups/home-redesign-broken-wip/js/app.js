import './bootstrap';
import { replaceIcons } from './icons';
import { initHomeMotion, destroyHomeMotion, refreshHomeMotion } from './home';

let iconFrame = null;

function refreshIcons(source) {
  if (iconFrame) cancelAnimationFrame(iconFrame);
  iconFrame = requestAnimationFrame(() => {
    replaceIcons();
  });
}

function bootMotion() {
  if (document.querySelector('#hero') || document.querySelector('[data-reveal]') || document.querySelector('[data-count-up]')) {
    initHomeMotion();
  }
}

document.addEventListener('DOMContentLoaded', () => {
  refreshIcons('DOMContentLoaded');
  bootMotion();
});

document.addEventListener('livewire:navigated', () => {
  refreshIcons('navigated');
  destroyHomeMotion();
  bootMotion();
});

function registerHooks() {
  if (window.Livewire) {
    window.Livewire.hook('morph.added', () => refreshIcons('morph.added'));
    window.Livewire.hook('morph.updated', () => refreshIcons('morph.updated'));
  }
}

if (window.Livewire) {
  registerHooks();
} else {
  document.addEventListener('livewire:init', registerHooks);
  document.addEventListener('livewire:initialized', registerHooks);
}

window.showToast = function (type, title, message) {
  const container = document.getElementById('toast-container');
  if (!container) return;

  const id = 'toast-' + Date.now();
  const colors = {
    success: 'bg-success-light border-success/30 text-success',
    error: 'bg-danger-light border-danger/30 text-danger',
    warning: 'bg-warning-light border-warning/30 text-warning',
    info: 'bg-info-light border-info/30 text-info',
  };
  const iconsMap = {
    success: 'check-circle',
    error: 'x-circle',
    warning: 'alert-triangle',
    info: 'info',
  };

  const html = `
    <div id="${id}" class="toast ${colors[type]} opacity-0 translate-y-2 transition-all duration-300" role="alert">
      <i data-lucide="${iconsMap[type]}" class="w-5 h-5 mt-0.5 shrink-0"></i>
      <div class="flex-1 min-w-0">
        <p class="font-semibold text-sm">${title}</p>
        <p class="text-sm opacity-80">${message}</p>
      </div>
      <button onclick="dismissToast('${id}')" class="shrink-0 opacity-60 hover:opacity-100">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    </div>
  `;

  container.insertAdjacentHTML('beforeend', html);
  replaceIcons(container);

  requestAnimationFrame(() => {
    const el = document.getElementById(id);
    if (el) {
      el.classList.remove('opacity-0', 'translate-y-2');
      el.classList.add('opacity-100', 'translate-y-0');
    }
  });

  setTimeout(() => window.dismissToast(id), 5000);
};

window.dismissToast = function (id) {
  const el = document.getElementById(id);
  if (el) {
    el.classList.add('opacity-0', 'translate-y-2');
    setTimeout(() => el.remove(), 300);
  }
};