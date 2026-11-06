(() => {
    'use strict';

    const root = document.documentElement;

    /* ------------------------------------------------------------------
     * Tema terang / gelap
     * ------------------------------------------------------------------ */
    const themeButtons = document.querySelectorAll('[data-theme-toggle]');

    const syncThemeButtons = () => {
        const isDark = root.classList.contains('dark');

        themeButtons.forEach((button) => {
            button.setAttribute('aria-label', isDark ? 'Aktifkan tema terang' : 'Aktifkan tema gelap');
            button.setAttribute('title', isDark ? 'Tema terang' : 'Tema gelap');
        });
    };

    themeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const isDark = root.classList.toggle('dark');

            try {
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            } catch (error) {
                // Preferensi tema tidak wajib tersimpan.
            }

            syncThemeButtons();
        });
    });

    syncThemeButtons();

    /* ------------------------------------------------------------------
     * Sidebar mobile
     * ------------------------------------------------------------------ */
    const sidebar = document.getElementById('app-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const sidebarToggles = document.querySelectorAll('[data-sidebar-toggle]');

    const closeSidebar = () => {
        if (!sidebar) return;

        sidebar.classList.add('-translate-x-full');
        sidebarOverlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    const openSidebar = () => {
        if (!sidebar) return;

        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    sidebarToggles.forEach((button) => {
        button.addEventListener('click', () => {
            if (window.innerWidth >= 1024) return;

            if (sidebar?.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    });

    sidebarOverlay?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });

    /* ------------------------------------------------------------------
     * Menu pengguna
     * ------------------------------------------------------------------ */
    const userMenu = document.getElementById('user-menu');
    const userMenuToggle = document.querySelector('[data-user-menu-toggle]');

    const closeUserMenu = () => {
        userMenu?.classList.add('hidden');
        userMenuToggle?.setAttribute('aria-expanded', 'false');
    };

    userMenuToggle?.addEventListener('click', (event) => {
        event.stopPropagation();

        const isHidden = userMenu?.classList.toggle('hidden');

        userMenuToggle.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
    });

    document.addEventListener('click', (event) => {
        if (!userMenu || !userMenuToggle) return;
        if (userMenu.contains(event.target) || userMenuToggle.contains(event.target)) return;

        closeUserMenu();
    });

    /* ------------------------------------------------------------------
     * Notifikasi flash
     * ------------------------------------------------------------------ */
    const dismissFlash = (flash) => {
        if (!flash) return;

        flash.classList.add('opacity-0', '-translate-y-2');

        window.setTimeout(() => flash.remove(), 250);
    };

    document.querySelectorAll('[data-flash]').forEach((flash) => {
        window.setTimeout(() => dismissFlash(flash), 5500);
    });

    document.querySelectorAll('[data-dismiss-flash]').forEach((button) => {
        button.addEventListener('click', () => dismissFlash(button.closest('[data-flash]')));
    });

    /* ------------------------------------------------------------------
     * Modal konfirmasi hapus
     * ------------------------------------------------------------------ */
    const confirmModal = document.getElementById('confirm-modal');
    let pendingForm = null;

    const openConfirmModal = (form) => {
        if (!confirmModal) {
            form.dataset.confirmed = '1';
            form.requestSubmit();
            return;
        }

        pendingForm = form;

        const message = confirmModal.querySelector('[data-confirm-message]');
        const title = confirmModal.querySelector('#confirm-modal-title');

        if (message) {
            message.textContent = form.dataset.confirm || 'Data yang dihapus tidak dapat dikembalikan.';
        }

        if (title && form.dataset.confirmTitle) {
            title.textContent = form.dataset.confirmTitle;
        }

        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');

        confirmModal.querySelector('[data-confirm-accept]')?.focus();
    };

    const closeConfirmModal = () => {
        if (!confirmModal) return;

        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
        pendingForm = null;
    };

    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement)) return;
            if (!form.matches('form[data-confirm]')) return;
            if (form.dataset.confirmed === '1') return;

            event.preventDefault();
            event.stopImmediatePropagation();

            openConfirmModal(form);
        },
        true
    );

    confirmModal?.querySelectorAll('[data-confirm-cancel]').forEach((button) => {
        button.addEventListener('click', closeConfirmModal);
    });

    confirmModal?.querySelector('[data-confirm-accept]')?.addEventListener('click', () => {
        if (!pendingForm) return;

        const form = pendingForm;

        form.dataset.confirmed = '1';
        closeConfirmModal();
        form.requestSubmit();
    });

    /* ------------------------------------------------------------------
     * Toggle password
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const target = document.getElementById(button.dataset.target);

        if (!target) return;

        button.addEventListener('click', () => {
            const show = target.type === 'password';

            target.type = show ? 'text' : 'password';

            button.querySelector('[data-eye-open]')?.classList.toggle('hidden', show);
            button.querySelector('[data-eye-closed]')?.classList.toggle('hidden', !show);
            button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });

    /* ------------------------------------------------------------------
     * Preview file lampiran
     * ------------------------------------------------------------------ */
    const humanSize = (bytes) => {
        if (bytes < 1024) return `${bytes} B`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`;

        return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
    };

    document.querySelectorAll('input[type="file"][data-file-input]').forEach((input) => {
        const list = document.getElementById(input.dataset.fileList);
        const error = document.querySelector(`[data-file-error="${input.dataset.fileList}"]`);
        const maxFiles = Number.parseInt(input.dataset.maxFiles || '5', 10);
        const maxSize = Number.parseFloat(input.dataset.maxSize || '5') * 1024 * 1024;

        if (!list) return;

        input.addEventListener('change', () => {
            const files = Array.from(input.files || []);
            let message = '';

            list.innerHTML = '';

            if (files.length > maxFiles) {
                message = `Maksimal ${maxFiles} file dalam satu kali unggah.`;
            }

            const oversized = files.find((file) => file.size > maxSize);

            if (!message && oversized) {
                message = `File "${oversized.name}" melebihi ${input.dataset.maxSize || 5} MB.`;
            }

            if (message) {
                input.value = '';
                list.classList.add('hidden');

                if (error) {
                    error.textContent = message;
                    error.classList.remove('hidden');
                }

                return;
            }

            error?.classList.add('hidden');
            list.classList.toggle('hidden', files.length === 0);

            files.forEach((file) => {
                const item = document.createElement('span');
                item.className = 'inline-flex max-w-full items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300';

                const name = document.createElement('span');
                name.className = 'max-w-[14rem] truncate';
                name.textContent = file.name;

                const size = document.createElement('span');
                size.className = 'shrink-0 text-[10px] text-slate-400';
                size.textContent = humanSize(file.size);

                item.append(name, size);
                list.appendChild(item);
            });
        });
    });

    /* ------------------------------------------------------------------
     * Form yang bisa dibuka / ditutup
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-toggle-target]').forEach((button) => {
        const target = document.getElementById(button.dataset.toggleTarget);

        if (!target) return;

        button.addEventListener('click', () => {
            const isHidden = target.classList.toggle('hidden');

            button.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
        });
    });

    /* ------------------------------------------------------------------
     * Filter otomatis
     * ------------------------------------------------------------------ */
    document.querySelectorAll('[data-auto-submit]').forEach((field) => {
        field.addEventListener('change', () => field.form?.requestSubmit());
    });

    /* ------------------------------------------------------------------
     * Loader navigasi
     * ------------------------------------------------------------------ */
    const pageProgress = document.getElementById('page-progress');
    const pageProgressBar = pageProgress?.querySelector('[data-progress-bar]');

    const LOADER_DELAY = 150;
    const LOADER_TIMEOUT = 10000;

    let loaderDelayTimer = null;
    let loaderSafetyTimer = null;
    let progressTimer = null;
    let progressValue = 0;

    function resetProgress() {
        window.clearInterval(progressTimer);
        progressTimer = null;
        progressValue = 0;

        if (pageProgressBar) {
            pageProgressBar.style.width = '0%';
        }

        pageProgress?.classList.add('opacity-0');
    }

    function startProgress() {
        if (!pageProgress || !pageProgressBar) return;

        window.clearInterval(progressTimer);
        progressValue = 8;
        pageProgressBar.style.width = '8%';
        pageProgress.classList.remove('opacity-0');

        progressTimer = window.setInterval(() => {
            if (progressValue >= 90) return;

            progressValue = Math.min(90, progressValue + Math.max(1.5, (90 - progressValue) * 0.14));
            pageProgressBar.style.width = `${progressValue}%`;
        }, 240);
    }

    function completeProgress() {
        if (!pageProgress || !pageProgressBar) return;

        window.clearInterval(progressTimer);
        progressTimer = null;

        if (!pageProgress.classList.contains('opacity-0')) {
            pageProgressBar.style.width = '100%';
        }

        window.setTimeout(resetProgress, 200);
    }

    function stopLoader() {
        window.clearTimeout(loaderDelayTimer);
        window.clearTimeout(loaderSafetyTimer);
        loaderDelayTimer = null;
        loaderSafetyTimer = null;

        completeProgress();
    }

    function showLoader() {
        window.clearTimeout(loaderDelayTimer);
        window.clearTimeout(loaderSafetyTimer);

        loaderDelayTimer = window.setTimeout(startProgress, LOADER_DELAY);
        loaderSafetyTimer = window.setTimeout(stopLoader, LOADER_TIMEOUT);
    }

    document.addEventListener(
        'click',
        (event) => {
            if (event.defaultPrevented) return;
            if (event.button !== 0) return;
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

            const link = event.target instanceof Element ? event.target.closest('a[href]') : null;

            if (!link) return;
            if (link.target && link.target !== '_self') return;
            if (link.hasAttribute('download')) return;
            if (link.hasAttribute('data-no-loader')) return;

            const href = link.getAttribute('href');

            if (!href || href.startsWith('#')) return;
            if (/^(mailto|tel|javascript):/i.test(href)) return;

            let target;

            try {
                target = new URL(link.href, window.location.href);
            } catch (error) {
                return;
            }

            if (target.origin !== window.location.origin) return;
            if (target.pathname === window.location.pathname && target.search === window.location.search) return;

            showLoader();
        },
        true
    );

    document.addEventListener(
        'submit',
        (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement)) return;
            if (event.defaultPrevented) return;
            if (form.hasAttribute('data-no-loader')) return;
            if (form.dataset.submitting === '1') return;

            // Form hapus baru boleh memicu loader setelah dikonfirmasi.
            if (form.matches('form[data-confirm]') && form.dataset.confirmed !== '1') return;

            showLoader();
        },
        true
    );

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            document.querySelectorAll('form[data-submitting="1"]').forEach((form) => {
                form.dataset.submitting = '0';

                form.querySelectorAll('[data-loading-active="1"]').forEach((button) => {
                    restoreButton(button);
                });
            });
        }

        stopLoader();
    });

    /* ------------------------------------------------------------------
     * Cegah submit ganda
     * ------------------------------------------------------------------ */
    function setButtonLoading(button) {
        if (!(button instanceof HTMLElement)) return;
        if (button.dataset.loadingActive === '1') return;

        const label = button.dataset.loadingText || 'Memproses…';

        button.dataset.loadingActive = '1';
        button.disabled = true;
        button.classList.add('cursor-not-allowed', 'opacity-70');

        if (button instanceof HTMLInputElement) {
            button.dataset.originalValue = button.value;
            button.value = label;

            return;
        }

        button.dataset.originalHtml = button.innerHTML;
        button.setAttribute('aria-busy', 'true');

        const spinner = document.createElement('span');
        spinner.className = 'jd-btn-spinner';
        spinner.setAttribute('aria-hidden', 'true');

        const text = document.createElement('span');
        text.textContent = label;

        button.replaceChildren(spinner, text);
    }

    function restoreButton(button) {
        if (!(button instanceof HTMLElement)) return;
        if (button.dataset.loadingActive !== '1') return;

        button.dataset.loadingActive = '0';
        button.disabled = false;
        button.classList.remove('cursor-not-allowed', 'opacity-70');

        if (button instanceof HTMLInputElement) {
            if (button.dataset.originalValue !== undefined) {
                button.value = button.dataset.originalValue;
                delete button.dataset.originalValue;
            }

            return;
        }

        if (button.dataset.originalHtml !== undefined) {
            button.innerHTML = button.dataset.originalHtml;
            delete button.dataset.originalHtml;
        }

        button.removeAttribute('aria-busy');
    }

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement)) return;
        if (!form.matches('form[data-submit-once]')) return;

        if (form.dataset.submitting === '1') {
            event.preventDefault();
            return;
        }

        form.dataset.submitting = '1';

        const submitter = event.submitter instanceof HTMLElement ? event.submitter : null;
        const button = submitter || form.querySelector('[type="submit"]');

        if (!button) return;

        window.setTimeout(() => setButtonLoading(button), 0);
    });

    /* ------------------------------------------------------------------
     * Escape untuk menutup lapisan
     * ------------------------------------------------------------------ */
    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        closeUserMenu();
        closeConfirmModal();
        closeSidebar();
    });
})();
