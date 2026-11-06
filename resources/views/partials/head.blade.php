<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $pageTitle = trim($__env->yieldContent('title'));
@endphp

<title>{{ $pageTitle !== '' ? $pageTitle . ' · JobDesk' : 'JobDesk' }}</title>
<meta name="description" content="JobDesk — aplikasi manajemen tugas dan progres pekerjaan.">

<link rel="icon" href="{{ asset('favicon.ico') }}">

<script>
    (function () {
        try {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        } catch (error) {
            // Abaikan: mode gelap hanya preferensi tampilan.
        }
    })();
</script>

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    brand: {
                        50: '#ecfdf5',
                        100: '#d1fae5',
                        200: '#a7f3d0',
                        300: '#6ee7b7',
                        400: '#34d399',
                        500: '#10b981',
                        600: '#059669',
                        700: '#047857',
                        800: '#065f46',
                        900: '#064e3b',
                        950: '#022c22'
                    }
                },
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif']
                },
                boxShadow: {
                    soft: '0 12px 40px -18px rgba(15, 118, 110, 0.35)'
                }
            }
        }
    };
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    html {
        color-scheme: light;
    }

    html.dark {
        color-scheme: dark;
    }

    body {
        -webkit-font-smoothing: antialiased;
        text-rendering: optimizeLegibility;
    }

    ::selection {
        background-color: #a7f3d0;
        color: #064e3b;
    }

    .dark ::selection {
        background-color: #065f46;
        color: #ecfdf5;
    }

    * {
        scrollbar-width: thin;
        scrollbar-color: #a7f3d0 transparent;
    }

    .dark * {
        scrollbar-color: #334155 transparent;
    }

    *::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #a7f3d0;
        border-radius: 9999px;
    }

    .dark *::-webkit-scrollbar-thumb {
        background-color: #334155;
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes toast-in {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up .35s ease-out both;
    }

    .animate-toast-in {
        animation: toast-in .25s ease-out both;
    }

    @keyframes jd-spin {
        to {
            transform: rotate(360deg);
        }
    }

    .jd-btn-spinner {
        display: inline-block;
        width: 1em;
        height: 1em;
        margin-right: .5rem;
        vertical-align: -.125em;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-bottom-color: transparent;
        border-radius: 9999px;
        opacity: .85;
        animation: jd-spin .6s linear infinite;
    }

    details > summary {
        list-style: none;
    }

    details > summary::-webkit-details-marker {
        display: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .animate-fade-in-up,
        .animate-toast-in,
        .jd-btn-spinner {
            animation: none;
        }

        #page-progress {
            transition-duration: 0s;
        }

        #page-progress [data-progress-bar] {
            transition: none;
        }
    }
</style>

@stack('head')
