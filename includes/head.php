<?php
$pageTitle = $pageTitle ?? 'The Sovereign Ledger';
?>

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= htmlspecialchars($pageTitle); ?></title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary-container": "#456a56",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#005235",
                        "surface-container-high": "#e6e8eb",
                        "on-surface-variant": "#3e4942",
                        "primary-container": "#00875a",
                        secondary: "#416652",
                        "secondary-container": "#c0e9cf",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed-dim": "#71dba6",
                        "surface-bright": "#f7f9fc",
                        "on-error-container": "#93000a",
                        "on-tertiary-fixed-variant": "#7d2a2a",
                        "inverse-surface": "#2d3133",
                        "on-primary": "#ffffff",
                        "surface-dim": "#d8dadd",
                        "on-primary-fixed": "#002113",
                        "on-tertiary-container": "#ffffff",
                        outline: "#6e7a71",
                        "on-tertiary": "#ffffff",
                        tertiary: "#9b403e",
                        "inverse-on-surface": "#eff1f4",
                        "on-background": "#191c1e",
                        "on-secondary-fixed": "#002113",
                        "surface-tint": "#006c47",
                        "surface-variant": "#e0e3e6",
                        "on-secondary": "#ffffff",
                        primary: "#006b47",
                        "surface-container": "#eceef1",
                        "surface-container-highest": "#e0e3e6",
                        "on-secondary-fixed-variant": "#294e3b",
                        error: "#ba1a1a",
                        "tertiary-fixed": "#ffdad7",
                        surface: "#f7f9fc",
                        background: "#f7f9fc",
                        "outline-variant": "#bdcac0",
                        "surface-container-low": "#f2f4f7",
                        "on-tertiary-fixed": "#410005",
                        "on-primary-container": "#ffffff",
                        "error-container": "#ffdad6",
                        "secondary-fixed-dim": "#a7d0b7",
                        "on-surface": "#191c1e",
                        "tertiary-container": "#ba5855",
                        "primary-fixed": "#8df7c1",
                        "secondary-fixed": "#c2ecd2",
                        "inverse-primary": "#71dba6",
                        "tertiary-fixed-dim": "#ffb3af",
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem",
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"],
                        label: ["Inter"],
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        h1,
        h2,
        h3,
        .display-font {
            font-family: "Manrope", sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
            display: inline-block;
            vertical-align: middle;
        }

        .chart-gradient-primary {
            background: linear-gradient(180deg, rgba(0, 107, 71, 0.1) 0%, rgba(0, 107, 71, 0) 100%);
        }

        .chart-gradient-tertiary {
            background: linear-gradient(180deg, rgba(155, 64, 62, 0.1) 0%, rgba(155, 64, 62, 0) 100%);
        }
    </style>
</head>